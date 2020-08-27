<?php
namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\logging\Log;
use gitzw\site\model\SiteResources;
use gitzw\site\handle\Gitz;

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
    
    protected function endRender() {
    	Gitz::get()->setStructuredData($this->getStructuredData());
    }
    
    public function getStructuredData() : array {
    	return [
    			"@context"=>"http://schema.org",
    			"@type"=>"Organization",
    			"@id"=>"https://gitzw.art",
    			"url"=>"https://gitzw.art",
    			"name"=>"gitzw.art",
    			"logo"=>["https://gitzw.art/logo.png", "https://gitzw.art/logo-small.png"],
    			"address"=>[
	    			"@type"=>"PostalAddress",
	    			"addressLocality"=>"Netherlands, Amsterdam",
	    			"postalCode"=>"1094 HP",
	    			"streetAddress"=>"Javastraat 126 R"
    			],
    			"description"=>"Site to show artist portfolios"
    	];
    }
    
    protected function renderHomeContent() {
        $template = GZ::TEMPLATES . '/views/simpel_domain_view.php';
        $initial = SiteResources::get()->getChildByName(self::DEFAULT_CATEGORY);
        foreach ($initial->getChildren() as $child) {
            $child->render($template);
        }
    }
    
}

