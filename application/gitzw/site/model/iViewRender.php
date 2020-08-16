<?php
namespace gitzw\site\model;

interface iViewRender {
    
    public function render(string $template, array $args=NULL);
    
}