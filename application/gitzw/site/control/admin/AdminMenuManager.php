<?php

namespace gitzw\site\control\admin;

use gitzw\site\control\menu\MenuManager;

class AdminMenuManager extends MenuManager {
	
	private $structure = [
			'overview'=>[
					['admin', '/admin'],
					['server', '/admin/server'],
					['resources', '/admin/resources'],
					['list images', 'admin/list-images']
			],
			'edit'=>[
					['scan images', '/admin/scan-images']
			],
			'misc'=>[
					['raise exception', '/admin/raise-exception']
			]			
	];
	
	function __construct(string $current=NULL, $style=self::STYLE_VERTICAL) {
		parent::__construct($style);
		$this->constructMenu($current);
	}
	
	private function constructMenu(string $current=NULL) {
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

