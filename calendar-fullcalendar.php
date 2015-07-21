<?php 
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
	$sql = "select * from ".$table_prefix."evntgen_scevents";
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
	?>
  <script type="text/javascript">
		function evntgen_submit_form(){
			var event = jQuery('#optevents').val();
			var sel = jQuery("option[value=" + event + "]", jQuery("select[name=optevents]") );
			if (sel.length > 0){
				sel.attr('selected', 'selected');
			}
			var event = jQuery("select[name=optevents] option:selected").text();
		}
	</script>
  <style type='text/css'>
	 #calendar {
			max-width: 800px;
			margin-top: 10px;
		}
		.event {
		}
		.greenEvent {
				background-color:#00FF00;
		}
		.redEvent {
				background-color:#FF0000;
		}
		#wpfooter {
			position:relative;
		}
  </style>
  <div style="height:auto;">
      <div id="icon-options-general" class="icon32">
      </div>
      <h2 style="padding-top:10px;">Event Calendar</h2>
      <div style="height:15px;"></div>
      <div style="padding-left:30px;">
        <!--<div style="float:left;">Events: </div>
        <div style="float:left;">
        <form id="frmevents" method="post">
           <!--<select id="events_multiselect">
             <option value="0">All</option>
              <//?php foreach($taxonomies as $taxo){?>
              <option disabled="disabled" value="<//?php echo $taxo->name;?>">--<//?php printf(__("%s","evntgen-ustsbooking"), strtoupper($taxo->name));?>--</option>
              <//?php 
                $term_id = $taxo->term_id;
                $sql_event = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id=tr.object_id inner join ".$table_prefix."postmeta pm on pm.post_id= p.id where p.post_status = 'publish' and p.post_type='evntgen_custom_event' and tt.term_id=".$term_id." and pm.meta_key='_event_price'";
                $events = $wpdb->get_results($sql_event);	
                foreach($events as $event){
                ?>
                  <option value="<//?php echo $event->ID;?>"><//?php echo "  ".printf(__("%s","evntgen-ustsbooking"), $event->post_title);?></option>
               <//?php } ?>
             <//?php } ?>
           </select>-- >
        </form>
        </div>-->
        <div style="clear:both"></div>
        <div id='calendar' style="clear:both;"></div>
        <div style="clear:both"></div>
      </div>
      <?php include_once('includes/add_event.php');?>
  </div>
  <div style="clear:both;"></div>
  
  <script type='text/javascript'>
	function evntgen_get_events(){
		var event = jQuery('#optevents').val();
		jQuery.ajax({
				type: "POST",
        url: '<?php echo admin_url( 'admin-ajax.php' );?>',
				data: {
          action: 'evntgen_get_events_by_schedule',  
          schedule: schedule
        },
				//dataType:'json',
				success: function (data) {
					console.log(data);
				},
				error : function(s , i , error){
					console.log(error);
				}
				
		});
	}	
		
	function evntgen_generate_calendar(){
		 jQuery('#calendar').fullCalendar({
			header: {
				left: 'prev, next today',
				center: 'title',
				//right: 'month, agendaWeek, agendaDay'
				//right: 'month, agendaWeek, agendaDay'
        right: 'month,basicWeek,basicDay'
			},
			defaultView: 'agendaWeek',
			theme:true,
			selectable: true,
			selectHelper: true,
			editable: true,
			allDayDefault: false,
			dayClick: function(date, allDay, jsEvent, view) {
					 //jQuery('#dtpdate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
					 //jQuery('#dtptodate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
					 jQuery("#addevent_backend_popup").dialog("open");
			},
			events: [
			<?php  //die(print_r($onlyevents_result));
      foreach($onlyevents_result as $event){
        //die(print_r($event));
        //die('==>'.get_post_meta($event->event_id, '_event_noofseat_booked', true));
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
        ?>
				{
					id: <?php echo $event->ID;?>,
					title: '<?php echo $event->post_title.'->'.$starttime.'-'.$endtime.'=>Total Seat:'.$total_seat.'=>Booked:'.$booked; ?>',
					start: '<?php echo $event_startdate.' '.$starttime;?>',
					end: '<?php echo $event_enddate.' '.$endtime;?>',
					backgroundColor : '#<?php echo $booked_bg_color;?>',
					editable: true
          
				},
			  <?php } ?>	
			],
      minTime: <?php echo intval($mintime);?>,
			maxTime: <?php echo intval($maxtime);?>,
			slotMinutes: <?php echo intval($interval);?>,
			eventColor: '#F05133'
		});
	}
	function generate_calendar_on_ajaxcall(events){
		jQuery('#calendar').fullCalendar({
				header: {
					left: 'prev, next today, agenda',
					center: 'title',
					//right: 'month, agendaWeek, agendaDay'
					right: 'month, agendaWeek, agendaDay'
				},
				defaultView: 'agendaWeek',
				theme:true,
				selectable: true,
				selectHelper: true,
				editable: true,
				dayClick: function(date, allDay, jsEvent, view) {
						 //jQuery('#dtpfromdate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
						 //jQuery('#dtptodate').val(jQuery.datepicker.formatDate('yy-mm-dd',date));
						 jQuery("#addevent_backend_popup").dialog("open");
				},
				events: [
				],
				minTime: 0,
				maxTime: 24,
				slotMinutes: 30,
				eventColor: '#F05133'
			});
	}
	jQuery(document).ready(function() {
		
		evntgen_generate_calendar();
		<?php if(isset( $_REQUEST['optevents'])){?>
        jQuery('#optevents').val(<?php echo $_REQUEST['optevents']?>);
    <?php } ?>

    jQuery("#addevent_backend_popup").dialog({
					autoOpen: false,
					height: 600,
					width: 550,
					modal: true,
					buttons: {
							'Book Ticket': function () {
									//jQuery(this).dialog('close');
									if(evntgen_save_booking()){
										jQuery(this).dialog("close");
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
	
 </script>