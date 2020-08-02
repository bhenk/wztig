<?php
namespace gitzw\site\control\visar;

use gitzw\site\model\Path;

class MenuManager {
    
    private Path $segment;
    private array $path;
    
    function __construct(Path $segment, array $path) {
        $this->segment = $segment;
        $this->path = $path;
    }
    
    public function renderMenu() {
        echo '<div class="sidenav">';
        $work = $this->segment->getChildByName('work');
        // categories
        foreach($work->getChildren() as $cat) {
            if ($cat->getFullNamePath() == $this->path[3]) {
                echo '<button class="current-btn">' . $cat->getName() . '</button>';
                echo '<div class="current-container">';                
            } else {
                echo '<button class="dropdown-btn">' . $cat->getName() . '</button>';
                echo '<div class="dropdown-container">';
            }
            // years
            foreach ($cat->getChildren() as $year) {
                if ($year->getFullNamePath() == $this->path[4] and $cat->getFullNamePath() == $this->path[3]) {
                    echo '<a class="current-link" href="' . 
                        $year->getResourcePath() . '">' . $year->getFullName() . '</a>';
                } else {
                    echo '<a href="' . $year->getResourcePath() . '">' . $year->getFullName() . '</a>';
                }
            }
            echo '</div>';
        }
        
        if (isset($this->path[3])) {
            echo '<a href="' . $this->segment->getResourcePath() . '">' . 
                $this->segment->getName() . '</a>';
        }
        
        echo '</div>';
    }
}

