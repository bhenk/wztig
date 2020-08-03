<?php

namespace gitzw\site\control\menu;

class MenuItem {
	
	private $text;
	private $link;
	private $subItems = array();
	private $selected = FALSE;
	
	function __construct(string $text, string $link=NULL, bool $selected=FALSE) {
		$this->text = $text;
		$this->link = $link;
		$this->selected = $selected;
	}
	
	public function addSub(string $text, string $link=NULL, bool $selected=FALSE) : MenuItem {
		$item = new MenuItem($text, $link, $selected);
		$this->subItems[] = $item;
		return $item;
	}
	
	public function isLink() : bool {
		return isset($this->link);
	}
	
	public function setSelected($selected) {
		if (!$this->selected) {
			$this->selected = $selected;
		}
	}
	
	public function render() {
		if ($this->isLink()) {
			$class = $this->selected ? ' class="current-link"' : '';
			echo '<a'.$class.' href="'.$this->link.'">'.$this->text.'</a>';
		} else {
			if ($this->selected) {
				echo '<button class="current-btn">'.$this->text.'</button>';
				echo '<div class="current-container">';
			} else {
				echo '<button class="dropdown-btn">'.$this->text.'</button>';
				echo '<div class="dropdown-container">';
			}
			foreach ($this->subItems as $item) {
				$item->render();
			}
			echo '</div>';
		}
	}
}

