<?php
/*
Plugin Name: WP Event Booking Manager
Plugin URI: http://upscalethought.com/?page_id=9
Description: Using Wordpress Event Booking Management System you can manage your event, can manage registration and sell tickets.
Version: 1.0
Author: UpScaleThought
Author URI: http://upscalethought.com
Text Domain: evntgen-ustsbooking
Domain Path: /i18n/languages/
*/
define('GENUSTSEVENT_PLUGIN_URL', plugins_url('',__FILE__));
define("EVNTGEN_USTS_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));
define('GENUSTSEVENT_DIR', plugin_dir_path(__FILE__) );
define('EVNTGEN_BOOKED_BG_COLOR','138219') ;

$ustsevent_calendar_page = get_page_by_path('usts-event-calendar');

$ustsevent_calendar_page_id= 0;
if(isset($ustsevent_calendar_page)){
  $ustsevent_calendar_page_id = $ustsevent_calendar_page->ID;
}
define('USTSEVENTCALENDAR_PAGEID', $ustsevent_calendar_page_id);

//include_once('includes/calendar_shortcode.php');
include_once('includes/currency.php');
include_once('includes/fullcalendar_shortcode.php');
//include_once('includes/event_pro_version.php');
//include_once('front-login/frontLoginForm.php');
//include_once('includes/roomsgallery_shortcode.php');
//include_once('includes/user_add_event_shortcode.php');
//include_once('includes/shoppingcart_shortcode.php');
//include_once('includes/checkout_shortcode.php');
//=====Payment System include===============================
//include_once('evntpg_core/evntpg_core.php');
//include_once('evntpg_admin/settings/evntpg_settings.php');
//include_once('evntpg_frontend/evntpg_checkout.php');
//include_once ('evntpg_frontend/evntpg_success.php');
include_once('includes/create_page.php');
include_once('operations/ustsevent_init.php');
//=================================================
/*add_action('admin_menu', 'evntgen_plugin_admin_menu');
function evntgen_plugin_admin_menu(){
	//add_menu_page('Event Management Pro', 'Event Pro', 'publish_posts', 'custom_event', 'evntgen_settings_menu');
	add_object_page('Event Management Pro', 'Event Managementss', 'publish_posts', 'custom_event', 'evntgen_settings_menu');
}*/
function evntgen_settings_menu(){
?>
	<div> <h2>Event Management</h2></div>
 <?php 
}

add_action( 'init', 'evntgen_create_custom_post_type' );
function evntgen_create_custom_post_type() {
	register_post_type( 'evntgen_custom_event',
		array(
			'labels' => array(
				'name' => __('Events','evntgen-ustsbooking'),
				'singular_name' => __('Event','evntgen-ustsbooking'),
				'menu_name'=>__('WP Event Booking Manager','evntgen-ustsbooking'),
				'all_items'=>__('Events','evntgen-ustsbooking'),
				'add_new_item'=>__('Add New Event','evntgen-ustsbooking'),
				'add_new'=> __('Add New Event','evntgen-ustsbooking'),
				'not_found'=>__('No events found.','evntgen-ustsbooking'),
				'search_items'=>__('Search Events','evntgen-ustsbooking'),
				'edit_item'=>__('Edit Event','evntgen-ustsbooking'),
				'view_item'=>__('View Event','evntgen-ustsbooking'),
				'not_found_in_trash'=>__('No Events found in trash','evntgen-ustsbooking')
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'custom_bookings'),
      'supports' => array('title','thumbnail')
		)
	);
}
add_action( 'init', 'evntgen_create_book_taxonomy' );

function evntgen_create_book_taxonomy() {
	register_taxonomy(
		'evntgen_custom_category',
		'evntgen_custom_event',
		array(
			'label' => __( 'Event Category' ),
			'rewrite' => array( 'slug' => 'evntgen_custom_category' ),
			'hierarchical' => true,
		)
	);
}
function  evntgen_add_metabox_for_event(){
add_meta_box(
		'event_attribute_metabox', // ID, should be a string
		'Event Attribute Settings', // Meta Box Title
		'evntgen_event_meta_box_content', // Your call back function, this is where your form field will go
		'evntgen_custom_event', // The post type you want this to edit screen section (�post�, �page�, �dashboard�, �link�, �attachment� or �custom_post_type� where custom_post_type is the custom post type slug)
		'normal', // The placement of your meta box, can be �normal�, �advanced�or side
		'high' // The priority in which this will be displayed
		);
}

function evntgen_event_meta_box_content($post){
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	 
	 jQuery('#upload_eventimage_button').click(function() {
			formfield = jQuery('#eventmetabox_image').attr('name');
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				jQuery('#eventmetabox_image').val(imgurl);
				tb_remove();
			}
			return false;
	 });
	 jQuery("#eventmetabox_startdate").datepicker({
      dateFormat: "yy-mm-dd"		
	 });
   jQuery("#eventmetabox_enddate").datepicker({
      dateFormat: "yy-mm-dd"		
	 });
   jQuery('#event_venue').val(<?php echo get_post_meta($post->ID, '_event_venue', true);?>);
   jQuery('#event_organizer').val(<?php echo get_post_meta($post->ID, '_event_organizer', true);?>);
   jQuery('#event_sponsor').val(<?php echo get_post_meta($post->ID, '_event_sponsor', true);?>);
});
</script>
<?php
global $table_prefix,$wpdb;
$venue_sql = "select * from ".$table_prefix."evntgen_venues";
$venues = $wpdb->get_results( $venue_sql );
$organizer_sql = "select * from ".$table_prefix."evntgen_organizers";
$organizers = $wpdb->get_results( $organizer_sql );
$sponsor_sql = "select * from ".$table_prefix."evntgen_sponsors";
$sponsors = $wpdb->get_results( $sponsor_sql );
//die(print_r($venues));
//wp_nonce_field( basename( __FILE__ ), �prfx_nonce� );
// Get post meta value using the key from our save function in the second paramater.
$event_description = get_post_meta($post->ID, '_event_description', true);
$event_noofseat = get_post_meta($post->ID, '_event_noofseat', true);
$event_price = get_post_meta($post->ID, '_event_price', true);
$event_website = get_post_meta($post->ID, '_event_website', true);
$event_image = get_post_meta($post->ID, '_event_image', true);

$event_startdate = get_post_meta($post->ID, '_event_startdate', true);
$event_starthour = get_post_meta($post->ID, '_event_starthour', true);
$event_startminute = get_post_meta($post->ID, '_event_startminute', true);

$event_enddate = get_post_meta($post->ID, '_event_enddate', true);
$event_endhour = get_post_meta($post->ID, '_event_endhour', true);
$event_endminute = get_post_meta($post->ID, '_event_endminute', true);

$event_venue = get_post_meta($post->ID, '_event_venue', true);
//die('==>>>'.$event_venue);
$event_organizer = get_post_meta($post->ID, '_event_organizer', true);
$event_sponsor = get_post_meta($post->ID, '_event_sponsor', true);
    
?>
<style type="text/css">
  .blank_row{
    height:30px;
  }
</style>
<table >
  <tbody>
  	<tr>
      <th scope="row">Description:</th>
      <td>
      	<?php
					$content = $event_description;
					$editor_id = 'mycustomeditor';
					$settings = array('wpautop'=>true,'media_buttons'=>true,'textarea_name'=>'event_description','textarea_rows'=>8);
					wp_editor( $content, $editor_id,$settings );
				?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="blank_row"></td>
    </tr>
    <tr>
    	<th scope="row">Capacity/Total Seat:</th>
      <td><input type="text" name="eventmetabox_noofseat" id="eventmetabox_noofseat" value="<?php if(isset($event_noofseat)) echo $event_noofseat;?>" style="width:300px;" /></td>
    </tr>
    <tr>
    	<th scope="row">Ticket Price:</th>
      <td><input type="text" name="eventmetabox_price" id="eventmetabox_price" value="<?php if(isset($event_price)) echo $event_price;?>" style="width:300px;" /></td>
    </tr>
    <tr>
    	<th scope="row">Event Website:</th>
      <td><input type="text" name="eventmetabox_website" id="eventmetabox_website" value="<?php if(isset($event_website)) echo $event_website;?>" style="width:300px;" /></td>
    </tr>
    <tr>
    	<th scope="row">Image:</th>
      <td>
      	<input type="text" class="code"  name="eventmetabox_image" id="eventmetabox_image" value="<?php if(isset($event_image)) echo $event_image;?>" style="width:300px;" />
        <input  id="upload_eventimage_button" class="button" type="button" value="Upload Image" />
      </td>
    </tr>
    <tr>
      <td colspan="2" class="blank_row"></td>
    </tr>
    <tr>
      <td colspan="2"><b>EVENT TIME & DATE</b><hr></td>
    </tr>
    <tr>
    	<th scope="row">Start Date & Time:</th>
      <td>
        <input type="text" name="eventmetabox_startdate" id="eventmetabox_startdate" value="<?php if(isset($event_startdate)) echo $event_startdate;?>" style="width:200px;" />
        <select name="eventmetabox_starthour" id="eventmetabox_starthour">
          <option value="01" <?php if($event_starthour==01) echo 'selected="selected"'?>>01</option>
          <option value="02" <?php if($event_starthour==02) echo 'selected="selected"'?>>02</option>
          <option value="03" <?php if($event_starthour==03) echo 'selected="selected"'?>>03</option>
          <option value="04" <?php if($event_starthour==04) echo 'selected="selected"'?>>04</option>
          <option value="05" <?php if($event_starthour==05) echo 'selected="selected"'?>>05</option>
          <option value="06" <?php if($event_starthour==06) echo 'selected="selected"'?>>06</option>
          <option value="07" <?php if($event_starthour==07) echo 'selected="selected"'?>>07</option>
          <option value="08" <?php if($event_starthour==08) echo 'selected="selected"'?>>08</option>
          <option value="09" <?php if($event_starthour==09) echo 'selected="selected"'?>>09</option>
          <option value="10" <?php if($event_starthour==10) echo 'selected="selected"'?>>10</option>
          <option value="11" <?php if($event_starthour==11) echo 'selected="selected"'?>>11</option>
          <option value="12" <?php if($event_starthour==12) echo 'selected="selected"'?>>12</option>
          <option value="13" <?php if($event_starthour==13) echo 'selected="selected"'?>>13</option>
          <option value="14" <?php if($event_starthour==14) echo 'selected="selected"'?>>14</option>
          <option value="15" <?php if($event_starthour==15) echo 'selected="selected"'?>>15</option>
          <option value="16" <?php if($event_starthour==16) echo 'selected="selected"'?>>16</option>
          <option value="17" <?php if($event_starthour==17) echo 'selected="selected"'?>>17</option>
          <option value="18" <?php if($event_starthour==18) echo 'selected="selected"'?>>18</option>
          <option value="19" <?php if($event_starthour==19) echo 'selected="selected"'?>>19</option>
          <option value="20" <?php if($event_starthour==20) echo 'selected="selected"'?>>20</option>
          <option value="21" <?php if($event_starthour==21) echo 'selected="selected"'?>>21</option>
          <option value="22" <?php if($event_starthour==22) echo 'selected="selected"'?>>22</option>
          <option value="23" <?php if($event_starthour==23) echo 'selected="selected"'?>>23</option>
          <option value="24" <?php if($event_starthour==24) echo 'selected="selected"'?>>24</option>
        </select>
        <select  name="eventmetabox_startminute" id="eventmetabox_startminute"> 
          <option value="00" <?php if($event_startminute==00) echo 'selected="selected"'?>>00</option>
          <option value="05" <?php if($event_startminute==05) echo 'selected="selected"'?>>05</option>
          <option value="10" <?php if($event_startminute==10) echo 'selected="selected"'?>>10</option>
          <option value="15" <?php if($event_startminute==15) echo 'selected="selected"'?>>15</option>
          <option value="20" <?php if($event_startminute==20) echo 'selected="selected"'?>>20</option>
          <option value="25" <?php if($event_startminute==25) echo 'selected="selected"'?>>25</option>
          <option value="30" <?php if($event_startminute==30) echo 'selected="selected"'?>>30</option>
          <option value="35" <?php if($event_startminute==35) echo 'selected="selected"'?>>35</option>
          <option value="40" <?php if($event_startminute==40) echo 'selected="selected"'?>>40</option>
          <option value="45" <?php if($event_startminute==45) echo 'selected="selected"'?>>45</option>
          <option value="50" <?php if($event_startminute==50) echo 'selected="selected"'?>>50</option>
          <option value="55" <?php if($event_startminute==55) echo 'selected="selected"'?>>55</option>
          <option value="60" <?php if($event_startminute==60) echo 'selected="selected"'?>>60</option>
          
        </select>
        <!--<select  name="eventmetabox_startdayshift" id="eventmetabox_startdayshift">
          <option>AM</option>
          <option>PM</option>
        </select>-->
      </td>
    </tr>
    <tr>
    	<th scope="row">End Date & Time:</th>
      <td>
        <input type="text" name="eventmetabox_enddate" id="eventmetabox_enddate" value="<?php if(isset($event_enddate)) echo $event_enddate;?>" style="width:200px;" />
        <select name="eventmetabox_endhour" id="eventmetabox_endhour">
          <option value="01" <?php if($event_endhour==01) echo 'selected="selected"'?>>01</option>
          <option value="02" <?php if($event_endhour==02) echo 'selected="selected"'?>>02</option>
          <option value="03" <?php if($event_endhour==03) echo 'selected="selected"'?>>03</option>
          <option value="04" <?php if($event_endhour==04) echo 'selected="selected"'?>>04</option>
          <option value="05" <?php if($event_endhour==05) echo 'selected="selected"'?>>05</option>
          <option value="06" <?php if($event_endhour==06) echo 'selected="selected"'?>>06</option>
          <option value="07" <?php if($event_endhour==07) echo 'selected="selected"'?>>07</option>
          <option value="08" <?php if($event_endhour==08) echo 'selected="selected"'?>>08</option>
          <option value="09" <?php if($event_endhour==09) echo 'selected="selected"'?>>09</option>
          <option value="10" <?php if($event_endhour==10) echo 'selected="selected"'?>>10</option>
          <option value="11" <?php if($event_endhour==11) echo 'selected="selected"'?>>11</option>
          <option value="12" <?php if($event_endhour==12) echo 'selected="selected"'?>>12</option>
          <option value="13" <?php if($event_endhour==13) echo 'selected="selected"'?>>13</option>
          <option value="14" <?php if($event_endhour==14) echo 'selected="selected"'?>>14</option>
          <option value="15" <?php if($event_endhour==15) echo 'selected="selected"'?>>15</option>
          <option value="16" <?php if($event_endhour==16) echo 'selected="selected"'?>>16</option>
          <option value="17" <?php if($event_endhour==17) echo 'selected="selected"'?>>17</option>
          <option value="18" <?php if($event_endhour==18) echo 'selected="selected"'?>>18</option>
          <option value="19" <?php if($event_endhour==19) echo 'selected="selected"'?>>19</option>
          <option value="20" <?php if($event_endhour==20) echo 'selected="selected"'?>>20</option>
          <option value="21" <?php if($event_endhour==21) echo 'selected="selected"'?>>21</option>
          <option value="22" <?php if($event_endhour==22) echo 'selected="selected"'?>>22</option>
          <option value="23" <?php if($event_endhour==23) echo 'selected="selected"'?>>23</option>
          <option value="24" <?php if($event_endhour==24) echo 'selected="selected"'?>>24</option>
          
        </select>
        <select  name="eventmetabox_endminute" id="eventmetabox_endminute">
          <option value="00" <?php if($event_endminute==00) echo 'selected="selected"'?>>00</option>
          <option value="05" <?php if($event_endminute==05) echo 'selected="selected"'?>>05</option>
          <option value="10" <?php if($event_endminute==10) echo 'selected="selected"'?>>10</option>
          <option value="15" <?php if($event_endminute==15) echo 'selected="selected"'?>>15</option>
          <option value="20" <?php if($event_endminute==20) echo 'selected="selected"'?>>20</option>
          <option value="25" <?php if($event_endminute==25) echo 'selected="selected"'?>>25</option>
          <option value="30" <?php if($event_endminute==30) echo 'selected="selected"'?>>30</option>
          <option value="35" <?php if($event_endminute==35) echo 'selected="selected"'?>>35</option>
          <option value="40" <?php if($event_endminute==40) echo 'selected="selected"'?>>40</option>
          <option value="45" <?php if($event_endminute==45) echo 'selected="selected"'?>>45</option>
          <option value="50" <?php if($event_endminute==50) echo 'selected="selected"'?>>50</option>
          <option value="55" <?php if($event_endminute==55) echo 'selected="selected"'?>>55</option>
          <option value="60" <?php if($event_endminute==60) echo 'selected="selected"'?>>60</option>
          
        </select>
        <!--<select  name="eventmetabox_enddayshift" id="eventmetabox_enddayshift">
          <option>AM</option>
          <option>PM</option>
        </select>-->
      </td>
    </tr>
    <tr>
      <td colspan="2" class="blank_row"></td>
    </tr>
    <tr>
      <td colspan="2"><b>EVENT VENUE DETAILS</b><hr></td>
    </tr>
    <tr>
      <td>Venue: </td>
      <td>
        <select name="event_venue" id="event_venue">
          <?php foreach($venues as $venue){?>
          <option value="<?php echo $venue->id?>"><?php echo $venue->venue_name;?></option>
          <?php }?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="blank_row"></td>
    </tr>
    <tr>
      <td colspan="2"><b>EVENT ORGANIZER DETAILS</b><hr></td>
    </tr>
    <tr>
      <td>Organizer: </td>
      <td>
        <select name="event_organizer" id="event_organizer">
          <?php foreach($organizers as $organizer){?>
          <option value="<?php echo $organizer->id?>"><?php echo $organizer->organizer_name;?></option>
          <?php }?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="blank_row"></td>
    </tr>
    <tr>
      <td colspan="2"><b>EVENT SPONSOR DETAILS</b><hr></td>
    </tr>
    <tr>
      <td>Sponsor: </td>
      <td>
        <select name="event_sponsor" id="event_sponsor">
          <?php foreach($sponsors as $sponsor){?>
          <option value="<?php echo $sponsor->id?>"><?php echo $sponsor->sponsor_name;?></option>
          <?php }?>
        </select>
      </td>
    </tr>
  </tbody>
</table>
<?php 
}
function evntgen_save_event_metabox(){
	global $post;
	// Get our form field 
	if( $_POST ) :
		$event_description = esc_attr( $_POST['event_description'] );
		$eventmetabox_noofseat = esc_attr( $_POST['eventmetabox_noofseat'] );
		$eventmetabox_price = esc_attr( $_POST['eventmetabox_price'] );
		$eventmetabox_website = esc_attr( $_POST['eventmetabox_website'] );
		$eventmetabox_image = esc_attr( $_POST['eventmetabox_image'] );
    
		$eventmetabox_startdate = esc_attr( $_POST['eventmetabox_startdate'] );
		$eventmetabox_starthour = esc_attr( $_POST['eventmetabox_starthour'] );
    $eventmetabox_startminute = esc_attr( $_POST['eventmetabox_startminute'] );
    
    $eventmetabox_enddate = esc_attr( $_POST['eventmetabox_enddate'] );
		$eventmetabox_endhour = esc_attr( $_POST['eventmetabox_endhour'] );
    $eventmetabox_endminute = esc_attr( $_POST['eventmetabox_endminute'] );
    
    $event_venue = esc_attr( $_POST['event_venue'] );
    $event_organizer = esc_attr( $_POST['event_organizer'] );
    $event_sponsor = esc_attr( $_POST['event_sponsor'] );
		// Update post meta
		update_post_meta($post->ID, '_event_description', $event_description);
		update_post_meta($post->ID, '_event_noofseat', $eventmetabox_noofseat);
    update_post_meta($post->ID, '_event_noofseat_booked', 0);
		update_post_meta($post->ID, '_event_price', $eventmetabox_price);
		update_post_meta($post->ID, '_event_website', $eventmetabox_website);
		update_post_meta($post->ID, '_event_image', $eventmetabox_image);
    
		update_post_meta($post->ID, '_event_startdate', $eventmetabox_startdate);
    update_post_meta($post->ID, '_event_starthour', $eventmetabox_starthour);
    update_post_meta($post->ID, '_event_startminute', $eventmetabox_startminute);
    
    update_post_meta($post->ID, '_event_enddate', $eventmetabox_enddate);
    update_post_meta($post->ID, '_event_endhour', $eventmetabox_endhour);
    update_post_meta($post->ID, '_event_endminute', $eventmetabox_endminute);
    
    update_post_meta($post->ID, '_event_venue', $event_venue);
    update_post_meta($post->ID, '_event_organizer', $event_organizer);
    update_post_meta($post->ID, '_event_sponsor', $event_sponsor);
	endif;
}

add_action( 'save_post', 'evntgen_save_event_metabox' );
add_action('add_meta_boxes','evntgen_add_metabox_for_event');

/*---------------------*/
function evntgen_add_events_menu(){
	//add_submenu_page('edit.php?post_type=custom_event', 'Payment Settings', 'Payment Settings', 'edit_posts', 'evntpg_settingss', 'evntpg_global_settings');
	//add_submenu_page('edit.php?post_type=evntgen_custom_event', 'Payment Settings', 'Payment Settings', 'manage_options', 'evntpg_settings', 'evntpg_global_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Add Schedule', 'Add Schedule', 'manage_options', 'add-schedule-menu', 'evntgen_add_schedule_settings');
	//add_submenu_page( '-', 'Add Schedule', 'Add Schedule', 'manage_options', 'add-schedule-menu', 'evntgen_add_schedule_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Manage Schedule', 'Manage Schedule', 'manage_options', 'manage-schedule-menu', 'evntgen_manage_schedule_settings');
	///add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Manage Schedule', 'Manage Schedule', 'manage_options', 'manage-schedule-menu', 'evntgen_manage_schedule_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Add Venue', 'Add Venue', 'manage_options', 'add-venues-menu', 'evntgen_add_venues_settings');
	add_submenu_page( '-', 'Add Venue', 'Add Venue', 'manage_options', 'add-venues-menu', 'evntgen_add_venues_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Manage Venue', 'Manage Venue', 'manage_options', 'manage-venue-menu', 'evntgen_manage_venue_settings');
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Manage Venue', 'Manage Venue', 'manage_options', 'manage-venue-menu', 'evntgen_manage_venue_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Add Sponsor', 'Add Sponsor', 'manage_options', 'add-sponsors-menu', 'evntgen_add_sponsors_settings');
	add_submenu_page( '-', 'Add Sponsor', 'Add Sponsor', 'manage_options', 'add-sponsors-menu', 'evntgen_add_sponsors_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Manage Sponsor', 'Manage Sponsor', 'manage_options', 'manage-sponsor-menu', 'evntgen_manage_sponsor_settings');
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Manage Sponsor', 'Manage Sponsors', 'manage_options', 'manage-sponsor-menu', 'evntgen_manage_sponsor_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Add Organizer', 'Add Organizer', 'manage_options', 'add-organizer-menu', 'evntgen_add_organizer_settings');
	add_submenu_page( '-', 'Add Organizer', 'Add Organizer', 'manage_options', 'add-organizer-menu', 'evntgen_add_organizer_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Manage Organizer', 'Manage Organizer', 'manage_options', 'manage-organizer-menu', 'evntgen_manage_organizer_settings');
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Manage Organizer', 'Manage Organizer', 'manage_options', 'manage-organizer-menu', 'evntgen_manage_organizer_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Add Event', 'Add Event', 'manage_options', 'add-event-menu', 'evntgen_add_event_settings' );
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Add Event Booking', 'Add Event Booking', 'manage_options', 'add-event-menu', 'evntgen_add_event_settings' );
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Manage Event', 'Manage Event', 'manage_options', 'manage-event-menu', 'evntgen_manage_event_settings');
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Manage Event Booking', 'Manage Event Booking', 'manage_options', 'manage-event-menu', 'evntgen_manage_event_settings');
	//add_submenu_page( 'edit.php?post_type=custom_event', 'Event Calendar', 'Event Calendar', 'manage_options', 'event-calendar-menu', 'evntgen_event_calendar' );
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Event Calendar', 'Event Calendar', 'manage_options', 'event-calendar-menu', 'evntgen_event_calendar' );	
	//add_submenu_page( 'edit.php?post_type=custom_event', 'event Settings', 'event Settings', 'manage_options', 'event-settings-menu', 'evntgen_event_settings_page' );
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'event Settings', 'event Settings', 'manage_options', 'event-settings-menu', 'evntgen_event_settings_page' );
	//function gen_cssfix_front(){
	add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'FrontEnd CSS Fix', 'FrontEnd CSS Fix', 'manage_options', 'css-fix-menu', 'evntgen_cssfix_front_setting' );
  //}
  add_submenu_page( 'edit.php?post_type=evntgen_custom_event', 'Wordpress Event Commerce Pro', 'Wordpress Event Commerce Pro', 'manage_options', 'event-pro-menu', 'evntgen_wordpress_event_commerce_pro' );
}
//-------------event Settings-----------------------
function evntgen_scevent_get_opt_val($opt_name,$default_val){
		if(get_option($opt_name)!=''){
			return $value = get_option($opt_name);
		}else{
			return $value =$default_val;
		}
}
//Schedule
function evntgen_add_schedule_settings(){
	include_once('includes/add_schedule.php');
}
/*function evntgen_manage_schedule_settings(){
	include_once('includes/manage_schedule.php');
}*/
//venue
function evntgen_add_venues_settings(){
	include_once('includes/add_venue.php');
}
function evntgen_manage_venue_settings(){
	include_once('includes/manage_venue.php');
}
// Sponsor function
function evntgen_add_sponsors_settings(){
	include_once('includes/add_sponsor.php');
}
function evntgen_manage_sponsor_settings(){
	include_once('includes/manage_sponsor.php');
}
//time slot funcitons
function evntgen_add_organizer_settings(){
	include_once('includes/add_organizer.php');
}
function evntgen_manage_organizer_settings(){
	include_once('includes/manage_organizer.php');
}
//
function evntgen_event_settings_page(){
	include_once('operations/event_settings.php');
}
//
function evntgen_event_calendar(){
	//include_once('calendar.php');
	include_once('calendar-fullcalendar.php');
	//include_once('fullcalendar.php');
}
function evntgen_manage_event_settings(){
	include_once('includes/manage_event.php');
}
function evntgen_add_event_settings(){
	include_once('includes/add_event_backend.php');
}
function evntgen_cssfix_front_setting(){
	include_once('includes/add_cssfix_front.php');	
}
function evntgen_wordpress_event_commerce_pro(){
  include_once('includes/event_pro_version.php');	
}
add_action('admin_menu','evntgen_add_events_menu');
/*---------------------*/

function evntgen_event_uninstall(){
}

register_activation_hook( __FILE__, 'evntgen_ustsevent_install' );
register_deactivation_hook( __FILE__, 'evntgen_event_uninstall');

/*function evntgen_prevent_admin_access()
{
    if (strpos( strtolower( $_SERVER['REQUEST_URI'] ), '/wp-admin' ) && !current_user_can( 'administrator' ) ){
    		//die('user....');
		    wp_redirect( home_url() );
		}
}
add_action( 'init', 'evntgen_prevent_admin_access', 0 );*/
//====== session start =================================
add_action('init', 'evntgen_eventStartSession', 1);
function evntgen_eventStartSession() {
    if(!session_id()) {
        session_start();
    }
}
//------
function evntgen_eventjs(){
	wp_register_script('eventjs',plugins_url('/includes/js/event.js',__FILE__));
	wp_localize_script( 'eventjs', 'scEventAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	
	wp_enqueue_script( 'eventjs');
}
function evntgen_eventjs_front(){
	wp_register_script('eventjs_front',plugins_url('/includes/js/event_front.js',__FILE__),'jquery',"",true);
	wp_localize_script( 'eventjs_front', 'scEventAjax_front', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_enqueue_script( 'eventjs_front');
}

add_action('admin_enqueue_scripts','evntgen_eventjs');
add_action('wp_enqueue_scripts','evntgen_eventjs_front');

function evntgen_add_organizer_ajax_request(){
	global $table_prefix,$wpdb;	
	if ( isset($_REQUEST) ) {
		$hdnorganizerid = $_REQUEST['hdnorganizerid'];
		$organizer_name = $_REQUEST['organizer_name'];
		$phone = $_REQUEST['phone'];
		$website = $_REQUEST['website'];
		$email = $_REQUEST['email'];
		
		$values = array(
			'organizer_name'=>$organizer_name,
			'phone'=>$phone,
			'website'=>$website,
			'email'=>$email 
		);
		//echo 'no choice';
		//die();
		if($hdnorganizerid == "" || $hdnorganizerid == NULL){
			$wpdb->insert($table_prefix.'evntgen_organizers',$values );	
			$inserted_id = $wpdb->insert_id;
			echo $inserted_id;
			//echo 'if';
		}
		else{
			$wpdb->update(
				 $table_prefix.'evntgen_organizers',
				 $values,
				 array('id' =>$hdnorganizerid)
			 );
			 //$updated_id = $wpdb->insert_id;
			 echo $hdnorganizerid;
			 //echo 'else';
		}
	}
	exit;
	//die() ;
}
add_action( 'wp_ajax_nopriv_evntgen_add_organizer_ajax_request','evntgen_add_organizer_ajax_request' );
add_action( 'wp_ajax_evntgen_add_organizer_ajax_request', 'evntgen_add_organizer_ajax_request' );

function evntgen_add_sponsor_ajax_request(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$hdnsponsorid = $_REQUEST['hdnsponsorid'];
			$sponsor_name = $_REQUEST['sponsor_name'];
      $phone = $_REQUEST['phone'];
			$email = $_REQUEST['email'];
			$website = $_REQUEST['website'];
			$address = $_REQUEST['address'];
			
			$values = array(
				'sponsor_name'=>$sponsor_name,
        'phone'=>$phone,  
				'email'=>$email,
				'website'=>$website,
				'address'=>$address 
			);
			if($hdnsponsorid == "" || $hdnsponsorid == NULL){
				$wpdb->insert($table_prefix.'evntgen_sponsors',$values );	
				$inserted_id = $wpdb->insert_id;
				echo $inserted_id;
				//echo 'if';
			}
			else{
				$wpdb->update(
					 $table_prefix.'evntgen_sponsors',
					 $values,
					 array('id' =>$hdnsponsorid)
				 );
				 //$updated_id = $wpdb->insert_id;
				 echo $hdnsponsorid;
				 //echo 'else';
			}
		}
		exit;
}

add_action( 'wp_ajax_nopriv_evntgen_add_sponsor_ajax_request','evntgen_add_sponsor_ajax_request' );
add_action( 'wp_ajax_evntgen_add_sponsor_ajax_request', 'evntgen_add_sponsor_ajax_request' );

function evntgen_add_venue_ajax_request(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$hdnvenueid = $_REQUEST['hdnvenueid'];
			//echo 'id: '.$hdnorganizerid; die();
			$venue_name = $_REQUEST['venue_name'];
			$venue_address = $_REQUEST['venue_address'];
			$city = $_REQUEST['city'];
			$country = $_REQUEST['country'];
      $postal_code = $_REQUEST['postal_code'];
      $phone = $_REQUEST['phone'];
      $website = $_REQUEST['website'];
      
			$values = array(
				'venue_name'=>$venue_name,
				'venue_address'=>$venue_address,
				'city'=>$city,
        'country'=>$country,
        'postal_code'=>$postal_code,
        'phone'=>$phone,
        'website'=>$website
			);
			//echo 'no choice';
			//die();
			if($hdnvenueid == "" || $hdnvenueid == NULL){
				$wpdb->insert($table_prefix.'evntgen_venues',$values );	
				$inserted_id = $wpdb->insert_id;
				echo $inserted_id;
				//echo 'if';
			}
			else{
				$wpdb->update(
					 $table_prefix.'evntgen_venues',
					 $values,
					 array('id' =>$hdnvenueid)
				 );
				 //$updated_id = $wpdb->insert_id;
				 echo $hdnvenueid;
				 //echo 'else';
			}
		}
		exit;
}

add_action( 'wp_ajax_nopriv_evntgen_add_venue_ajax_request','evntgen_add_venue_ajax_request' );
add_action( 'wp_ajax_evntgen_add_venue_ajax_request', 'evntgen_add_venue_ajax_request' );

function evntgen_add_schedule_ajax_request(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$hdnscheduleid = $_REQUEST['hdnscheduleid'];
			//echo 'id: '.$hdnorganizerid; die();
			$schedule_name = $_REQUEST['schedule_name'];
			$organizer = $_REQUEST['optorganizer'];
			$sponsor = $_REQUEST['optsponsor'];
			$venue = $_REQUEST['optvenue'];
			
			$values = array(
				'schedule_name'=>$schedule_name,
				'organizer'=>$organizer,
				'sponsor'=>$sponsor,
				'venue'=>$venue
			);
			if($hdnscheduleid == "" || $hdnscheduleid == NULL){
				$wpdb->insert($table_prefix.'evntgen_schedules',$values );	
				$inserted_id = $wpdb->insert_id;
				echo $inserted_id;
			}
			else{
				$wpdb->update(
					 $table_prefix.'evntgen_schedules',
					 $values,
					 array('id' =>$hdnscheduleid)
				 );
				 echo $hdnscheduleid;
			}
		}
		exit;
}

add_action( 'wp_ajax_nopriv_evntgen_add_schedule_ajax_request','evntgen_add_schedule_ajax_request' );
add_action( 'wp_ajax_evntgen_add_schedule_ajax_request', 'evntgen_add_schedule_ajax_request' );
//
function evntgen_set_ajax_event_session(){
		//die();
		global $table_prefix,$wpdb;	
		//echo 'im here';die();
		if ( isset($_REQUEST) ) {
			$_SESSION['eventid'] = $_REQUEST['eventid']; 
			echo $_SESSION['eventid'];
		}
		exit;
}

add_action( 'wp_ajax_nopriv_evntgen_set_ajax_event_session','evntgen_set_ajax_event_session' );
add_action( 'wp_ajax_evntgen_set_ajax_event_session', 'evntgen_set_ajax_event_session' );

//
function evntgen_set_ajax_event_session_front(){
		global $table_prefix,$wpdb;	
		if ( isset($_REQUEST) ) {
			$_SESSION['eventid_front'] = $_REQUEST['eventid']; 
			echo $_SESSION['eventid_front'];
		}
		exit;
}

add_action( 'wp_ajax_nopriv_evntgen_set_ajax_event_session_front','evntgen_set_ajax_event_session_front' );
add_action( 'wp_ajax_evntgen_evntgen_set_ajax_event_session_front', 'evntgen_set_ajax_event_session_front' );

//
//------ Shopping Cart Session ajax call ------

function evntgen_shoppingcartjs(){
	//wp_enqueue_script( 'shoppingcartjs',GENUSTSEVENT_PLUGIN_URL.'/shoppingcart/js/shoppingcart.js',true);
	wp_localize_script( 'shoppingcartjs', 'scCartAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}
add_action('wp_footer','evntgen_shoppingcartjs');
function evntgen_cart_session_ajax_request(){
	if ( isset($_REQUEST) ) {
		$indx = $_REQUEST['indx'];
		$shopping_cart_arr = $_SESSION['eventcart'];	
	  unset($shopping_cart_arr[$indx]);
		sort($shopping_cart_arr);
	  $_SESSION['eventcart'] = $shopping_cart_arr;	
		print_r($shopping_cart_arr);//json_encode($shopping_cart_arr);
	}
	exit;
	//die() ;
}
add_action( 'wp_ajax_nopriv_evntgen_cart_session_ajax_request','evntgen_cart_session_ajax_request' );
add_action( 'wp_ajax_evntgen_cart_session_ajax_request', 'evntgen_cart_session_ajax_request' );
//=========Payment System-----------------------------------------------------------------------------------
define('WP_CUSTOM_PRODUCT_URL', plugins_url('',__FILE__));
define('WP_CUSTOM_PRODUCT_PATH',plugin_dir_path( __FILE__ ));
function add_admin_additional_script(){
  wp_enqueue_script( 'thickbox');
  wp_enqueue_style ( 'thickbox');
  wp_enqueue_media();

  wp_enqueue_script( 'post' );
  wp_enqueue_style ( 'evntpg_admin_style',plugins_url( '/evntpg_resource/admin/css/admin.css', __FILE__ ));
  //wp_enqueue_script( 'jquery-no-conflict.js', plugins_url( '/evntpg_resource/js/jquery-no-conflict.js', __FILE__ ) );
}
function add_frontend_additional_script(){
	wp_enqueue_style( 'custom.css', plugins_url( '/evntpg_resource/css/custom.css', __FILE__ ) );
}
function load_custom_wp_admin_style() {
  //wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}
//---------------------End Payemnt system code-----------------------------------------------------------------------
//============= WP Ajax Calls ====================================================
function evntgen_get_sponsorprice_by_schedule(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $schedule = $_REQUEST['schedule'];
    $price = 0;
    $sql_sponsor_price = "select scd.id as scheduleid,srv.id as sponsorid,tmsl.id as organizerid,vn.id as venueid, scd.* ,srv.*,tmsl.*,vn.* from ".$table_prefix."evntgen_schedules scd inner join ".$table_prefix."evntgen_sponsors srv on scd.sponsor = srv.id 
    inner join ".$table_prefix."evntgen_organizers tmsl on tmsl.id = scd.organizer
    inner join ".$table_prefix."evntgen_venues vn on vn.id = scd.venue 
    where scd.id=".$schedule;	
    $result = $wpdb->get_results($sql_sponsor_price);
    $price = $result[0]->price;
    echo $price;    
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_get_sponsorprice_by_schedule','evntgen_get_sponsorprice_by_schedule' );
add_action( 'wp_ajax_evntgen_get_sponsorprice_by_schedule', 'evntgen_get_sponsorprice_by_schedule' );
function evntgen_get_events(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $event_id = $_REQUEST['event_id'];
    $sql = "select * from ".$table_prefix."evntgen_ustsevents where event_id=".$event_id;
    $result = $wpdb->get_results($sql);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_get_events','evntgen_get_events' );
add_action( 'wp_ajax_evntgen_get_events', 'evntgen_get_events' );

function evntgen_load_manageevent_data_front(){
  if(isset($_REQUEST)){
    $msg ='<style type="text/css">
        #loading{
            width: 50px;
            position: absolute;
            /*top: 100px;
            left: 100px;
            margin-top:200px;*/
            height:50px;
        }
        #inner_content{
           padding: 0 20px 0 0!important;
        }
        #inner_content .pagination ul li.inactive,
        #inner_content .pagination ul li.inactive:hover{
            background-color:#ededed;
            color:#bababa;
            border:1px solid #bababa;
            cursor: default;
        }
        #inner_content .data ul li{
            list-style: none;
            font-family: verdana;
            margin: 5px 0 5px 0;
            color: #000;
            font-size: 13px;
        }

        #inner_content .pagination{
            width: 80%;/*800px;*/
            height: 45px;
        }
        #inner_content .pagination ul li{
            list-style: none;
            float: left;
            border: 1px solid #006699;
            padding: 2px 6px 2px 6px;
            margin: 0 3px 0 3px;
            font-family: arial;
            font-size: 14px;
            color: #006699;
            font-weight: bold;
            background-color: #f2f2f2;

            /*display:inline;
            cursor:pointer;*/
        }
        #inner_content .pagination ul li:hover{
            color: #fff;
            background-color: #006699;
            cursor: pointer;
        }
        .go_button
        {
          background-color:#f2f2f2;
          border:1px solid #006699;
          color:#cc0000;
          padding:2px 6px 2px 6px;
          cursor:pointer;
          position:absolute;
          /*margin-top:-1px;*/
          width:50px;
        }
        .total
        {
          float:right;
          font-family:arial;
          color:#999;
          padding-right:150px;
        }
        #namediv input {
          width:5%!important;
        }
        /*---------------------------------*/
    </style>';
    if($_POST['page'])
      {
      $page = $_POST['page'];
      $cur_page = $page;
      $page -= 1;
      $per_page = 10;//15;
      $previous_btn = true;
      $next_btn = true;
      $first_btn = true;
      $last_btn = true;
      $start = $page * $per_page;
        global $table_prefix,$wpdb;
        $sql = "select * from ".$table_prefix."evntgen_ustsevents ";
        $result_count = $wpdb->get_results($sql);
        $count = count($result_count);
        $sql = $sql.' LIMIT '.$start.', '.$per_page.'';
        $result_page_data = $wpdb->get_results($sql); 
      $msg = "<div id='content_top'></div>";
      if(count($result_page_data)){
            $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Event</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tr>';
                    foreach($result_page_data as $event){
                      $msg .= '<tr class="alternate">
                                  <td>'.$event->room.'</td>
                                  <td>'.$event->from_date.'</td>
                                  <td>'.$event->to_date.'</td>
                                  <td>'.$event->email.'</td>
                                  <td>'.$event->phone.'</td>

                                  <td>
                                    ';
                      if(!$event->confirmed){
                          $msg .= '<a id="lnkapprove" href="" > Approve </a>&nbsp;&nbsp;&nbsp;';
                      }
                      else {
                          $msg .= '<span id="" > <b>Approved </b></span>&nbsp;&nbsp;&nbsp;';
                      }
                      $msg .= '<a onclick="evntgen_open_edit_popup('.$event->event_id.')" style="cursor:pointer;text-decoration:none;" >edit</a>
                                    &nbsp;&nbsp;&nbsp;<a id="delete_event" href="#" >delete</a>
                                    <input type="hidden" id="hdneventid"  name="hdneventid" value="'.$event->event_id.'" />

                                  </td>
                              </tr>';
                    }
                    $msg .= '</tr>
                              <tfoot>
                                <tr>
                                  <th>Event</th>
                                  <th>From Date</th>
                                  <th>To Date</th>
                                  <th>Email</th>
                                  <th>Phone</th>
                                  <th></th>
                                </tr>
                              </tfoot>
                            </table>';	
        //}
      }
      else{
        $msg .= '<div style="padding:80px;color:red;">Sorry! No Data Found!</div>';
      }	
      $msg = "<div class='data'>" . $msg . "</div>"; // Content for Data

      $no_of_paginations = ceil($count / $per_page);
      /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
      if ($cur_page >= 7) {
          $start_loop = $cur_page - 3;
          if ($no_of_paginations > $cur_page + 3)
              $end_loop = $cur_page + 3;
          else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
              $start_loop = $no_of_paginations - 6;
              $end_loop = $no_of_paginations;
          } else {
              $end_loop = $no_of_paginations;
          }
      } else {
          $start_loop = 1;
          if ($no_of_paginations > 7)
              $end_loop = 7;
          else
              $end_loop = $no_of_paginations;
      }
      /* ----------------------------------------------------------------------------------------------------------- */
      $msg .= "<div class='pagination'><ul>";

      // FOR ENABLING THE FIRST BUTTON
      if ($first_btn && $cur_page > 1) {
          $msg .= "<li p='1' class='active'>First</li>";
      } else if ($first_btn) {
          $msg .= "<li p='1' class='inactive'>First</li>";
      }

      // FOR ENABLING THE PREVIOUS BUTTON
      if ($previous_btn && $cur_page > 1) {
          $pre = $cur_page - 1;
          $msg .= "<li p='$pre' class='active'>Previous</li>";
      } else if ($previous_btn) {
          $msg .= "<li class='inactive'>Previous</li>";
      }
      for ($i = $start_loop; $i <= $end_loop; $i++) {

          if ($cur_page == $i)
              $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
          else
              $msg .= "<li p='$i' class='active'>{$i}</li>";
      }

      // TO ENABLE THE NEXT BUTTON
      if ($next_btn && $cur_page < $no_of_paginations) {
          $nex = $cur_page + 1;
          $msg .= "<li p='$nex' class='active'>Next</li>";
      } else if ($next_btn) {
          $msg .= "<li class='inactive'>Next</li>";
      }

      // TO ENABLE THE END BUTTON
      if ($last_btn && $cur_page < $no_of_paginations) {
          $msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
      } else if ($last_btn) {
          $msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
      }
      $goto = "<input type='text' class='goto' size='1' style='margin-left:30px;height:24px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
      $total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
      $img_loading = "<span ><div id='loading'></div></span>";
      $msg = $msg . "" . $goto . $total_string . $img_loading . "</ul></div>";  // Content for pagination
      echo $msg;
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_load_manageevent_data_front','evntgen_load_manageevent_data_front' );
add_action( 'wp_ajax_evntgen_load_manageevent_data_front', 'evntgen_load_manageevent_data_front' );
function evntgen_check_event_booking(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $hdneventbookingid = $_REQUEST['hdneventbookingid'];
    $event_id = $_REQUEST['event_id'];
    $event_name = $_REQUEST['event_name'];
    $startdate = $_REQUEST['startdate'];
    $starttime = $_REQUEST['starttime'];
    $enddate = $_REQUEST['enddate'];
    $endtime = $_REQUEST['endtime'];
    $noof_seat = $_REQUEST['noof_seat'];
    $noof_ticket = $_REQUEST['noof_ticket'];
    
    $event_noofseat_booked = get_post_meta($event_id, '_event_noofseat_booked', true);
    
    $event_cond = "event_name like '%".$event_name."%'";
    $sql = "";
    if($hdneventbookingid != '' || $hdneventbookingid != NULL ){
      $sql = "select * from ".$table_prefix."evntgen_ustsevents where (".$event_cond.") and 
        startdate >= '".$startdate."' and enddate<= '".$startdate."' and  
        ((starttime > '".$starttime."' and endtime < '".$endtime."') or 
        (endtime > '".$starttime."' and endtime < '".$endtime."') or 
        (starttime > '".$starttime."' and starttime < '".$endtime."') or 
        (starttime < '".$starttime."' and endtime > '".$endtime."') )
        and event_booking_id!=".$hdneventbookingid;
    }
    else{
      $sql = "select * from ".$table_prefix."evntgen_ustsevents where (".$event_cond.") and 
        startdate >= '".$startdate."' and enddate<= '".$startdate."' and 
        ((starttime > '".$starttime."' and endtime < '".$endtime."') or 
        (endtime > '".$starttime."' and endtime < '".$endtime."') or 
        (starttime > '".$starttime."' and starttime < '".$endtime."') or 
        (starttime < '".$starttime."' and endtime > '".$endtime."') )";
    }
    $result = $wpdb->get_results($sql);
    $yesno = "";
    if(count($result)>0 || $event_noofseat_booked >= $noof_seat){
      $yesno .= "yes";	
    }
    else if($event_noofseat_booked < $noof_seat){
      $yesno .= "no";
    }
    echo $yesno;
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_check_event_booking','evntgen_check_event_booking' );
add_action( 'wp_ajax_evntgen_check_event_booking', 'evntgen_check_event_booking' );
function evntgen_save_event_booking(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $hdneventbookingid = $_REQUEST['hdneventbookingid'];
    $hdneventid = $_REQUEST['hdneventid'];
    $event_id = $_REQUEST['event_id'];
    $hdnnoofticket = $_REQUEST['hdnnoofticket'];
    $event_name = $_REQUEST['event_name'];
    $startdate = $_REQUEST['startdate'];
    $starttime = $_REQUEST['starttime'];
    $enddate = $_REQUEST['enddate'];
    $endtime = $_REQUEST['endtime'];
    $noof_seat = $_REQUEST['noof_seat'];
    $noof_ticket = $_REQUEST['noof_ticket'];
    //echo $time_shift; exit;
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $details = $_REQUEST['details'];
    $bookingby = $_REQUEST['bookingby'];
    $price = $_REQUEST['price'];
    $payment_method = $_REQUEST['payment_method'];
    $payment_status = $_REQUEST['payment_status'];
    
    $event_noofseat_booked = get_post_meta($event_id, '_event_noofseat_booked', true);
    $total_noofseat_booked = ($event_noofseat_booked + $noof_ticket);
    
    $values = array(
      'event_name'=>$event_name,
      'event_id'=>$event_id,
      'startdate'=>$startdate,
      'starttime'=>$starttime, 
      'enddate'=>$enddate,  
      'endtime'=>$endtime, 
      'noof_ticket' =>$noof_ticket,
      'first_name'=>$first_name, 
      'last_name'=>$last_name, 
      'email'=>$email, 
      'phone'=>$phone, 
      'details'=>$details, 
      'booking_by'=>$bookingby, 
      'custom_price'=>$price, 
      'payment_method'=>$payment_method,
      'confirmed'=> $payment_status
    );
    if($hdneventid == "" || $hdneventid == NULL){
      $wpdb->insert($table_prefix.'evntgen_ustsevents',$values );
      update_post_meta($event_id, '_event_noofseat_booked', $total_noofseat_booked);
      $inserted_id = $wpdb->insert_id;
      echo $inserted_id;
    }
    else{
      if($hdneventid == $event_id){
        if($hdnnoofticket > $noof_ticket){
          $change = ($hdnnoofticket - $noof_ticket);
          $total_noofseat_booked = ($event_noofseat_booked + $change );
        }
        else if($hdnnoofticket < $noof_ticket){
          $change = ($noof_ticket-$hdnnoofticket);
          $total_noofseat_booked = ($event_noofseat_booked - $change );
        }
      }
      $wpdb->update(
         $table_prefix.'evntgen_ustsevents',
         $values,
         array('event_booking_id' =>$hdneventbookingid)
       );
       update_post_meta($event_id, '_event_noofseat_booked', $total_noofseat_booked);
       echo $hdneventid;
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_save_event_booking','evntgen_save_event_booking' );
add_action( 'wp_ajax_evntgen_save_event_booking', 'evntgen_save_event_booking' );
function evntgen_get_event_bycat(){
  if(isset($_REQUEST)){
    global $table_prefix,$wpdb;
    $term_id = $_REQUEST['term_id'];
    $sql_event = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id=tr.object_id inner join ".$table_prefix."postmeta pm on pm.post_id= p.id where p.post_status = 'publish' and tt.term_id=".$term_id." and pm.meta_key='_event_price'";
    $result = $wpdb->get_results($sql_event);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_get_event_bycat','evntgen_get_event_bycat' );
add_action( 'wp_ajax_evntgen_get_event_bycat', 'evntgen_get_event_bycat' );
function evntgen_save_event_booking_session(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $event_id = $_REQUEST['event_id'];
    $event_name = $_REQUEST['event_name'];
    $event = $_REQUEST['event'];
    $startdate = $_REQUEST['startdate'];
    $starttime = $_REQUEST['starttime'];
    $enddate = $_REQUEST['enddate'];
    $endtime = $_REQUEST['endtime'];
    $noof_seat = $_REQUEST['noof_seat'];
    $noof_ticket = $_REQUEST['noof_ticket'];
    
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $details = $_REQUEST['details'];
    $bookingby = $_REQUEST['bookingby'];
    $price = $_REQUEST['price'];
    $payment_method = $_REQUEST['payment_method'];
    $payment_status = $_REQUEST['payment_status'];

    $values = array(
      'arr_type'=>'raw',
      'carthdneventid'=> $hdneventid, 
      'event_id'=>$event_id,
      'event_name'=>$event_name,  
      'event'=>$event,
      'startdate'=>$startdate,
      'starttime'=>$starttime, 
      'enddate'=>$enddate, 
      'endtime'=>$endtime,
      'noof_ticket'=>$noof_ticket,
      'first_name'=>$first_name, 
      'last_name'=>$last_name, 
      'email'=>$email, 
      'phone'=>$phone, 
      'details'=>$details, 
      'booking_by'=>$bookingby, 
      'custom_price'=>$price, 
      'payment_method'=>$payment_method,
      'confirmed'=>$payment_status  
    );
    $count = 0;
    if(isset($_SESSION['eventcart'])){
      $count = count($_SESSION['eventcart']);
    }
    else{
      $count = 0;
    }
    $_SESSION['eventcart'][$count] = $values;
    echo "added to session";
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_save_event_booking_session','evntgen_save_event_booking_session' );
add_action( 'wp_ajax_evntgen_save_event_booking_session', 'evntgen_save_event_booking_session' );
function evntgen_save_cssfixfront(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $cssfix = $_REQUEST['cssfix'];
    $css = $_REQUEST['css'];
    $isupdate ="";
    if($cssfix == "front"){
      $isupdate = update_option('cssfix_front',$css);
    }
    if($isupdate){
      echo "added";
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_save_cssfixfront','evntgen_save_cssfixfront' );
add_action( 'wp_ajax_evntgen_save_cssfixfront', 'evntgen_save_cssfixfront' );
function evntgen_search_event(){
  global $table_prefix,$wpdb;
  $search_text = $_REQUEST['searchtext'];
  $sql = "select * from ".$table_prefix."evntgen_ustsevents where email='".$search_text."' or phone='".$search_text."' or schedule='".$search_text."' or date='".$search_text."'";
  $result = $wpdb->get_results($sql);
  $msg = "<div id='content_top'></div>";
  if(count($result)){
        $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Schedule</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tr>';
                foreach($result as $event){
                  $msg .= '<tr class="alternate">
                              <td>'.$event->schedule.'</td>
                              <td>'.$event->date.'</td>
                              <td>'.$event->start_time.' '.$event->timeshift.'</td>  
                              <td>'.$event->end_time.' '.$event->timeshift.'</td>
                              <td>'.$event->email.'</td>
                              <td>'.$event->phone.'</td>

                              <td>
                                ';
                  $msg .= '<a href="'.site_url().'/wp-admin/admin.php?page=add-event-menu&calltype=editevent&id='.$event->event_id.'">edit</a>
                                &nbsp;&nbsp;&nbsp;<a id="delete_event" href="#" onclick="return confirm("Are you sure want to delete");">delete</a>
                                <input type="hidden" id="hdneventid"  name="hdneventid" value="'.$event->event_id.'" />
                              </td>
                          </tr>';
                }
                $msg .= '</tr>
                          <tfoot>
                            <tr>
                              <th>Event</th>
                              <th>From Date</th>
                              <th>To Date</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>';	
  }
  else{
    $msg .= '<div style="padding:80px;color:red;">Sorry! No Data Found!</div>';
  }
  $msg = "<div class='data'>" . $msg . "</div>";
  echo $msg;
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_search_event','evntgen_search_event' );
add_action( 'wp_ajax_evntgen_search_event', 'evntgen_search_event' );
function evntgen_load_manageevent_data(){
  if($_POST['page'])
  {
    $page = $_POST['page'];
    $cur_page = $page;
    $page -= 1;
    $per_page = 10;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;
      global $table_prefix,$wpdb;
      $sql = "select * from ".$table_prefix."evntgen_ustsevents ";
      $result_count = $wpdb->get_results($sql);
      $count = count($result_count);
      $sql = $sql.' LIMIT '.$start.', '.$per_page.'';
      $result_page_data = $wpdb->get_results($sql); 
    $msg = "<style type='text/css'>
      /*-----paginations------*/
      #loading{
          width: 50px;
          position: absolute;
          /*top: 100px;
          left: 100px;
          margin-top:200px;*/
          height:50px;
      }
      #inner_content{
         padding: 0 20px 0 0!important;
      }
      #inner_content .pagination ul li.inactive,
      #inner_content .pagination ul li.inactive:hover{
          background-color:#ededed;
          color:#bababa;
          border:1px solid #bababa;
          cursor: default;
      }
      #inner_content .data ul li{
          list-style: none;
          font-family: verdana;
          margin: 5px 0 5px 0;
          color: #000;
          font-size: 13px;
      }

      #inner_content .pagination{
          width: 80%;/*800px;*/
          height: 45px;
      }
      #inner_content .pagination ul li{
          list-style: none;
          float: left;
          border: 1px solid #006699;
          padding: 2px 6px 2px 6px;
          margin: 0 3px 0 3px;
          font-family: arial;
          font-size: 14px;
          color: #006699;
          font-weight: bold;
          background-color: #f2f2f2;

          /*display:inline;
          cursor:pointer;*/
      }
      #inner_content .pagination ul li:hover{
          color: #fff;
          background-color: #006699;
          cursor: pointer;
      }
      .go_button
      {
        background-color:#f2f2f2;
        border:1px solid #006699;
        color:#cc0000;
        padding:2px 6px 2px 6px;
        cursor:pointer;
        position:absolute;
        /*margin-top:-1px;*/
        width:50px;
      }
      .total
      {
        float:right;
        font-family:arial;
        color:#999;
        padding-right:150px;
      }
      #namediv input {
        width:5%!important;
      }
      /*---------media query-------------*/
      /*@media screen and (min-width: 360px) and (max-width:991px){
        #imgproduct{
          width:46%;
        }	
      }*/
      /*---------------------------------*/
    </style>";  
    $msg .= "<div id='content_top'></div>";
    if(count($result_page_data)){
          $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Event</th>
                          <th>Start Date</th>
                          <th>Start Time</th>
                          <th>End Date</th>
                          <th>End Time</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tr>';
                  foreach($result_page_data as $event){
                    $msg .= '<tr class="alternate">
                                <td>'.$event->event_name.'</td>
                                <td>'.$event->startdate.'</td>
                                <td>'.$event->starttime.'</td>  
                                <td>'.$event->enddate.'</td>
                                <td>'.$event->endtime.'</td>  
                                <td>'.$event->email.'</td>
                                <td>'.$event->phone.'</td>

                                <td>
                                  ';
                    $msg .= '<a href="'.site_url().'/wp-admin/admin.php?page=add-event-menu&calltype=editevent&id='.$event->event_id.'">edit</a>
                                  &nbsp;&nbsp;&nbsp;<a id="delete_event" href="#" onclick="return confirm(\'Are you sure want to delete\');">delete</a>
                                  <input type="hidden" id="hdneventid"  name="hdneventid" value="'.$event->event_id.'" />
                                </td>
                            </tr>';
                  }
                  $msg .= '</tr>
                            <tfoot>
                              <tr>
                                <th>Event</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>End Date</th>
                                <th>End Time</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th></th>
                              </tr>
                            </tfoot>
                          </table>';	
    }
    else{
      $msg .= '<div style="padding:80px;color:red;">Sorry! No Data Found!</div>';
    }	
    $msg = "<div class='data'>" . $msg . "</div>"; // Content for Data
    $no_of_paginations = ceil($count / $per_page);
    /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<div class='pagination'><ul>";
    // FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class='active'>First</li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class='inactive'>First</li>";
    }
    // FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='active'>Previous</li>";
    } else if ($previous_btn) {
        $msg .= "<li class='inactive'>Previous</li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {

        if ($cur_page == $i)
            $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
        else
            $msg .= "<li p='$i' class='active'>{$i}</li>";
    }
    // TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='active'>Next</li>";
    } else if ($next_btn) {
        $msg .= "<li class='inactive'>Next</li>";
    }
    // TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
    }
    $goto = "<input type='text' class='goto' size='1' style='margin-left:30px;height:24px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
    $total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
    $img_loading = "<span ><div id='loading'></div></span>";
    $msg = $msg . "" . $goto . $total_string . $img_loading . "</ul></div>";  // Content for pagination
    echo $msg;
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_load_manageevent_data','evntgen_load_manageevent_data' );
add_action( 'wp_ajax_evntgen_load_manageevent_data', 'evntgen_load_manageevent_data' );
function evntgen_activate_event(){
  if ( count($_POST) > 0 ){
    global $table_prefix,$wpdb;
    $eventid = $_REQUEST['event_id'];	
     $values = array('confirmed'=>1);
     $wpdb->update(
           $table_prefix.'evntgen_ustsevent',
           $values,
           array('event_id' =>$eventid)
         );
     echo $eventid;		 
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_activate_event','evntgen_activate_event' );
add_action( 'wp_ajax_evntgen_activate_event', 'evntgen_activate_event' );
function evntgen_delete_event(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $eventid = $_REQUEST['event_id'];	
    $aff_rows = $wpdb->query("delete from ".$table_prefix."evntgen_ustsevents where event_id='".$eventid."'");
    echo $aff_rows;		 
  }  
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_delete_event','evntgen_delete_event' );
add_action( 'wp_ajax_evntgen_delete_event', 'evntgen_delete_event' );
function evntgen_event_operations(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $calltype = $_REQUEST['calltype'];
    if($calltype == 'delete_organizer' ){
        $organizerid = $_REQUEST['organizer_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."evntgen_organizers where id='".$organizerid."'");
        //echo 'here...';
    }
    else if($calltype == 'delete_sponsor' ){
        $sponsorid = $_REQUEST['sponsor_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."evntgen_sponsors where id='".$sponsorid."'");
    }
    else if($calltype == 'delete_venue' ){
        $venueid = $_REQUEST['venue_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."evntgen_venues where id='".$venueid."'");
    }
    else if($calltype == 'delete_schedule' ){
        $scheduleid = $_REQUEST['schedule_id'];	
        $aff_rows = $wpdb->query("delete from ".$table_prefix."evntgen_schedules where id='".$scheduleid."'");
    }
  }
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_event_operations','evntgen_event_operations' );
add_action( 'wp_ajax_evntgen_event_operations', 'evntgen_event_operations' );
function evntgen_get_events_by_schedule(){
  global $table_prefix,$wpdb;
  $room = $_REQUEST['schedule'];
  $sql = "select * from ".$table_prefix."evntgen_ustsevents where schedule like '%".$schedule."%'";
  $result = $wpdb->get_results($sql);
  echo json_encode($result);
  exit;
}
add_action( 'wp_ajax_nopriv_evntgen_get_events_by_schedule','evntgen_get_events_by_schedule' );
add_action( 'wp_ajax_evntgen_get_events_by_schedule', 'evntgen_get_events_by_schedule' );
function evntgen_get_eventprice_by_custompost(){
    if(isset($_REQUEST)){
      //die('end');
      global $table_prefix,$wpdb;
      $post_ids_arr = $_REQUEST['post_ids_arr'];
      $fromdate = $_REQUEST['from_date'];
      $todate = $_REQUEST['to_date'];
      $days = 1;
      $days = sc_howManyDays($fromdate,$todate);
      $price = 0;
      foreach($post_ids_arr as $post_id){
        $sql_event_price = "select * from ".$table_prefix."postmeta where meta_key='_event_price' and post_id=".$post_id;	
        $result = $wpdb->get_results($sql_event_price);
        $price = $price + ($result[0]->meta_value*$days);
      }
      echo $price;
    }
    exit;
}
function sc_howManyDays($startDate,$endDate) {
  $date1  = strtotime($startDate." 0:00:00");
  $date2  = strtotime($endDate." 23:59:59");
  $res    =  (int)(($date2-$date1)/86400);        
  return $res+1;
} 
add_action( 'wp_ajax_nopriv_evntgen_get_eventprice_by_custompost','evntgen_get_eventprice_by_custompost' );
add_action( 'wp_ajax_evntgen_get_eventprice_by_custompost', 'evntgen_get_eventprice_by_custompost' );
function evntgen_get_eventdata_by_custompost(){
  if(isset($_REQUEST)){
      //die('end');
      global $table_prefix,$wpdb;
      $post_ids_arr = $_REQUEST['post_ids_arr'];
      //$fromdate = $_REQUEST['from_date'];
      //$todate = $_REQUEST['to_date'];
      //$days = 1;
      //$days = sc_howManyDays($fromdate,$todate);
      $event_data = Array();
      foreach($post_ids_arr as $post_id){
        $event_startdate = get_post_meta($post_id, '_event_startdate', true);
        $event_starthour = get_post_meta($post_id, '_event_starthour', true);
        $event_startminute = get_post_meta($post_id, '_event_startminute', true);

        $event_enddate = get_post_meta($post_id, '_event_enddate', true);
        $event_endhour = get_post_meta($post_id, '_event_endhour', true);
        $event_endminute = get_post_meta($post_id, '_event_endminute', true);
        
        $event_noofseat = get_post_meta($post_id, '_event_noofseat', true);
        
        $event_data['event_startdate'] = $event_startdate;
        $event_data['event_starthour'] = $event_starthour;
        $event_data['event_startminute'] = $event_startminute;
        
        $event_data['event_enddate'] = $event_enddate;
        $event_data['event_endhour'] = $event_endhour;
        $event_data['event_endminute'] = $event_endminute;
        $event_data['event_noofseat'] = $event_noofseat;
      }
      echo json_encode($event_data);
    }
    exit;
}
add_action( 'wp_ajax_nopriv_evntgen_get_eventdata_by_custompost','evntgen_get_eventdata_by_custompost' );
add_action( 'wp_ajax_evntgen_get_eventdata_by_custompost', 'evntgen_get_eventdata_by_custompost' );
//==================================End Ajax Call ===========================================================
	function evntgen_fullcalendarincludejs(){
    wp_register_script( 'jquery.multiple.select',plugins_url('/multiselect/multiple-select/jquery.multiple.select.js',__FILE__), array( 'jquery' ));
		wp_register_script( 'fullcalendarjs',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.js',__FILE__));
    wp_register_script( 'jquery.bt.min',plugins_url('/tooltip/beautytips-master/jquery.bt.min.js',__FILE__), array( 'jquery' ));
		wp_register_script( 'jscolor',plugins_url('/jscolor/jscolor.js',__FILE__));
    wp_register_script( 'moment.min',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/lib/moment.min.js',__FILE__));
		//wp_register_script( 'gcaljs',plugins_url('/fullcalendar/gcal.js',__FILE__));	
		wp_enqueue_script( 'jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-datepicker');
    
		//wp_enqueue_script( 'jqueryminjs');
		//wp_enqueue_script( 'jqueryuijs');
    wp_enqueue_script( 'jquery.multiple.select');
		wp_enqueue_script( 'fullcalendarjs');
    wp_enqueue_script( 'jquery.bt.min');
		wp_enqueue_script( 'jscolor');
    wp_enqueue_script( 'moment.min');
		//wp_enqueue_script( 'gcaljs');	
	}
	function 	evntgen_fullcalendarincludecss(){
			wp_register_style( 'jquery-ui',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/cupertino/jquery-ui.min.css',__FILE__));
			//wp_register_style( 'jquery-ui',plugins_url('/assets/css/jquery/jquery-ui.css',__FILE__));
      wp_register_style( 'multiple-select',plugins_url('/multiselect/multiple-select/multiple-select.css',__FILE__));
      wp_register_style( 'fullcalendarcss',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.css',__FILE__));
			wp_register_style( 'fullcalendarprintcss',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.print.css',__FILE__));
			wp_register_style( 'jquery.bt',plugins_url('/tooltip/beautytips-master/jquery.bt.css',__FILE__));
      wp_register_style( 'addevent_css',plugins_url('/assets/css/add_event.css',__FILE__));
      wp_register_style( 'add_event_backend',plugins_url('/assets/css/add_event_backend.css',__FILE__));
      //wp_register_style( 'addevent_front_popup',plugins_url('/assets/css/add_event_front_popup.css',__FILE__));
			
			wp_enqueue_style( 'jquery-ui');
      wp_enqueue_style( 'multiple-select');
			wp_enqueue_style( 'fullcalendarcss');
			wp_enqueue_style( 'fullcalendarprintcss');
			wp_enqueue_style( 'jquery.bt');
      wp_enqueue_style( 'addevent_css');
      wp_enqueue_style( 'add_event_backend');
      //wp_enqueue_style( 'addevent_front_popup');
	}
	add_action('admin_enqueue_scripts','evntgen_fullcalendarincludejs');
	add_action('admin_enqueue_scripts','evntgen_fullcalendarincludecss');
  function evntgen_fullcalendarincludejs_front(){
    wp_register_script( 'jquery.multiple.select',plugins_url('/multiselect/multiple-select/jquery.multiple.select.js',__FILE__), array( 'jquery' ));
    wp_register_script( 'fullcalendar',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.js',__FILE__), array( 'jquery' ));
    wp_register_script( 'jquery.bt.min',plugins_url('/tooltip/beautytips-master/jquery.bt.min.js',__FILE__));
    
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-datepicker');
    
    wp_enqueue_script( 'jquery.multiple.select' );
    wp_enqueue_script( 'fullcalendar' );
    wp_enqueue_script( 'jquery.bt.min' );
  }
  function 	evntgen_fullcalendarincludecss_front(){
    //wp_register_style( 'jquery-ui',plugins_url('/assets/css/jquery/jquery-ui.css',__FILE__));
    wp_register_style( 'jquery-ui',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/cupertino/jquery-ui.min.css',__FILE__));
     wp_register_style( 'multiple-select',plugins_url('/multiselect/multiple-select/multiple-select.css',__FILE__));
    wp_register_style( 'fullcalendar',plugins_url('/fullcalendar/fullcalendar-1.6.4/fullcalendar/fullcalendar.css',__FILE__));
    wp_register_style( 'jquery.bt',plugins_url('/tooltip/beautytips-master/jquery.bt.css',__FILE__));
    
    wp_enqueue_style( 'jquery-ui');
    wp_enqueue_style( 'multiple-select');
    wp_enqueue_style( 'fullcalendar');
    wp_enqueue_style( 'jquery.bt');
  }
  add_action('wp_enqueue_scripts','evntgen_fullcalendarincludejs_front');
	add_action('wp_enqueue_scripts','evntgen_fullcalendarincludecss_front');