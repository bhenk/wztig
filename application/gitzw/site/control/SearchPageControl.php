<?php

namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\control\menu\Pager;
use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\model\Search;
use gitzw\site\model\SiteResources;

class SearchPageControl extends DefaultPageControl {
	
	protected string $action;
	protected $visart = 'all';
	protected $activity = 'all';
	protected $category = 'all';
	protected $year = 'all';
	protected $fullNames = array();
	protected $title_en;
	protected $title_nl;
	protected $media;
	protected $width;
	protected $height;
	protected $date;
	protected $rIsHidden = false;
	protected $rIsNotHidden = false;
	protected $rIsFrontPage = false;
	protected $rIsNotFrontPage = false;
	protected $longId;
	
	protected $results = array();
	
	protected $pager;
	protected $itemCount;
	protected $resultFactor;
	protected $start = 0;
	private $itemsPerPage = 5;
	
	function __construct(array $path) {
		header("Cache-Control: max-age=300, must-revalidate");
		$this->setPath($path);
		$this->setTemplate(self::COLUMN_3);
		$this->setTitle('search gitzw art');
		$this->addStylesheet('/css/form.css');
		$this->action = '/'.implode('/', array_slice($path, 1));
	}
	
	public function renderPage() {
		if (Site::get()->requestMethod() == 'GET' or Site::get()->requestMethod() == 'HEAD') {
			$this->setContentFile(GZ::TEMPLATES.'/frame/search-form.php');
			parent::renderPage();
		} else {
			$this->handlePost();
		}
	}
	
	private function handlePost() {
		$showForm = FALSE;
		if (empty($_POST)) {
			$input = json_decode(file_get_contents('php://input'), true);
			if ($input['reason'] == 'select_changed') {
				$this->handleSelectChanged(Security::cleanInput($input));
				return;
			}
			$data = Security::cleanInput($input['payload']);
			$paging = Security::cleanInput($input['paging']);
			$this->start = max(0, intval($paging['start']));
			if ($paging['start'] === 'form') {
				$showForm = TRUE;
			}
		} else {
			$data = Security::cleanInput($_POST);
		}
		$this->visart = $data['visart'];
		$this->activity = $data['activity'];
		$this->category = $data['category'];
		$this->year = $data['year'];
		$this->title_en = $data['title_en'];
		$this->title_nl = $data['title_nl'];
		$this->media = $data['media'];
		$this->width = $data['width'];
		$this->height = $data['height'];
		$this->date = $data['date'];
		$this->rIsHidden = $data['rishidden'] == 'rishidden';
		$this->rIsNotHidden = $data['risnothidden'] == 'risnothidden';
		$this->rIsFrontPage = $data['risfrontpage'] == 'risfrontpage';
		$this->rIsNotFrontPage = $data['risnotfrontpage'] == 'risnotfrontpage';
		$this->longId = $data['longid'];
		
		if ($showForm == TRUE) {
			$this->setContentFile(GZ::TEMPLATES.'/frame/search-form.php');
			parent::renderPage();
			return;
		}
		
		$this->setContentFile(GZ::TEMPLATES.'/frame/search-results.php');
		$this->fullNames = SiteResources::get()->getFullNames($data);
		$query = ['', $this->visart, $this->activity, $this->category, $this->year];
		$search = new Search($data);
// 		if ($search->isRelevant()) {
// 			$callback = [$search, 'inspect'];
// 		} else {
// 			$callback = NULL;
// 		}
		$callback = [$search, 'inspect'];
		$this->results = SiteResources::get()->listResources($query, $callback);
		$maxResult = 0;
		$this->results = array_filter($this->results, function($a) use (&$maxResult) {
			$maxResult = max($maxResult, $a[0]);
			return $a[0] >= 0;
		});
		usort($this->results, function($a, $b) {
			return $b[0] <=> $a[0];
		});
		$this->itemCount = count($this->results);
		$this->resultFactor = 100;
		if ($maxResult > 100) {
			$this->resultFactor = $maxResult;
		}
		
		$this->pager = new Pager($this->start, $this->itemsPerPage, $this->itemCount, $this->action);
		$this->pager->setTemplate(Pager::AJAX_TEMPLATE);
		
		parent::renderPage();
	}
	
	private function handleSelectChanged(array $data) {
		$tree = SiteResources::get()->getTree($data);
		header('Content-Type: application/json');
		echo json_encode($tree);		
	}
	
	protected function getJsonForSelects() : string {
		$data = [
				'visart'=>$this->visart,
				'activity'=>$this->activity,
				'category'=>$this->category,
				'year'=>$this->year
		];
		return json_encode(SiteResources::get()->getTree($data));
	}
	
	protected function getPageResources() {
		if ($this->start >= $this->itemCount) {
			return [];
		} else {
			return array_slice($this->results, $this->start, $this->itemsPerPage);
		}
	}
	
// 	protected function renderFooter() {
// 		echo 'visart: '.$this->visart.' category: '.$this->category;
// 	}
	
	
}

