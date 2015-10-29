<?php
/*
*	@version	0.1.0
*	@author		Ruud Eisinga (Ruud)
*	@date		2009-04-10
*/

require('../../config.php');

require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');
$admin = new admin('admintools','admintools',false,false);
if($admin->get_permission('admintools') == true) {
	
	$admintool_link = ADMIN_URL .'/admintools/index.php';
	$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=capslider';
	$admin = new admin('admintools', 'admintools');

	$gtable = TABLE_PREFIX .'mod_capslider_groups';
	$database->query("INSERT INTO ".$gtable." (group_name) VALUES ('' )");

	// Get the id
	$group_id = $database->get_one("SELECT LAST_INSERT_ID()");

	// Say that a new record has been added, then redirect to modify page
	if($database->is_error()) {
		$admin->print_error($database->get_error(), $module_edit_link);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/capslider/modify_group.php?group_id='.$group_id);
	}

	// Print admin footer
	$admin->print_footer();
} else {
	die(header('Location: ../../index.php'));
} 
?>