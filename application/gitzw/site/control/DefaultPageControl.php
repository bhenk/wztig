<?php
namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\logging\Log;
use gitzw\site\model\SiteResources;

class DefaultPageControl implements iPageControl {
	
	const COLUMN_2 = GZ::TEMPLATES . '/frame/a_page2.php';
	const COLUMN_3 = GZ::TEMPLATES . '/frame/a_page3.php';
	const DEFAULT_TEMPLATE = self::COLUMN_2;
    
	private $template;
	protected $path;
    private $title = 'gitzw.art';
    private $stylesheets = array();
    private $scriptLinks = array();
    private $navigation = array();
    private $footer = TRUE;
    private $footerTemplate;
    private $contentFile;
    private $scripts = array();
    private $canonicalURI;
    private $menuManager;
    
    
    function __construct($contentFile = NULL) {
        if (isset($contentFile)) {
            $this->contentFile = $contentFile;
        }
        Log::log()->info(__METHOD__);
        Log::log()->info($_SERVER["REQUEST_METHOD"]);
    }
    
    public function setTemplate(string $template) {
    	$this->template = $template;
    }
    
    public function setPath(array $path) {
    	$this->path = $path;
    }
    
    public function setContentFile(?string $contentFile) {
        $this->contentFile = $contentFile;
    }
    
    public function setTitle($title) {
    	$this->title = 'gitzw.art '.$title;
    }
    
    public function addStylesheet($styleSheet) {
        $this->stylesheets[] = $styleSheet;
    }
    
    public function addScriptLink(string $fileName) {
        $this->scriptLinks[] = $fileName;
    }
    
    public function setCanonicalURI(string $cURI) {
        $this->canonicalURI = $cURI;
    }
    
    public function addNavigation($link, $text, $selected = FALSE) {
        $this->navigation[$link] = [$text, $selected];
    }
    
    public function setMenuManager(MenuManager $manager) {
    	$this->addStylesheet($manager->getStylesheet());
    	//$this->addScript($manager->getScript());
    	$this->menuManager = $manager;
    }
    
    public function addScript(string $script) {
        $this->scripts[] = $script;
    }
    
    public function setFooter(bool $footer) {
        $this->footer = $footer;
    }
    
    public function setFooterTemplate(string $footerTemplate) {
        $this->footerTemplate = $footerTemplate;
    }
    
    
    public function renderPage() {
    	if (is_null($this->menuManager) and empty($this->navigation)) {
    		$this->setDefaultMenuManager();
    	}
        Log::log()->info(static::class.'->'.__METHOD__);
        require $this->getTemplate();
    }
    
    private function getTemplate() : string {
    	if (is_null($this->template)) {
    		return self::DEFAULT_TEMPLATE;
    	} else {
    		return $this->template;
    	}
    }
    
    private function setDefaultMenuManager() {
    	$visarts = SiteResources::get()->getChildByName('var')->getChildren();
    	$manager = new MenuManager();
    	foreach($visarts as $visart) {
    		$manager->addItem($visart->getName(), '/'.$visart->getFullNamePath());
    	}
    	$this->setMenuManager($manager);
    }
    
    protected function getPath() : ?array {
    	return $this->path;
    }
    
    protected function renderTitle() {
        echo $this->title;
    }
    
    protected function renderStylesheets() {
        foreach (array_unique($this->stylesheets) as $value) {
            echo '<link rel="stylesheet" href="' . $value . '">';
        }
    }
    
    protected function renderScriptLinks() {
        foreach(array_unique($this->scriptLinks) as $value) {
            echo '<script type="text/javascript" src="'.$value.'"></script>';
        }
    }
    
    protected function renderCanonicalURI() {
        if (isset($this->canonicalURI)) {
            echo '<link rel="canonical" href="'.$this->canonicalURI.'">';
        } else {
            echo '';
        }
    }
    
    protected function renderLogo() {
        require GZ::TEMPLATES . '/frame/logo.php';
    }
    
    protected function renderNavigation() {
    	if (isset($this->menuManager)) {
    		$this->menuManager->render();
    	} else {
	        echo '<ul>';
	        foreach ($this->navigation as $link => $params) {
	            if ($params[1]) {
	                $clazz = ' class="current"';
	            } else {
	                $clazz = '';
	            }
	            echo '<li' . $clazz . '><a href="' . $link . '">' . $params[0] . '</a></li>';
	        }
	        echo '</ul>';
    	}
    }
    
    protected function renderButtonPanel() {
    	require GZ::TEMPLATES . '/frame/button-panel.php';
    }
    
    protected function renderContent() {
        if (isset($this->contentFile)) {
            if (file_exists($this->contentFile)) {
            	Log::log()->info('Render content: '.$this->contentFile);
                require $this->contentFile;
            } else {
                Log::log()->error('content file "'.$this->contentFile.'" does not exist');
            }
        }
    }
    
    protected function renderThirdColumn() {
        
    }
    
    protected function renderFooter() {
        if (is_null($this->footerTemplate)) {
            $this->footerTemplate = GZ::TEMPLATES . '/frame/footer.php';
        }
        !$this->footer or require $this->footerTemplate;
    }
    
    protected function getCopyRight() : string {
        return '';
    }
    
    protected function getClientIp() : string {
        if (Security::get()->canLogin()) {
            return '&nbsp;&nbsp;&bull; &nbsp;'.Site::get()->clientIp();
        } else {
            return '';
        }
    }
    
    protected function getActiveUser() : string {
        $activeUser = Security::get()->getSessionUser();
        if (isset($activeUser)) {
            return '&nbsp;&bull; &nbsp;'.str_replace(' ', '&nbsp;', $activeUser->getFullName());
        } else {
            return '';
        }
    }
    
    protected function getUserLink() : string {
        if (Security::get()->getSessionUser() != NULL) {
            return '&nbsp;&bull; &nbsp;<a href="/logout">logout</a>';
        } elseif (Security::get()->canLogin()) {
            return '&nbsp;&bull; &nbsp;<a href="/login">login</a>';
        } else {
            return '';
        }
    }
    
    protected function getAdminLink() {
        if (Security::get()->hasAccess()) {
        	if ($this->path[5] == 'overview') {
        		$link = '/'.implode('/', array_slice($this->path, 1, 5));
        		return '&nbsp;&bull; &nbsp;<a href="'.$link.'/adm">admin</a>';
        	} else {
            	return '&nbsp;&bull; &nbsp;<a href="/admin">admin</a>';
        	}
        } else {
            return '';
        }
    }
    
    protected function renderScripts() {
        foreach(array_unique($this->scripts) as $script) {
            echo '<script>';
            require_once $script;
            echo '</script>';
        }
    }
    
    
    
}

