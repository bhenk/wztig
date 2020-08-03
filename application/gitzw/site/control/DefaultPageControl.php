<?php
namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\logging\Log;
use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\control\menu\MenuManager;

class DefaultPageControl implements iPageControl {
    
    private $title = 'gitzw art';
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
    }
    
    public function setContentFile(?string $contentFile) {
        $this->contentFile = $contentFile;
    }
    
    public function setTitle($title) {
        $this->title = $title;
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
    	$this->addScript($manager->getScript());
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
        Log::log()->debug(static::class.'->'.__METHOD__);
        require GZ::TEMPLATES . '/frame/a_page.php';
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
            echo '<link rel="canonical" href="'.$this->canonicalURI.'" />';
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
    
    protected function renderContent() {
        if (isset($this->contentFile)) {
            if (file_exists($this->contentFile)) {
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
            return '&nbsp;&bull; &nbsp;<a href="/admin">admin</a>';
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

