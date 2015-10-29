<?php


require('../../config.php');

// Get id
if(!isset($_POST['slide_id']) OR !is_numeric($_POST['slide_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$slide_id = $_POST['slide_id'];
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
include_once('resize_img.php');

// Create Imagedir (/slide is rejected by some add_blockers, use slide inverted
$slide_dir = WB_PATH.MEDIA_DIRECTORY.'/slider/';
make_dir($slide_dir);

// check website baker platform (with WB 2.7, Admin-Tools were moved out of settings dialogue)
$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=capslider';
$admin = new admin('admintools', 'admintools');


// Validate all fields
if($admin->get_post('comments') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/capslider/modify_slide.php?slide_id='.$slide_id);
} else {
	$active = $admin->get_post('active');
	$group = $admin->add_slashes($admin->get_post('group'));	
	$alt = $admin->add_slashes($admin->get_post('alt'));	
	$height = $admin->add_slashes($admin->get_post('height'));	
	$width = $admin->add_slashes($admin->get_post('width'));	
	$image = $admin->add_slashes($admin->get_post('image'));	
	$comments = $admin->add_slashes($admin->get_post('comments'));
	$modified_when = time();
	$modified_by = $admin->get_user_id(); 
	
	// Check if the user uploaded an image or wants to delete one
	if(isset($_FILES['newimage']['tmp_name']) && $_FILES['newimage']['tmp_name'] != '') {
		// Get real filename and set new filename
		$filename = $_FILES['newimage']['name'];
		$path_parts = pathinfo($filename);
		$fileext = strtolower($path_parts['extension']);

		// Make sure the image is a jpg or png file
		if(!($fileext == "jpg" || $fileext == "jpeg" || $fileext == "png" || $fileext == "gif" )) {
			$admin->print_error($MESSAGE['GENERIC']['FILE_TYPES'].' JPG / JPEG / PNG / GIF', ADMIN_URL .'/admintools/tool.php?tool=capslider');
		}
		// Upload image
		move_uploaded_file($_FILES['newimage']['tmp_name'], $slide_dir.$filename);
		change_mode($slide_dir.$filename);
			
		if (file_exists($slide_dir.$filename)) {
			if ($width > 0 || $height > 0) {
				$rimg=new RESIZEIMAGE($slide_dir.$filename);
				$rimg->resize_limitwh($width,$height,$slide_dir.$filename);
				$rimg->close();
			}
		}
		$image = addslashes(MEDIA_DIRECTORY.'/slider/'.$filename);
	}
	if(isset($_POST['delete_image']) AND $_POST['delete_image'] != '') {
		if(file_exists(WB_PATH.$image)) {
			unlink(WB_PATH.$image);
		}
		$image = "";
	}
}
$btable = TABLE_PREFIX .'mod_capslider_slide';

// Update row
$database->query("UPDATE ".$btable." SET 
	group_id = '$group', active = '$active', 
	image = '$image', alt = '$alt', width = '$width', height = '$height',
	comments = '$comments', modified_when = '$modified_when', modified_by = '$modified_by' WHERE id = '$slide_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/capslider/modify_slide.php?slide_id='.$slide_id);
} else {
    $admin->print_success($TEXT['SUCCESS'], $module_edit_link);
}

// Print admin footer
$admin->print_footer();

?>