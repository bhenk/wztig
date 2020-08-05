<?php
namespace gitzw\site\control\menu;

use gitzw\GZ;

class MenuManager {
	
	const STYLE_HORIZONTAL = 0;
	const STYLE_VERTICAL = 1;
    
    private $items = array();
    private $style = self::STYLE_HORIZONTAL;
    
    function __construct($style=self::STYLE_HORIZONTAL) {
    	$this->style = $style;
    }
    
    public function add(MenuItem $item) : MenuItem {
    	$this->items[] = $item;
    	return $item;
    }
    
    public function addItem(string $text, string $link=NULL, $selected=FALSE) : MenuItem {
    	$item = new MenuItem($text, $link, $selected);
    	return $this->add($item);
    }
    
    public function getStylesheet() : string {
    	if ($this->style == self::STYLE_HORIZONTAL) {
    		return '/css/menu/nav-menu-hor.css';
    	} else {
    		return '/css/menu/nav-menu-ver.css';
    	}
    }
    
    public function getScript() : string {
    	return GZ::TEMPLATES . '/frame/nav-menu.js';
    }
    
    public function render() {
    	echo '<div class="sidenav">';
    	foreach($this->items as $item) {
    		$item->render();
    	}
    	echo '</div>';
    }
    
    
}

