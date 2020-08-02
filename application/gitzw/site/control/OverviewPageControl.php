<?php
namespace gitzw\site\control;

use gitzw\site\ImageData;
//use gitzw\site\NavMenuManager;
use gitzw\site\logging\Log;

/* Creates an overview page of a particular year of a particular technique of a 
 * particular subdomain.
 * 
 * Or rather, creates a list of all entries found in
 * <pre>
 *  GZ_DATA_PATH/{$subdomainName}/img/{$dirname0}/{$dirname1}/image_data.json
 * </pre>
 */
class OverviewPageControl extends DefaultPageControl {
    
    private $subdomainName;
    private $dirname0;
    private $dirname1;
    
    function __construct($subdomainName, $dirname0, $dirname1) {
        $this->subdomainName = $subdomainName;
        $this->dirname0 = $dirname0;
        $this->dirname1 = $dirname1;
        $this->addStylesheet('/css/nav-menu.min.css');
        $this->addStylesheet('/css/img_overview.min.css');
        Log::log()->info(__METHOD__);
    }
    
    protected function renderNavigation() {
        //$menuManager = new NavMenuManager($this->subdomainName, $this->dirname0, $this->dirname1);
        // $menuManager->renderMenu();
    }
    
    protected function renderContent() {
        $image_data_file = GZ_DATA_PATH . '/' .$this->subdomainName . '/img/' .
            $this->dirname0 . '/' . $this->dirname1 . '/image_data.json';
        $image_data = json_decode(file_get_contents($image_data_file), TRUE);
        $view_file = GZ_TEMPLATES_PATH . '/views/img_overview.php';
        $img_folder = '/img/' .  $this->subdomainName . '/img/' . 
            $this->dirname0 . '/' . $this->dirname1;
        foreach ($image_data as $img_name => $data) {
            $imd = new ImageData($img_name, $data, $view_file, $img_folder);
            $imd->renderImage();
        }
    }
}