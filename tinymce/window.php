<?php

if ( !defined('ABSPATH') )
    die('You are not allowed to call this page directly.');

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>My Locations</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/jquery/ui/jquery.ui.core.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/jquery/ui/jquery.ui.widget.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-content/plugins/my-locations/tinymce/tinymce.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo plugins_url("/css/style.css", __FILE__); ?>" media="all" />

  <base target="_self" />
</head>

<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
	<form id="my_locations" action="#">
	<div class="wrapper">
		<p><label for="map_name">Map Name*</label> <input type="text" class="required" name="map_name" id="map_name"></input> <a class="help" title="Unique name of map. Example map_canvas.">?</a></p>
		<p><label for="width">Width*</label> <input type="text" class="required dimension" name="width" id="width"></input> <a class="help" title="Width of map.">?</a></p>
		<p><label for="height">Height*</label> <input type="text" class="required dimension" name="height" id="height"></input> <a class="help" title="Height of map.">?</a></p>

		<div id="locations">
			<div class="clone">
				<?php include("partial_location.php"); ?>
			</div>
			<div class="location" id="last_my_location">
				<?php include("partial_location.php"); ?>
			</div>
		</div>
		<p><a href='#last_my_location' id="add_another_my_location">Add Another Location</a></p>
		<div class="mceActionPanel">
			<div>
				<input type="button" id="cancel_my_location" name="cancel_my_location" value="<?php _e("Cancel", 'my_location'); ?>" onclick="tinyMCEPopup.close();" />
			</div>
			<div>
				<input type="submit" id="insert_my_location" name="insert_my_location" value="<?php _e("Insert", 'my_location'); ?>" />
			</div>
		</div>
	</div>
	</form>
</body>
</html>