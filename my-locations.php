<?php
/*
Plugin Name: My Locations
Plugin URI: http://wordpress.org/extend/plugins/my-locations/
Description: Adds my location pins to a Google Map.

Installation:

1) Install WordPress 3.4.2 or higher

2) Download the following file:

http://downloads.wordpress.org/plugin/my-locations.zip

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 1.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

require_once("my-location-path.php");

function my_locations_activate() {
}
register_activation_hook( __FILE__, 'my_locations_activate' );

add_action('admin_menu', 'register_my_locations_page');

function register_my_locations_page() {
   add_menu_page('My Locations', 'My Locations', 'manage_options', 'my-locations/my-locations.php', 'my_locations_settings_page');
}

function my_locations_settings_page() {
?>
  <div class="wrap">
  <h2>My Locations</h2>

<div class="postbox " style="display: block; ">
<div class="inside">
    <h3>Short Codes</h3>
    <h4>my_map</h4>
    <table class="data">
      <thead>
        <tr>
          <th>Attributes</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody> 
        <tr>
          <td>name</td>
          <td>Name of the map. Must be unique on the page.</td>
        </tr>
        <tr>
          <td>width</td>
          <td>The width of the google map.</td>
        </tr>
        <tr>
          <td>height</td>
          <td>Height of the google map.</td>
        </tr>
      </tbody>
    </table>

    <h4>my_location</h4>
    <table class="data">
      <thead>
        <tr>
          <th>Attributes</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody> 
        <tr>
          <td>lat</td>
          <td>Latitude of the location.</td>
        </tr>
        <tr>
          <td>lng</td>
          <td>Longitude of the location.</td>
        </tr>
        <tr>
          <td>title</td>
          <td>Title of the location.</td>
        </tr>
        <tr>
          <td>location</td>
          <td>The physical address of the location.</td>
        </tr>
        <tr>
          <td>phone</td>
          <td>Phone number to be displayed for the location.</td>
        </tr>
        <tr>
          <td>website</td>
          <td>The url of a website to be displayed for the location. Example: http://www.google.com.au.</td>
        </tr>
        <tr>
          <td>image</td>
          <td>The image url of the location. Example: http://www.google.com.au/logo.png</td>
        </tr>
        <tr>
          <td>location_id</td>
          <td>Unique id for the location. It can be any id, just make sure its unique.</td>
        </tr>
      </tbody>
    </table>

    <h4>Examples</h4>
    <pre>
      [my_map name="map_canvas" width="482px" height="415px"]
        [my_location lat="-32.340512" lng="115.819075" title="The Diva" location="12 Makybe Drive Baldivis WA 6171" phone="1800 214 864" website="{{site_url}}/on-display/diva/" image="{{site_url}}/wp-content/uploads/2012/10/46_Diva_thumb_map.jpg" location_id="1" ][/my_location]
        [my_location lat="-32.106452" lng="115.764007" title="The Horizon" location="20 Orsino Blvd North Coogee WA 6163" phone="1800 214 864" website="{{site_url}}/on-display/horizon/" image="{{site_url}}/wp-content/uploads/2012/10/79_horizon_thumb_map.jpg" location_id="4" ][/my_location]
      [/my_map]

      [my_map name="map_canvas_2" width="482px" height="415px" ]
        [my_location lat="-32.106452" lng="115.764007" title="The Horizon" location="20 Orsino Blvd North Coogee WA 6163" phone="1800 214 864" website="{{site_url}}/on-display/horizon/" image="{{site_url}}/wp-content/uploads/2012/10/79_horizon_thumb_map.jpg" location_id="2" ][/my_location]
      [/my_map]
    </pre>
</div>
</div>

  <div class="postbox " style="display: block; ">
  <div class="inside">
    <h3>CSS</h3>
    <form action="" method="post">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <td>
            <?php
              $file = fopen(plugins_url('css/my-locations.css', __FILE__), "r") or exit("Unable to open file!");
              //Output a line of the file until the end is reached
              $content = "";
              while(!feof($file))
              {
              $content .= fgets($file);
              }
              fclose($file);
            ?>
            <textarea rows="10" cols="60" name="css_content" id="css_content"><?php echo($content); ?></textarea>
          </td>
        </tr>
        <tr>
          <td><input type="submit" value="Update Css" /></td>
        </tr>
      </tbody>
    </table>
    </form>
  </div>
  </div>
</div>
  <?php
}

add_action( 'admin_init', 'register_my_locations_settings' );
function register_my_locations_settings() {
  if ($_POST["css_content"] != "") {
    $location = LocationPath::normalize(dirname(__FILE__).'/css/my-locations.css');
    $location = preg_replace('/^\//', '', $location); 

    $file = fopen($location, "w") or exit("Unable to open file!");
    $content = str_replace('\"', "\"", $_POST["css_content"]);
    $content = str_replace("\'", '\'', $content);
    $stringData = $content;
    fwrite($file, $stringData);
    fclose($file);    
  }
}

add_shortcode( 'my_map', 'my_location_map_shortcode' );
function my_location_map_shortcode($atts, $content) {
  $name = $atts["name"];
  $width = $atts["width"];
  if (!isset($atts["width"]) || $atts["width"] == "") {
    $width = "682px";
  }
  $height = $atts["height"];
  if (!isset($atts["height"]) || $atts["height"] == "") {
    $height = "415px";
  }

  echo("<div id='$name' style='width: $width; height: $height;' class='google-map'></div>");
  echo("<input type='hidden' name='gmap_name' value='$name' />");
  $content = str_replace("[my_location ", "[my_location map_name='$name' ", $content);
  echo("<ul class='locations' map='".$name."'>");
  do_shortcode($content);
  echo("</ul><div class='clear'></div>");
}

add_shortcode( 'my_location', 'my_location_location_shortcode' );

function my_location_location_shortcode($atts) {
  $gmap_name = $atts["map_name"];
  $gmap_lat = $atts["lat"];
  $gmap_lng = $atts["lng"];
  $gmap_title = $atts["title"];
  $gmap_location = $atts["location"];
  $gmap_phone = $atts["phone"];
  $gmap_website = $atts["website"];
  $gmap_image = $atts["image"];
  $gmap_location_id = $atts["location_id"];

  echo("
    <li id='".$gmap_location_id."'>
    <input type='hidden' name='" . $gmap_name . "_gmap_lat' value='$gmap_lat' />
    <input type='hidden' name='" . $gmap_name . "_gmap_lng' value='$gmap_lng' />
    <input type='hidden' name='" . $gmap_name . "_gmap_title' value='$gmap_title' />
    <input type='hidden' name='" . $gmap_name . "_gmap_location' value='$gmap_location' />
    <input type='hidden' name='" . $gmap_name . "_gmap_phone' value='$gmap_phone' />
    <input type='hidden' name='" . $gmap_name . "_gmap_website' value='$gmap_website' />
    <input type='hidden' name='" . $gmap_name . "_gmap_image' value='$gmap_image' />
    <input type='hidden' name='" . $gmap_name . "_gmap_location_id' value='$gmap_location_id' />
    ".$gmap_title."
    </li>
  ");
}

add_action('wp_head', 'add_my_locations_js_and_css');
function add_my_locations_js_and_css() { 
  wp_enqueue_script('jquery');
  echo("<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js' type='text/javascript'></script>");

  echo("<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js' type='text/javascript'></script>");
  echo("<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>");

  wp_register_style( 'my-locations-style', plugins_url('css/my-locations.css', __FILE__) );
  wp_enqueue_style('my-locations-style'); 

  ?>
  <script language="javascript">
    jQuery(document).ready(function() {
      initialize_gmaps();
    });
    var map = new Array();
    var geocoder = new google.maps.Geocoder;
    var infoWindow = new google.maps.InfoWindow;
    // Index DOM elements to their marker.
    var gmap_markers = {};
    var old_where_id = null;
    var new_where_id = null;
    jQuery("ul.locations li").live('click', function() {
      new_where_id = jQuery(this).attr("id");
      if (new_where_id != old_where_id) {
        map[jQuery(this).parent().attr("map")].setZoom(17);
        map[jQuery(this).parent().attr("map")].setCenter(gmap_markers[jQuery(this).attr("id")].getPosition());
        map[jQuery(this).parent().attr("map")].panTo(gmap_markers[jQuery(this).attr("id")].getPosition());
        markerClick(gmap_markers[jQuery(this).attr("id")]);
        old_where_id = new_where_id;
      }
    });

    markerClick = function(marker_location) {
      // Ensure that hover will position over previous marker when something else is clicked.
      old_where_id = null;
      var markerLatLng = marker_location.getPosition();
      var title = "";
      if (marker_location.website != "") {
        title = "<span class='title'><a href='" + marker_location.website + "'>" + marker_location.title + "</a></span>";
      } else {
        title = "<span class='title'>" + marker_location.title + "</span>";
      }
      var content = "<div id='pop_up'>" + title;
      if (marker_location.image_url != "") {
        content += "<a href='" + marker_location.website + "'><img class='image' src='" + marker_location.image_url + "' /></a>";
      }
      content += "<span class='wrapper'>";
      if (marker_location.location != "") {
        content += "<span class='address'>" + marker_location.location + "</span>";
      }
      content += "<span><a href='" + marker_location.website + "'>Find out more</a></span>";
      content += "</span>";
      content += "<br /></div>";
      infoWindow.setContent(content);
      infoWindow.setPosition(markerLatLng);
      infoWindow.open(map[marker_location.map_id]);
    };
    function initialize_gmaps() {

      for (var map_i=0;map_i<jQuery("input[name=gmap_name]").length;map_i++) {
        var google_map_name = jQuery("input[name=gmap_name]")[map_i].value;
        var zoom_level = 15;
        if(jQuery("input[name="+google_map_name+"_gmap_lat]").length > 0 && jQuery("input[name="+google_map_name+"_gmap_lng]").length > 0 && jQuery("input[name="+google_map_name+"_gmap_location]").length > 0) {
          var latlng = new google.maps.LatLng(jQuery("input[name="+google_map_name+"_gmap_lat]:first").val(), jQuery("input[name="+google_map_name+"_gmap_lng]:first").val());
          var myOptions = {
            zoom: zoom_level,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            streetViewControl: false,
            mapTypeControl: false
          };
          map[google_map_name] = new google.maps.Map(document.getElementById(google_map_name), myOptions);
          var bounds = new google.maps.LatLngBounds();
          for (var i=0;i<jQuery("input[name="+google_map_name+"_gmap_lng]").length;i++) {
            var local_latlng = new google.maps.LatLng(jQuery("input[name="+google_map_name+"_gmap_lat]")[i].value, jQuery("input[name="+google_map_name+"_gmap_lng]")[i].value);
            bounds.extend(local_latlng);
            var marker = new google.maps.Marker({
              map_id: google_map_name,
              map: map[google_map_name],
              position: local_latlng,
              title: jQuery("input[name="+google_map_name+"_gmap_title]")[i].value,
              location: jQuery("input[name="+google_map_name+"_gmap_location]")[i].value,
              phone: jQuery("input[name="+google_map_name+"_gmap_phone]")[i].value,
              website: jQuery("input[name="+google_map_name+"_gmap_website]")[i].value,
              image_url: jQuery("input[name="+google_map_name+"_gmap_image]")[i].value
            });

            google.maps.event.addListener(marker, 'click', function() {markerClick(this);});
            if (jQuery("input[name="+google_map_name+"_gmap_location_id]")[i]) {
              gmap_markers[jQuery("input[name="+google_map_name+"_gmap_location_id]")[i].value] = marker;
            }

          }

          var thismap = map[google_map_name];

          google.maps.event.addListener(thismap, 'zoom_changed', function() {
              zoomChangeBoundsListener = 
                  google.maps.event.addListener(thismap, 'bounds_changed', function(event) {
                      if (this.getZoom() > zoom_level && this.initialZoom == true) {
                          // Change max/min zoom here
                          this.setZoom(zoom_level);
                          this.initialZoom = false;
                      }
                  google.maps.event.removeListener(zoomChangeBoundsListener);
              });
          });
          thismap.initialZoom = true;
          thismap.fitBounds(bounds);       
        }


      }

    }
  </script>
  <?php
}