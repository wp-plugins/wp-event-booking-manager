<?php  
  function evntgen_ustscalendar_shortcode($atts){ 
	//if ( is_user_logged_in() ){
		global $table_prefix,$wpdb;
  $sql_taxonomy = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."terms t on tt.term_id = t.term_id where tt.taxonomy = 'evntgen_custom_category' ORDER BY tt.term_id ASC";
	$taxonomies = $wpdb->get_results( $sql_taxonomy );
  //die(print_r($taxonomies));
  $sql_onlyevents = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id=tr.object_id inner join ".$table_prefix."postmeta pm on pm.post_id= p.id where p.post_status = 'publish' and p.post_type='evntgen_custom_event' and pm.meta_key='_event_price'";
  $onlyevents_result = $wpdb->get_results($sql_onlyevents);
  
  $event = "";
  if(isset($_SESSION['event_id'])){
    $event = $_SESSION['event_id'];
  }
	
	if($_POST){
		$event = $_REQUEST['optevents'];
	}
	$sql = "";
	$sql_event = "";
	$events_result = "";
	$mintime = 0;
	$maxtime = 24;
	$interval = 30;
	//if($event==0){
	$sql = "select * from ".$table_prefix."evntgen_ustsevents";
		//echo '<br >its all';
		//$mintime = 0;
	  //$maxtime = 24;
	  //$interval = 30;
	//}
	/*else{
		$sql = "select * from ".$table_prefix."evntgen_scevents where event_id like '%".$event."%'";
		//echo '<br> other options';
		/*$sql_event = "select scd.id as scheduleid,srv.id as serviceid,tmsl.id as timeslotid,vn.id as venueid, scd.* ,srv.*,tmsl.*,vn.* from ".$table_prefix."evntgen_events scd inner join ".$table_prefix."evntgen_services srv on scd.service = srv.id 
inner join ".$table_prefix."evntgen_timeslot tmsl on tmsl.id = scd.timeslot
inner join ".$table_prefix."evntgen_venues vn on vn.id = scd.venue 
where scd.id=".$schedule;* /
		$events_result = $wpdb->get_results($sql_schedule);
		// $mintime = $tvs_result[0]->mintime;
		//$maxtime = $tvs_result[0]->maxtime;
		//$interval = $tvs_result[0]->time_interval;	
	}*/
	
	$sceventsbooking = $wpdb->get_results($sql);
	//==========day calculation=============
  $booked_bg_color = evntgen_scevent_get_opt_val('_booked_bg_color',EVNTGEN_BOOKED_BG_COLOR);
  
		$output = "<style type='text/css'";
    include_once GENUSTSEVENT_DIR.'operations/get_cssfixfront.php';
		$output .= '</style><script type="text/javascript">
			function evntgen_submit_form(){
				var schedule = jQuery("#optevents").val();
				var sel = jQuery("option[value=" + event + "]", jQuery("select[name=optevents]") );
				if (sel.length > 0){
					sel.attr("selected", "selected");
				}
				var event = jQuery("select[name=optevent] option:selected").text();
				//jQuery("#frmschedules").submit();
			}
		</script>
		<style type="text/css">
				#calendar {
					/*width: 800px;*/
					/*margin: 0 auto;*/
					/*margin: 30px;*/
					}
					.event {
						/*shared event css*/
					}
					.greenEvent {
							background-color:#00FF00;
					}
					.redEvent {
							background-color:#FF0000;
					}
					table{
						margin:0!important;
					}
				</style>
				
				<div style="">
						<div style="clear:both"></div>
						<div id="calendar"></div>
						<div style="clear:both"></div>
      	</div>
				';
				
				//================================================================ Add event Popup ================================================================================
				include_once('add_event_front_popup.php');	
				//================================================================================================================================================
				$output .= "<script type='text/javascript'>
				function evntgen_generate_calendar(){
					jQuery('#calendar').fullCalendar({
						header: {
							left: 'prev, next today, agenda',
							center: 'title',
							right: 'month,agendaWeek,agendaDay'
						},
						defaultView: 'agendaWeek',
						theme:true,
						selectable: true,
						selectHelper: true,
						editable: true,
						allDayDefault: false,
						dayClick: function(date, allDay, jsEvent, view) {
								//jQuery('#dtpdate').val(jQuery.datepicker.formatDate('yy-mm-dd',date)); 
                jQuery('#addeventbooking_frontdialog').dialog('open');
						},
						events: [";
						foreach($onlyevents_result as $event){
              $event_startdate = get_post_meta($event->ID, '_event_startdate', true);
              $event_starthour = get_post_meta($event->ID, '_event_starthour', true);
              $event_startminute = get_post_meta($event->ID, '_event_startminute', true);
              $starttime = $event_starthour.':'.$event_startminute;

              $event_enddate = get_post_meta($event->ID, '_event_enddate', true);
              $event_endhour = get_post_meta($event->ID, '_event_endhour', true);
              $event_endminute = get_post_meta($event->ID, '_event_endminute', true);
              $endtime = $event_endhour.':'.$event_endminute;
              $total_seat = get_post_meta($event->ID, '_event_noofseat', true);
              $booked = get_post_meta($event->ID, '_event_noofseat_booked', true);
        
						$output .="
                    {
                      id: '".$event->ID."',
                      title: ' ".$event->post_title."->".$starttime."-".$endtime."-> Total Seat:".$total_seat."-> Booked:".$booked."', 
                      start: '".$event_startdate." ".$starttime."',
                      end: '".$event_enddate." ".$endtime."',
                      backgroundColor : '#".$booked_bg_color."',
                      editable: true
                      
                    },";
						}	
						$output .="],
						minTime:".intval($mintime).",
						maxTime:".intval($maxtime).",
						slotMinutes:".intval($interval).",
						eventColor: '#F05133'
					});
				}
				jQuery(document).ready(function() {
						evntgen_generate_calendar();";
            if(isset($_REQUEST["optschedules"])){
              $output .="jQuery('#optschedules').val('".$_REQUEST["optschedules"]."');";
            }
						$output .="jQuery('#addeventbooking_frontdialog').dialog({
								autoOpen: false,
								height: 603,
								width: 550,
								modal: true,
								buttons: {
										'Book Ticket': function () {
												if(evntgen_save_booking()){
													jQuery(this).dialog('close');
												}
												else{
												}
										},
										Cancel: function () {
												jQuery(this).dialog('close');
												evntgen_cleardata();
										}
								},
				
								close: function () {
									evntgen_cleardata();
								}
				
						});
						
					});
				</script>";

			return $output;		
	
	}
	add_shortcode('evntgen_ustscalendar','evntgen_ustscalendar_shortcode');