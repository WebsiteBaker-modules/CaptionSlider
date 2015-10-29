<?php
/*
*	@version	0.1
*	@author		Ruud Eisinga (Ruud)
*	@date		2009-04-10
*/

require('../../config.php');

// Get id
if(!isset($_GET['slide_id']) OR !is_numeric($_GET['slide_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$slide_id = $_GET['slide_id'];
}

// Include WB admin wrapper script
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=capslider';
$admin = new admin('admintools', 'admintools');


$btable = TABLE_PREFIX .'mod_capslider_slide';
$database->query("DELETE FROM ".$btable." WHERE id = '$slide_id' LIMIT 1");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/capslider/modify_slide.php?slide_id='.$slide_id);
} else {
    $admin->print_success($TEXT['SUCCESS'], $module_edit_link);
}

// Print admin footer
$admin->print_footer();

?>