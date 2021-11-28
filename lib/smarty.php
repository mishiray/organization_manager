<?php
global $libraries, $site, $templateFolder;
$requiredFrom = basename($_SERVER['SCRIPT_FILENAME']);
require_once ("Smarty/Smarty.class.php");
$smarty = new Smarty;
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';
$smarty->setTemplateDir("./$site/templates/$templateFolder/")->setCompileDir('./cache/smarty/templates/')->setCacheDir('./cache/smarty/')->addPluginsDir("$libraries/smarty/plugins/");
