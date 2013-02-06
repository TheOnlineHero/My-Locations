function init() {
	tinyMCEPopup.resizeToInnerSize();
}

jQuery(function() {
	jQuery("#add_another_my_location").live("click", function() {
		jQuery('.clone:first').clone().appendTo('#locations');
		jQuery("#locations .clone:last").removeClass("clone").addClass("location");
		jQuery("#last_my_location").removeAttr("id");
		jQuery(".location:last").attr("id", "last_my_location");
		return false;
	});
	jQuery("#insert_my_location").live("click", function() {
		jQuery("span.error").remove();
		var valid_form = true;
		jQuery("form#my_locations input.required:visible").each(function() {
			if (jQuery(this).val() == "") {
				valid_form = false;
				jQuery(this).parent().append("<span class='error'>cannot be blank.</span> ")
			}
		});

		jQuery("form#my_locations input.dimension:visible").each(function() {
			if (!jQuery(this).val().replace("px", "").match("^[0-9]*$")) {
				valid_form = false;
				jQuery(this).parent().append("<span class='error'>must be valid dimension.</span> ");
			}
		});

		lat_reg = /^(-?[1-8]?\d(?:\.\d{1,18})?|90(?:\.0{1,18})?)$/;
		lng_reg = /^(-?(?:1[0-7]|[1-9])?\d(?:\.\d{1,18})?|180(?:\.0{1,18})?)$/;

		jQuery("form#my_locations input.lat:visible").each(function() {
			if (!jQuery(this).val().match(lat_reg)) {
				valid_form = false;
				jQuery(this).parent().append("<span class='error'>must be valid latitude.</span> ");
			}
		});
		jQuery("form#my_locations input.lng:visible").each(function() {
			if (!jQuery(this).val().match(lng_reg)) {
				valid_form = false;
				jQuery(this).parent().append("<span class='error'>must be valid longitude.</span> ");
			}
		});

		url_reg = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/;
		jQuery("form#my_locations input.url:visible").each(function() {
			if (jQuery(this).val() != "" && !jQuery(this).val().match(url_reg)) {
				valid_form = false;
				jQuery(this).parent().append("<span class='error'>must be valid url.</span> ");
			}
		});

		image_url_reg = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)+\.(?:jpe?g|gif|png)$/;
		jQuery("form#my_locations input.image-url:visible").each(function() {
			if (jQuery(this).val() != "" && !jQuery(this).val().match(image_url_reg)) {
				valid_form = false;
				jQuery(this).parent().append("<span class='error'>must be valid image url.</span> ");
			}
		});

		if (valid_form == true) {
			insertMyLocation();
		}
		return false;
	});
	
});

function insertMyLocation() {
	tagtext = "";
	tagtext = "[my_map name='" + jQuery("#map_name").val() + "' width='" + jQuery("#width").val() + "' height='" + jQuery("#height").val() + "']";
	var index = 0;
	jQuery("#locations .location").each(function(i) {
		index = parseInt(i + 1);
		tagtext += "[my_location ";
		tagtext += "lat='" + jQuery("input[name=lat]")[index].value + "' ";
		tagtext += "lng='" + jQuery("input[name=lng]")[index].value + "' ";
		tagtext += "title='" + jQuery("input[name=title]")[index].value + "' ";
		tagtext += "location='" + jQuery("input[name=location]")[index].value + "' ";
		tagtext += "phone='" + jQuery("input[name=phone]")[index].value + "' ";
		tagtext += "website='" + jQuery("input[name=website]")[index].value + "' ";
		tagtext += "image='" + jQuery("input[name=image]")[index].value + "' ";
		tagtext += "][/my_location]";
	});

	tagtext += "[/my_map]";

	if(window.tinyMCE) {
    window.tinyMCE.execInstanceCommand(window.tinyMCE.activeEditor.id, 'mceInsertContent', false, tagtext);
		//Peforms a clean up of the current editor HTML. 
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches. 
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}