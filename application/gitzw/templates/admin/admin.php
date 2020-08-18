<?php
namespace gitzw\templates\admin;

use gitzw\site\data\Security;

?>

<h1><?php echo Security::get()->getSessionUser()->getFullName(); ?></h1>

<table>
<tr><td>email</td><td><?php echo Security::get()->getSessionUser()->getEmail(); ?></td></tr>
<tr><td>last login</td><td><?php echo Security::get()->getLastLogin(); ?></td></tr>
</table>

<h1>Gitzw.art</h1>
version: RC_0.0.2 &nbsp;&bull;&nbsp; date: 2020-08-18