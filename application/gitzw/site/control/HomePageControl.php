<?php
namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\logging\Log;
use gitzw\site\model\SiteResources;

/**
 *
 * @author ecco
 *        
 */
class HomePageControl extends DefaultPageControl
{
    
    const DEFAULT_CATEGORY = 'var';

    /**
     *
     * @param $contentFile        
     */
    public function __construct()
    {
        $this->setContentFile(GZ::TEMPLATES.'/home.php');
        $this->constructMenu();
        Log::log()->info(__METHOD__);
    }
    
    private function constructMenu() {
    	$visarts = SiteResources::get()->getChildByName('var')->getChildren();
    	$manager = new MenuManager();
    	foreach($visarts as $visart) {
    		$manager->addItem($visart->getName(), $visart->getFullNamePath());
    	}
    	$this->setMenuManager($manager);
    }
    
    protected function renderHomeContent() {
        $template = GZ::TEMPLATES . '/views/simpel_domain_view.php';
        $initial = SiteResources::get()->getChildByName(self::DEFAULT_CATEGORY);
        foreach ($initial->getChildren() as $child) {
            $child->render($template);
        }
    }
    
}

