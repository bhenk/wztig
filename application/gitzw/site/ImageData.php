<?php
namespace gitzw\site;

class ImageData {
    
    const TITLE_EN = 'title_en';
    const TITLE_NL = 'title_nl';
    const PREFERRED_TITLE = 'preferred_title';
    const TECHNIQUE = 'technique';
    const HEIGHT = 'height';
    const WIDTH = 'width';
    const DATE = 'date';
    
    private $img_name;
    private $img_data;
    private $viewTemplate;
    private $img_folder;
    
    function __construct($img_name, $img_data, $viewTemplate, $img_folder) {
        $this->img_name = $img_name;
        $this->img_data = $img_data;
        $this->viewTemplate = $viewTemplate;
        $this->img_folder = $img_folder;
    }
    
    public function renderImage() {
        require $this->viewTemplate;
    }
    
    protected function getImageFile($postfix='') {
        $imgFile = $this->img_folder . '/' . $this->img_name . $postfix . '.jpg';
        echo $imgFile;
    }
    
    protected function getTitle() {
        $first = $this->img_data[self::PREFERRED_TITLE] == 'en' ? 
            $this->img_data[self::TITLE_EN] : $this->img_data[self::TITLE_NL];
        $last = $this->img_data[self::PREFERRED_TITLE] == 'en' ?
            $this->img_data[self::TITLE_NL] : $this->img_data[self::TITLE_EN];
        if ($first == '') {
            $first = $last;
            $last = '';
        }
        if ($last != '') {
            $first .= ' (' . $last . ')';
        }
        echo $first;
    }
    
    protected function getTechnique() {
        echo $this->img_data[self::TECHNIQUE];
    }
    
    protected function getDimensions() {
        if ($this->img_data[self::HEIGHT] == '' or $this->img_data[self::WIDTH] == '') {
            echo ' ';
            return;
        }
        $hc = floatval($this->img_data[self::HEIGHT]);
        $wc = floatval($this->img_data[self::WIDTH]);
        $hi = $hc/2.54;
        $wi = $wc/2.54;
        echo  $hc . ' x ' . $wc . ' cm. [h x w] ' . number_format($hi, 1) . ' x ' . 
            number_format($wi, 1) . ' in.';
    }
    
    protected function getDate() {
        echo $this->img_data[self::DATE];
    }
}