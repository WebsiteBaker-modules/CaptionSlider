<?php
/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
require('../../config.php');
require(WB_PATH.'/modules/admin.php');

require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=capslider';
$admin = new admin('admintools', 'admintools');

if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/capslider/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/capslider/backend.css');
	echo "n</style>n";
}

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/capslider/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php');
	}
}


$gtable = TABLE_PREFIX .'mod_capslider_groups';
$query = "SELECT * FROM ".$gtable." WHERE group_id = '$group'";
$get_groups = $database->query($query);


$olist = "<select onchange=\"window.location='pagetypes.php?page_id=$page_id&pagetype='+this.value\" style=\"width:100%;\" name=\"pagetype\">";
while ($types = $get_types->fetchRow()) {
	$selected = ($pagetype == $types['page_type'] ? " selected":"");
	$olist .= "<option value=\"".$types['page_type']."\"".$selected.">".$types['name']."</option>";
}
$olist .= "</select>";



?>

<form enctype="multipart/form-data" name="groupsform" action="<?php echo $_SERVER['php_self'] ?>" method="post">

<table cellpadding="2" cellspacing="0" border="0" width="99%" style="border:1px solid #7f7f7f; padding: 3px;">
<tr style="background:#F0F0F0"><td><H2><?php echo $MPP['SELTYPE']; ?></H2></td><td align="right">
<input type="button" name="newpagetype" value="<?php echo $MPP['ADDTYPE']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/multipage/addpagetype.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&pagetype=<?php echo $pagetype; ?>';">
</td></tr>
<tr><td width="150" nowrap="true"><?php echo $MPP['PAGETYPE']; ?>:</td><td><?php echo $olist; ?>
</td></tr>
<tr><td></td><td align="right">
<input type="button" name="delpagetype" value="<?php echo $MPP['DELTYPE']; ?>" onclick="javascript: if(confirm('<?php echo $MPP['WARNDEL']; ?>')) window.location = '<?php echo WB_URL; ?>/modules/multipage/delpagetype.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&pagetype=<?php echo $pagetype; ?>';">
</td></tr>
</table>
<br/>
<table cellpadding="2" cellspacing="0" border="0" width="99%" style="border:1px solid #7f7f7f; padding: 3px;">
<tr style="background:#F0F0F0"><td colspan="2"><H2><?php echo $MPP['EDITTYPE']; ?></H2></td></tr>
<tr><td><b><?php echo $MPP['NAME']; ?></b></td><td><input size="80" type="text" name="name" value="<?php echo $name ?>"></td></tr>
<tr><td><b><?php echo $MPP['FIELDS']; ?></b></td><td><input size="80" type="text" name="fields" value="<?php echo $fields ?>"></td></tr>
<tr style="background:#F0F0F0"><td colspan="2"><h2><?php echo $MPP['TEMPLATE']; ?></h2></td></tr>
<tr><td colspan="2">
	<textarea id ="htmledit" name="template" style="width: 100%; height: 300px;"><?php echo $template; ?></textarea>
</td></tr>
<tr style="background:#F0F0F0"><td colspan="2"><h2><?php echo $MPP['CSS']; ?></h2></td></tr>
<tr><td colspan="2">
	<textarea id ="cssedit"  name="css" style="width: 100%; height: 300px;"><?php echo $css; ?></textarea>

</td></tr>

</table>
<br />

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-bottom: 3px;">
<tr>
	<td align="left">
		<input type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
	</td>
	<td align="right">
		</form>
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/admin/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

</form>