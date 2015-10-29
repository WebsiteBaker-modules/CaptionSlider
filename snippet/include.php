<?php
/**
*	@version	0.1.1
*	@author		Ruud Eisinga (Ruud)
*
*/
function showslider ($group = 0, $pagelist = '') {
	global $database, $wb;
	if ($pagelist !== '') {
		if ( !in_array( PAGE_ID , explode(',' , $pagelist) ) )	return;
	}
	
	$gtable = TABLE_PREFIX .'mod_capslider_groups';

	if(!is_numeric($group)) {
		$q = $database->query("SELECT * from ".$gtable." WHERE group_name = '$group'"); 
		if ($q) {
			$record = $q->fetchRow();
			$group = $record['group_id'];
			$height = $record['height'];
			$width = $record['width'];
			$delay = $record['delay'];
			$speed = $record['speed'];
			$panel = $record['panel'];
		}
	}
	if(!is_numeric($group)) {
		return;
	}
	
	$btable = TABLE_PREFIX .'mod_capslider_slide';
	$banner_query = $database->query("SELECT  * FROM ".$btable." WHERE active = 1 AND `image` <> '' AND group_id = '".$group."'");
	$imglist = $captionlist = $firstimg = '';
	$style = ' style="border:0;width:'.$width.'px; height:'.$height.'px;"';
	if ($banner_query && $banner_query->numRows() >0) {
		$slidecount = $banner_query->numRows();
		while ($content = $banner_query->fetchRow()){
			$img = $content['image'];
			$caption = $content['comments'];
			if (!$firstimg) $firstimg = '<img src="'.WB_URL.$img.'" alt=""'.$style.'/><div class="caption" style="color:#fff;position:absolute;padding:10px 13px;width:'.round($width/4).'px;margin-top:-'.$height.'px;height:'.($height-20).'px;background-color:#'.$panel.';filter:alpha(opacity=80);-moz-opacity:0.8;-khtml-opacity:0.8;opacity:0.8">'.$caption.'</div>';
			$imglist .= '<li class="sliderImage" style="display:none"><img src="'.WB_URL.$img.'" alt=""'.$style.'/><div class="caption">'.$caption.'</div></li>';
		}
	} else {
		return;
	}
	$wb->preprocess($imglist);
?>
<script type="text/javascript" >
$(document).ready(function() {

	$('#slider').css({'width':'<?php echo $width ?>px','height':'<?php echo $height ?>px','position': 'relative','overflow': 'hidden'});
	$('#sliderContent').css({'width':'<?php echo $width ?>px','position':'absolute','top':'0','margin':'0','padding':'0'});
	$('.sliderImage').css({'float':'left','position':'relative','display':'none'});
	$('.sliderImage div').css({'color':'#fff','position':'absolute','padding':'10px 13px','width':'<?php echo round($width/4) ?>px','height':'<?php echo $height ?>px','background-color':'#<?php echo $panel?>','filter':'alpha(opacity=80)','-moz-opacity':'0.8','-khtml-opacity':'0.8','opacity':'0.8','display':'none'});
	$('.clearslider').css({'clear': 'both'});
	$('ul#sliderContent').css({ 'list-style-type': 'none'});
	$('.caption').css({'top':'0','left':'0'});
	
	$.fn.slider = function(vars) {       
		var element     = this;
		var timeOut     = (vars.timeOut != undefined) ? vars.timeOut : 5000;
		var fadeTime	= (vars.fadeTime != undefined) ? vars.fadeTime : 1000;
		var items       = $("#" + element[0].id + "Content ." + element[0].id + "Image");
		var caption   	= $("#" + element[0].id + "Content ." + element[0].id + "Image div");
		var activeSlide = 0;
		$(items[activeSlide]).fadeIn(150, function() {
			$(caption[activeSlide]).slideDown(fadeTime);
			setTimeout (doSlide, timeOut);
		});
		var doSlide = function() {
			$(caption[activeSlide]).slideUp(fadeTime, function() {
				$(items[activeSlide]).fadeOut(100, function() {
					if ((++activeSlide) == (items.length)) { activeSlide = 0;	}
					$(items[activeSlide]).fadeIn(fadeTime/4, function() {
						$(caption[activeSlide]).slideDown(fadeTime);
					});
				});
			});
			setTimeout(doSlide, timeOut);
		}
	}; 
	$('#slider').slider({
		timeOut: <?php echo $delay ?>,
		fadeTime: <?php echo $speed ?>
	});
});
</script>
<div id="slider" style="width:<?php echo $width ?>px;height:<?php echo $height ?>px;overflow:hidden">
	<noscript><?php echo $firstimg ?></noscript>
    <ul id="sliderContent">
	<?php echo $imglist; ?>
	</ul>
</div>
<?php } ?>
