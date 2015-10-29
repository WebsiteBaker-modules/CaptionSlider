<?php
/*
*	@version	0.1
*	@author		Ruud Eisinga (Ruud)
*	@date		2009-04-10
*
*/

if(!defined('WB_PATH')) die(header('Location: ../index.php'));

$table = TABLE_PREFIX .'mod_capslider_slide';
$database->query("DROP TABLE IF EXISTS `$table`");
$database->query("CREATE TABLE `$table` (
	`id` INT NOT NULL auto_increment,
	`group_id` INT NOT NULL DEFAULT '0',
	`image` TEXT NOT NULL ,
	`alt` TEXT NOT NULL ,
	`height` INT NOT NULL DEFAULT '0',
	`width` INT NOT NULL DEFAULT '0',
	`modified_when` INT NOT NULL DEFAULT '0',
	`modified_by` INT NOT NULL DEFAULT '0',
	`active` INT NOT NULL DEFAULT '0',
	`comments` TEXT NOT NULL,
	PRIMARY KEY ( `id` )
	)"
);


$table = TABLE_PREFIX .'mod_capslider_groups';
$database->query("DROP TABLE IF EXISTS `$table`");
$database->query("CREATE TABLE `$table` (
	`group_id` INT NOT NULL auto_increment,
	`group_name` VARCHAR(32) NOT NULL DEFAULT '',
	`height` INT NOT NULL DEFAULT '0',
	`width` INT NOT NULL DEFAULT '0',
	`delay` INT NOT NULL DEFAULT '0',
	`speed` INT NOT NULL DEFAULT '0',
	`panel` VARCHAR(6) NOT NULL DEFAULT '000000',
	PRIMARY KEY ( `group_id` )
	)"
);
$database->query("INSERT INTO ".$table." (`group_name`,`height`,`width`,`delay`,`speed`) VALUES ('capslider','200','600','5000','800' )");

load_module(WB_PATH.'/modules/capslider/snippet');

?>