<?php
namespace gitzw\templates\admin;

use gitzw\site\data\Site;
use gitzw\GZ;

function logLevelForInt(int $i) : ?string {
    return [100=>'DEBUG',
        200=>'INFO',
        250=>'NOTICE',
        300=>'WARNING',
        400=>'ERROR',
        500=>'CRITICAL',
        550=>'ALERT',
        600=>'EMERGENCY'][$i];
}

/** @var mixed $this */
?>
<h1 class="collapse-button">Server</h1>
<div class="collapsable">
<?php echo '__FILE__ : '.__FILE__.'<br/>'?>
<?php echo 'self::class : '.self::class.'<br/>'?>
</div>

<h2 class="collapse-button"><?php echo 'class '.GZ::class ?></h2>
<table class="collapsable">
<tr><td>ROOT</td><td><?php echo GZ::ROOT ?></td></tr>
<tr><td>DATA</td><td><?php echo GZ::DATA ?></td></tr>
<tr><td>LOG_DIRECTORY</td><td><?php echo GZ::LOG_DIRECTORY ?></td></tr>
<tr><td>GITZWART</td><td><?php echo GZ::GITZWART ?></td></tr>
<tr><td>TEMPLATES</td><td><?php echo GZ::TEMPLATES ?></td></tr>
<tr><td>MINIFY_HTML</td><td><?php echo GZ::MINIFY_HTML ? 'TRUE' : 'FALSE' ?></td></tr>
<tr><td>SHOW_TRACE</td><td><?php echo GZ::SHOW_TRACE ? 'TRUE' : 'FALSE' ?></td></tr>
<tr><td>LOG_LEVEL</td><td><?php echo GZ::LOG_LEVEL.' '.logLevelForInt(GZ::LOG_LEVEL)?></td></tr>
<tr><td>LOG_OUTPUT</td><td><?php echo GZ::LOG_OUTPUT ?></td></tr>
</table>

<h2 class="collapse-button"><?php echo 'class '.Site::class ?></h2>
<table class="collapsable">
<tr><td>document root</td><td><?php echo Site::get()->documentRoot() ?></td></tr>
<tr><td>hostname</td><td><?php echo Site::get()->hostName() ?></td></tr>
<tr><td>actual link</td><td><?php echo Site::get()->actualLink() ?></td></tr>
<tr><td>client ip</td><td><?php echo Site::get()->clientIp() ?></td></tr>
<tr><td>redirect location</td><td><?php echo Site::get()->redirectLocation('example/cannonical/path') ?></td></tr>
</table>


<h2 class="collapse-button">$_SERVER</h2>
<?php 
$indicesServer = array('PHP_SELF',
    'argv',
    'argc',
    'GATEWAY_INTERFACE',
    'SERVER_ADDR',
    'SERVER_NAME',
    'SERVER_SOFTWARE',
    'SERVER_PROTOCOL',
    'REQUEST_METHOD',
    'REQUEST_TIME',
    'REQUEST_TIME_FLOAT',
    'QUERY_STRING',
    'DOCUMENT_ROOT',
    'HTTP_ACCEPT',
    'HTTP_ACCEPT_CHARSET',
    'HTTP_ACCEPT_ENCODING',
    'HTTP_ACCEPT_LANGUAGE',
    'HTTP_CONNECTION',
    'HTTP_HOST',
    'HTTP_REFERER',
    'HTTP_USER_AGENT',
    'HTTPS',
    'REMOTE_ADDR',
    'REMOTE_HOST',
    'REMOTE_PORT',
    'REMOTE_USER',
    'REDIRECT_REMOTE_USER',
    'SCRIPT_FILENAME',
    'SERVER_ADMIN',
    'SERVER_PORT',
    'SERVER_SIGNATURE',
    'PATH_TRANSLATED',
    'SCRIPT_NAME',
    'REQUEST_URI',
    'PHP_AUTH_DIGEST',
    'PHP_AUTH_USER',
    'PHP_AUTH_PW',
    'AUTH_TYPE',
    'PATH_INFO',
    'ORIG_PATH_INFO');

echo '<table class="collapsable">';
foreach ($indicesServer as $arg) {
    if (isset($_SERVER[$arg])) {
        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>';
    } else {
        echo '<tr><td>'.$arg.'</td><td>(not set)</td></tr>';
    }
}
echo '</table>';
?>

<h2 class="collapse-button">Environment</h2>
<div class="collapsable">
getenv()<br/>&nbsp;<br/>
<table>
<?php 
foreach(getenv() as $key=>$value) {
    echo '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
}
?>
</table>
</div>

<h2 class="collapse-button">PHP ini file</h2>
<div class="collapsable">
<?php echo 'loaded file: '.php_ini_loaded_file() ?>
</div>

<h2 class="collapse-button">Interface between web server and PHP</h2>
<div class="collapsable">
<?php echo 'php sapi name: '.php_sapi_name() ?>
</div>

<h2 class="collapse-button">Operating system</h2>
<div class="collapsable">
<?php echo php_uname() ?>
</div>

<h2 class="collapse-button">Loaded extensions</h2>
<div class="collapsable">
<?php 
foreach (array_values(get_loaded_extensions()) as $value) {
    echo $value.', ';
}
?>
</div>

<h2 class="collapse-button">Included files</h2>
<div class="collapsable">
<?php 
foreach (get_included_files() as $value) {
    echo $value.'<br/>';
}
?>
</div>

<h2 class="collapse-button">Configuration options</h2>
<div class="collapsable">
ini_get_all<br/>&nbsp;<br/>
<table>
<?php 
foreach(ini_get_all() as $key=>$value) {
    echo '<tr><td>'.$key.'</td><td>';
    foreach($value as $v) {
        echo $v.', ';
    }
    echo '</td></tr>';
}
?>
</table>
</div>

<script>
<?php require_once GZ::SCRIPTS.'/collapse.js' ?>
</script>

<!-- h2>PHP info</h2 -->

<?php 
//echo phpinfo(INFO_ALL);
?>
