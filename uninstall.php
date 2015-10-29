<?php
/*
*	@version	0.1
*	@author		Ruud Eisinga (Ruud)
*	@date		2009-04-10
*
*/

if(!defined('WB_PATH')) die(header('Location: ../index.php'));

// Uninstall snippet
$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE directory = 'capslider/snippet' AND type = 'module'");
// Snippet uninstalled

$table = TABLE_PREFIX .'mod_capslider_slide';
$database->query("DROP TABLE IF EXISTS `$table`");

$table = TABLE_PREFIX .'mod_capslider_stats';
$database->query("DROP TABLE IF EXISTS `$table`");

$table = TABLE_PREFIX .'mod_capslider_groups';
$database->query("DROP TABLE IF EXISTS `$table`");

?>