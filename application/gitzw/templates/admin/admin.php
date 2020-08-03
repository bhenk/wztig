<?php
namespace gitzw\templates\admin;

use gitzw\site\data\Security;

?>

<h1><?php echo Security::get()->getSessionUser()->getFullName(); ?></h1>

<table>
<tr><td>email</td><td><?php echo Security::get()->getSessionUser()->getEmail(); ?></td></tr>
<tr><td>last login</td><td><?php echo Security::get()->getLastLogin(); ?></td></tr>
</table>
