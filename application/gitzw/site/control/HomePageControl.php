<?php
namespace gitzw\site\control;

use gitzw\GZ;
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
        Log::log()->info(__METHOD__);
    }
    
    protected function renderHomeContent() {
        $template = GZ::TEMPLATES . '/views/simpel_domain_view.php';
        $initial = SiteResources::get()->getChildByName(self::DEFAULT_CATEGORY);
        foreach ($initial->getChildren() as $child) {
            $child->render($template);
        }
    }
    
    public function getStructuredData()  {
    	return str_replace(['{version}', '{datePublished}'], 
    			[GZ::VERSION, GZ::VERSION_DATE  ], file_get_contents(GZ::DATA.'/sd/home.json'));
    }
    
}

