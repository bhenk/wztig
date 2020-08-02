<?php
namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\model\Path;


class VarView
{
    private $var;
    
    function __construct(Path $var) {
        $var->loadChildren();
        $var->loadResources();
        $this->var = $var;
        
    }
    public function render() {
        require GZ::TEMPLATES.'/admin/var_view.php';
    }
    
    protected function getVarName() {
        return $this->var->getName();
    }
    
    protected function getVarData() {
        return json_encode($this->var, JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
    }
}

