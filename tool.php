<?php
/*
*	@version	0.1.0
*	@author		Ruud Eisinga (Ruud)
*	@date		2009-04-10
*/

// Direct access prevention
defined('WB_PATH') OR die(header('Location: ../index.php'));

// Load Language file
if(!file_exists(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/capslider/languages/EN.php');
} else {
	require_once(WB_PATH.'/modules/capslider/languages/'.LANGUAGE.'.php');
}

// check if backend.css file needs to be included into the <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH .'/modules/capslider/backend.css')) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/capslider/backend.css');
	echo "\n</style>\n";
}
	
// A quick check to see if the snippet was unloaded by a module reload or WB upgrade script
$result = $database->query("SELECT * from " . TABLE_PREFIX . "addons where directory='capslider/snippet' and type= 'module'");
if ($result && $result->numRows() == 0) {
    load_module(WB_PATH.'/modules/capslider/snippet');
}	
	
	
// And... action
$admintool_url = ADMIN_URL .'/admintools/index.php';

$btable = TABLE_PREFIX .'mod_capslider_slide';
$gtable = TABLE_PREFIX .'mod_capslider_groups';

function getGroupName ( $group_id ) {
	global $gtable, $database,$WB_TEXT;
	$query_groups = $database->query("SELECT * FROM ".$gtable." where group_id = '$group_id'");
	if ($query_groups && $group = $query_groups->fetchRow()) 
		return $group['group_name'];
	if ($group_id == '0')
		return $WB_TEXT['ANYGROUP'];
	return "<font style='color:red;'>".$WB_TEXT['ERRORGROUP']."</font>";
}

?>
<script type="text/javascript" src="<?php echo WB_URL; ?>/modules/capslider/overlib.js"></script>
<br />
<small><?php echo $WB_HELP['MAINTIP']; ?></small>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td valign="top" width="30%" align="right">
		<button class="add" type="button" name="add_slide" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/capslider/add_slide.php';"><?php echo $WB_TEXT['ADDSLIDE']; ?></button>	
	</td>
</tr>

</table>
<table class="row_a" cellpadding="0" cellspacing="0" border="0" width="100%"><h2><?php echo $WB_TEXT['MODSLIDE']; ?></h2>
<?php
$query_slides = $database->query("SELECT * FROM ".$btable."");
if ($query_slides ) {
	$num_slides = $query_slides->numRows();
	if($num_slides > 0) {
		?>
		<table class="row_a" border="0" cellspacing="0" cellpadding="3" width="100%">
		<tr>
			<thead>
				<td ><?php echo $WB_TEXT['SLIDE']; ?></td>
				<td ><?php echo $WB_TEXT['GROUP']; ?></td>
				<td width="30"><?php echo $WB_TEXT['ACTIVE']; ?></td>
					<td width="20"></td>
					
				</thead>
		</tr>
		<?php
		while($slide = $query_slides->fetchRow()) {
			$warning = "";
			if ($slide['image'] == "") $warning .= "<font size=1 color=red> [NO image!!]</font>";
			$comments = str_replace(array("\r\n", "\n", "\r"), '<br />', $slide['comments']);
			$om = ' onmouseover="overlib(\'<img src=\\\''.WB_URL.$slide['image'].'\\\'>\',WIDTH,10,VAUTO,HAUTO)" onmouseout="nd()"';
			?>
					<tr class="row_om_out" onMouseOver="className='row_om_over'" onMouseOut="className='row_om_out'">
						<td>
							<a href="<?php echo WB_URL; ?>/modules/capslider/modify_slide.php?slide_id=<?php echo $slide['id']?>" title="<?php echo $WB_TEXT['MODSLIDE']; ?>" <?php echo $om ?>>
								<?php echo $warning; ?>
								<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="Modify" /> 
								<img src="<?php echo WB_URL.$slide['image']?>" style="height:30px;"/>
							</a>
						</td>
						<td>
							<?php echo getGroupName($slide['group_id']); ?>
						</td>
						<td>
							<b><?php if($slide['active'] == 1){ echo '<span style="color: green;">'. $WB_TEXT['YES']. '</span>'; } else { echo '<span style="color: red;">'.$WB_TEXT['NO'].'</span>';  } ?></b>
						</td>
						<td>
							<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/capslider/delete_slide.php?slide_id=<?php echo $slide['id']?>');" title="<?php echo $WB_TEXT['DELSLIDE']; ?>">
								<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
							</a>
						</td>
				</tr>
		<?php
		}
		?>
		</table>
<?php } ?>	
<?php } else { ?>	
	<tr><td>No slides found!!</td></tr>
<?php } ?>	


<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td valign="top" width="30%" align="right">
		<button class="add" type="button" name="add_group" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/capslider/add_group.php';"><?php echo $WB_TEXT['ADDGROUP']; ?></button>	
	</td></tr>
</table>
<table class="row_a" cellpadding="0" cellspacing="0" border="0" width="100%"><h2><?php echo $WB_TEXT['MODGROUP']; ?></h2>
<?php 
$query_groups = $database->query("SELECT * FROM ".$gtable."");
if ($query_groups ) {
	$num_groups = $query_groups->numRows();
	if($num_groups > 0) {
		?>
		<table class="row_a" border="0" cellspacing="0" cellpadding="3" width="100%">
		<tr>
			<thead>
				<td width="20"></td>
				<td width="20"><?php echo $WB_TEXT['GROUPID']; ?></td>
				<td ><?php echo $WB_TEXT['GROUP']; ?></td>
				<td width="50"><?php echo $WB_TEXT['HW']; ?></td>
				<td width="50"><?php echo $WB_TEXT['DELAY']; ?></td>
				<td width="50"><?php echo $WB_TEXT['SPEED']; ?></td>
				<td width="20"></td>
			</thead>
		</tr>
		<?php
		while($group = $query_groups->fetchRow()) {
		?>
		<tr class="row_om_out" onMouseOver="className='row_om_over'" onMouseOut="className='row_om_out'">
			<td width="20">
				<a href="<?php echo WB_URL; ?>/modules/capslider/modify_group.php?group_id=<?php echo $group['group_id']?>" title="<?php echo $WB_TEXT['MODGROUP']; ?>">
				<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="Modify" /> 
				</a>
			</td>
			<td>
				<?php echo $group['group_id']; ?>
			</td>
			<td width="140">
				<a href="<?php echo WB_URL; ?>/modules/capslider/modify_group.php?group_id=<?php echo $group['group_id']?>">
				<?php echo $group['group_name'];?>
				</a>
			</td>
			<td>
				<?php echo $group['height'];?>x<?php echo $group['width'];?>
			</td>
			<td>
				<?php echo $group['delay'];?>ms
			</td>
			<td>
				<?php echo $group['speed'];?>ms
			</td>
			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/capslider/delete_group.php?group_id=<?php echo $group['group_id']?>');" title="<?php echo $WB_TEXT['DELGROUP']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		<?php
		}
	}
} ?>
</table>
