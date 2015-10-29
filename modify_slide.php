<?php
/*
*	@version	0.2
*	@author		Ruud Eisinga (Ruud)
*	@date		2009-01-02
*	@state		@beta
*
*	droplets are small codeblocks that are called from anywhere in the template. 
* 	To call a droplet just use [[dropletname]]. optional parameters for a droplet can be used like [[dropletname?parameter=value&parameter2=value]]
*/

require('../../config.php');

// Get id
if(!isset($_GET['slide_id']) OR !is_numeric($_GET['slide_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$slide_id = $_GET['slide_id'];
}

require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" rows="10" cols="1" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("caption");
			require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=capslider';
$admin = new admin('admintools', 'admintools');

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/capslider/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/capslider/backend.css');
	echo "n</style>n";
}

// Load Language file
if(!file_exists(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/capslider/languages/EN.php');
} else {
	require_once(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php');
}


$modified_when = time();
$modified_by = $admin->get_user_id();
	
$btable = TABLE_PREFIX .'mod_capslider_slide';

// Get header and footer
$query_content = $database->query("SELECT * FROM ".$btable." WHERE id = '$slide_id'");
$fetch_content = $query_content->fetchRow();

function p($text) {
	global $WB_HELP;
	echo 'onfocus="overlib(\''.$WB_HELP[$text].'\',CAPTION,\'Help\',BORDER,2, WIDTH,300,RELY, -20, FOLLOWMOUSE, RELX,-30)" onblur="nd()"';
}

?>
<script type="text/javascript" src="<?php echo WB_URL; ?>/modules/capslider/overlib.js"></script>
<h4 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
	<a href="<?php echo $admintool_link;?>"><?php echo $HEADING['ADMINISTRATION_TOOLS']; ?></a>
	->
	<a href="<?php echo $module_edit_link;?>">Caption Slider</a>
</h4>
<br />
<form enctype="multipart/form-data" name="modify" action="<?php echo WB_URL; ?>/modules/capslider/save_slide.php" method="post" style="margin: 0;">
<input type="hidden" name="slide_id" value="<?php echo $slide_id; ?>">

<table class="row_a" cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr>
		<td colspan="2"><strong>capslider Edit</strong></td>
	</tr>
	<tr>
		<td class="setting_name" width="150px">
			<?php echo $WB_TEXT['ACTIVE']; ?>:
		</td>
		<td>	
			<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;">
			<label><?php echo $WB_TEXT['YES']; ?></label>
			</a>
			<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;">
			<label><?php echo $WB_TEXT['NO']; ?></label>
			</a>
		</td>
	</tr>

	<tr>
		<td valign="top" class="setting_name"><?php echo $WB_TEXT['GROUP']; ?>:</td>
		<td>
		<select <?php p('GROUP') ?> name="group" style="width:300px;">
			<?php 
				$gtable = TABLE_PREFIX .'mod_capslider_groups';
				$query_groups = $database->query("SELECT * FROM ".$gtable."");
				if ($query_groups) {
					while($group = $query_groups->fetchRow()) {
						echo "<option value=\"".$group['group_id']."\"";
						if ($fetch_content['group_id'] == $group['group_id']) echo " selected";
						echo ">".$group['group_name']."</option>"; 
					}
				}
			?>
		</select>
		</td>
	</tr>	
	
	<tr>
		<td valign="top" class="setting_name"><?php echo $WB_TEXT['IMAGE']; ?>:</td>
	<?php if($fetch_content['image'] !== "" && file_exists(WB_PATH.$fetch_content['image'])) { ?>
		<td><input type="hidden" name="image" value="<?php echo htmlspecialchars($fetch_content['image']); ?>"/>
		<img style="padding: 5px; border: 1px solid #7f7f7f;" src="<?php echo WB_URL.$fetch_content['image']; ?>">
		&nbsp;
		<input type="checkbox" name="delete_image" id="delete_image" value="true" /><label for="delete_image"><?php echo $WB_TEXT['DELIMAGE']; ?>?</label></td>
	<?php } else { ?>
		<td><input size="50" type="file" name="newimage" /></td>
	<?php } ?>
	</tr>
	
	<tr>
		<td valign="top" class="setting_name"><?php echo $WB_TEXT['ALT']; ?> :</td>
		<td>
			<input <?php p('ALT') ?> type="text" name="alt" value="<?php echo htmlspecialchars($fetch_content['alt']); ?>" style="width: 600px;" />
		</td>
	</tr>

	
	<tr>
		<td valign="top" class="setting_name"><?php echo $WB_TEXT['WIDTH']; ?> x <?php echo $WB_TEXT['HEIGHT']; ?>:</td>
		<td>
			<input <?php p('SIZE') ?> type="text" name="width" value="<?php echo htmlspecialchars($fetch_content['width']); ?>" style="width: 30px;" />x
			<input <?php p('SIZE') ?> type="text" name="height" value="<?php echo htmlspecialchars($fetch_content['height']); ?>" style="width: 30px;" />
		</td>
	</tr>
	<tr>
		<td colspan="2">					
		</td>
	</tr>
	<tr>
		<td valign="top" class="setting_name"><?php echo $WB_TEXT['COMMENT']; ?>:</td>
		<td>
		<?php
			show_wysiwyg_editor("comments","comments",htmlspecialchars($fetch_content['comments']),"100%","400px");
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;					
		</td>
	</tr>
</table>
<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<button  class="save" name="save" type="submit"><?php echo $WB_TEXT['SAVE']; ?></button>
			</form>
		</td>
		<td align="right">
			<button class="cancel" type="button" onclick="javascript: window.location = '<?php echo $module_edit_link; ?>';"><?php echo $WB_TEXT['CANCEL']; ?></button>
		</td>
	</tr>
</table>

<?php

// Print admin footer
$admin->print_footer();

?>