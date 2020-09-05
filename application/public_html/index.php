<?php
namespace public_html;

require_once '../gitzw/GZ.php';

use gitzw\site\handle\Gitz;

Gitz::get()->handleRequestURI();
