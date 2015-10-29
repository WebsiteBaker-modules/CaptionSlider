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
if(!isset($_GET['group_id']) OR !is_numeric($_GET['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_GET['group_id'];
}

require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

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

$gtable = TABLE_PREFIX .'mod_capslider_groups';

// Get header and footer
$query_content = $database->query("SELECT * FROM ".$gtable." WHERE group_id = '$group_id'");
$fetch_content = $query_content->fetchRow();

?>
<h4 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
	<a href="<?php echo $admintool_link;?>"><?php echo $HEADING['ADMINISTRATION_TOOLS']; ?></a>
	->
	<a href="<?php echo $module_edit_link;?>">Caption Slider</a>
</h4>
<br />
<form name="modify" action="<?php echo WB_URL; ?>/modules/capslider/save_group.php" method="post" style="margin: 0;">
<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">

<table class="row_a" cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2"><strong><?php echo $WB_TEXT['MODGROUP'] ?></strong></td>
		</tr>
	<tr>
		<td style="width:200px" >
			<?php echo $WB_TEXT['GROUP']; ?>:
		</td>
		<td>
			<input type="text" name="title" value="<?php echo htmlspecialchars($fetch_content['group_name']); ?>" style="width: 38%;" maxlength="32" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $WB_TEXT['HEIGHT']; ?>:
		</td>
		<td>
			<input type="text" name="height" value="<?php echo htmlspecialchars($fetch_content['height']); ?>" style="width: 38%;" maxlength="32" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $WB_TEXT['WIDTH']; ?>:
		</td>
		<td>
			<input type="text" name="width" value="<?php echo htmlspecialchars($fetch_content['width']); ?>" style="width: 38%;" maxlength="32" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $WB_TEXT['DELAY']; ?>:
		</td>
		<td>
			<input type="text" name="delay" value="<?php echo htmlspecialchars($fetch_content['delay']); ?>" style="width: 38%;" maxlength="32" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $WB_TEXT['SPEED']; ?>:
		</td>
		<td>
			<input type="text" name="speed" value="<?php echo htmlspecialchars($fetch_content['speed']); ?>" style="width: 38%;" maxlength="32" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $WB_TEXT['PANEL']; ?>:
		</td>
		<td>
			<input type="text" name="panel" value="<?php echo htmlspecialchars($fetch_content['panel']); ?>" style="width: 38%;" maxlength="6" />
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