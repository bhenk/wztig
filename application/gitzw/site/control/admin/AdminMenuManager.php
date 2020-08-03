<?php

namespace gitzw\site\control\admin;

use gitzw\site\control\menu\MenuManager;

class AdminMenuManager extends MenuManager {
	
	private $structure = [
			'overview pages'=>[
					['admin', '/admin'],
					['server', '/admin/server'],
					['resources', '/admin/resources']
			],
			'edit'=>[
					['new images', '/admin/new-images']
			]
	];
	
	function __construct(string $current=NULL, $style=self::STYLE_VERTICAL) {
		parent::__construct($style);
		$this->constructMenu($current);
	}
	
	private function constructMenu(string $current) {
		foreach($this->structure as $heading=>$links) {
			$item = $this->addItem($heading, NULL);
			foreach($links as $link) {
				$selected = $current == $link[1];
				$item->setSelected($selected);
				$item->addSub($link[0], $link[1], $selected);
			}
		}
	}
}

