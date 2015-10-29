<?php


require('../../config.php');

// Get id
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_POST['group_id'];
}
// Load Language file
if(!file_exists(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/capslider/languages/EN.php');
} else {
	require_once(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php');
}

// Include WB admin wrapper script
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

// check website baker platform (with WB 2.7, Admin-Tools were moved out of settings dialogue)
$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=capslider';
$admin = new admin('admintools', 'admintools');


// Validate all fields
if($admin->get_post('title') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/capslider/modify_group.php?group_id='.$group_id);
} else {
	$title = $admin->add_slashes($admin->get_post('title'));
	$height = $admin->add_slashes($admin->get_post('height'));
	$width = $admin->add_slashes($admin->get_post('width'));
	$speed = $admin->add_slashes($admin->get_post('speed'));
	$delay = $admin->add_slashes($admin->get_post('delay'));
	$panel = $admin->add_slashes($admin->get_post('panel'));
}

$gtable = TABLE_PREFIX .'mod_capslider_groups';

// Update row
$database->query("UPDATE ".$gtable." SET `group_name` = '$title',`height` = '$height' ,`width` = '$width',`speed` = '$speed',`delay` = '$delay',`panel` = '$panel' WHERE group_id = '$group_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/capslider/modify_group.php?group_id='.$group_id);
} else {
    $admin->print_success($TEXT['SUCCESS'], $module_edit_link);
}

// Print admin footer
$admin->print_footer();

?>