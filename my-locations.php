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
include_once (dirname (__FILE__) . '/tinymce/tinymce.php');   


function my_locations_activate() {
}
register_activation_hook( __FILE__, 'my_locations_activate' );

add_action('admin_menu', 'register_my_locations_page');

function register_my_locations_page() {
   add_menu_page('My Locations', 'My Locations', 'manage_options', 'my-locations/my-locations.php', 'my_locations_settings_page', plugins_url('/tinymce/images/google_maps_icon.png', __FILE__));
}


add_action('wp_ajax_my_locations_tinymce', 'my_locations_ajax_tinymce');
/**
 * Call TinyMCE window content via admin-ajax
 * 
 * @since 1.7.0 
 * @return html content
 */
function my_locations_ajax_tinymce() {

    // check for rights
    if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
      die(__("You are not allowed to be here"));
          
    include_once( dirname( dirname(__FILE__) ) . '/my-locations/tinymce/window.php');
    
    die();  
}

function my_locations_settings_page() {
  if ($_POST["css_content"] != "") {
    echo("<div class='updated below-h2'><p>CSS Updated</p></div>");
  }
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
          <td>Unique title of the location.</td>
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
      </tbody>
    </table>

    <h4>Examples</h4>
    <pre>
      [my_map name="map_canvas" width="482px" height="415px"]
        [my_location lat="-32.340512" lng="115.819075" title="The Diva" location="12 Makybe Drive Baldivis WA 6171" 
        phone="1800 214 864" website="http://www.highburyhomes.com.au/on-display/diva/" 
        image="http://www.highburyhomes.com.au/wp-content/uploads/2012/10/46_Diva_thumb_map.jpg"][/my_location]
        [my_location lat="-32.106452" lng="115.764007" title="The Horizon" location="20 Orsino Blvd North Coogee WA 6163" 
        phone="1800 214 864" website="http://www.highburyhomes.com.au/on-display/horizon/" 
        image="http://www.highburyhomes.com.au/wp-content/uploads/2012/10/79_horizon_thumb_map.jpg"][/my_location]
      [/my_map]

      [my_map name="map_canvas_2" width="482px" height="415px" ]
        [my_location lat="-32.106452" lng="115.764007" title="The Horizon" location="20 Orsino Blvd North Coogee WA 6163" 
        phone="1800 214 864" website="http://www.highburyhomes.com.au/on-display/horizon/" 
        image="http://www.highburyhomes.com.au/wp-content/uploads/2012/10/79_horizon_thumb_map.jpg"][/my_location]
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
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $location = preg_replace('/^\//', '', $location); 
    }
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
  $name = strtolower(str_replace(" ", "_", $atts["name"]));
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
  $gmap_name = strtolower(str_replace(" ", "_", $atts["map_name"]));
  $gmap_lat = $atts["lat"];
  $gmap_lng = $atts["lng"];
  $gmap_title = $atts["title"];
  $gmap_location = $atts["location"];
  $gmap_phone = $atts["phone"];
  $gmap_website = $atts["website"];
  $gmap_image = $atts["image"];
  $gmap_location_id = $gmap_name.strtolower(str_replace(" ", "_", $atts["title"]));

  echo("
    <li id='".$gmap_location_id."'>
    <input type='hidden' name='" . $gmap_name . "_gmap_lat' value='$gmap_lat' />
    <input type='hidden' name='" . $gmap_name . "_gmap_lng' value='$gmap_lng' />
    <input type='hidden' name='" . $gmap_name . "_gmap_title' value='$gmap_title' />
    <input type='hidden' name='" . $gmap_name . "_gmap_location' value='$gmap_location' />
    <input type='hidden' name='" . $gmap_name . "_gmap_phone' value='$gmap_phone' />
    <input type='hidden' name='" . $gmap_name . "_gmap_website' value='$gmap_website' />
    <input type='hidden' name='" . $gmap_name . "_gmap_image' value='$gmap_image' />
    ".$gmap_title."
    </li>
  ");
}

add_action('wp_head', 'add_my_locations_js_and_css');
function add_my_locations_js_and_css() { 
  wp_enqueue_script('jquery');

  wp_register_script("jquery-google-ui", "https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js");
  wp_enqueue_script("jquery-google-ui");
  
  wp_register_script("jquery-google-api", "http://maps.google.com/maps/api/js?sensor=false");
  wp_enqueue_script("jquery-google-api");

  wp_register_script( 'my-locations-script', plugins_url('js/my-locations.js', __FILE__) );
  wp_enqueue_script('my-locations-script');

  wp_register_style( 'my-locations-style', plugins_url('css/my-locations.css', __FILE__) );
  wp_enqueue_style('my-locations-style'); 

  ?>

  <?php
}