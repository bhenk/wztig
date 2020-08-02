<?php
namespace gitzw\site\control\admin;

use gitzw\site\control\DefaultPageControl;
use gitzw\GZ;
use gitzw\site\data\Security;
use gitzw\site\model\SiteResources;

/**
 * Shows overview page for admins.
 *
 */
class AdminPageControl extends DefaultPageControl {
    
    function __construct() {
        $this->setContentFile(GZ::TEMPLATES.'/admin/admin_page.php');
        $this->addStylesheet('/css/admin.min.css');
        //$this->addScript(GZ::SCRIPTS.'/var-view.js');
        
        $this->addStylesheet('/css/js/json-viewer.css');
        $this->addScriptLink('/js/json-viewer.js');
        $this->addNavigation('/show-site', 'show-site');
    }
    
    protected function getUserName() {
        return Security::get()->getSessionUser()->getFullName();
    }
    
    protected function renderVarViews() {
        $vars = SiteResources::getSite()->getChildByName('var');
        foreach($vars->getChildren() as $var) {
            (new VarView($var))->render();
        }
    }
    
    protected function renderSite() {
        $site = SiteResources::getSite();
        $site->loadChildren();
        $site->loadResources();
        return json_encode($site); // JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
    }
}

