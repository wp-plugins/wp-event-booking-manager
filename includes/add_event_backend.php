<?php
	global $table_prefix,$wpdb;
	$sql_taxonomy = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."terms t on tt.term_id = t.term_id where tt.taxonomy = 'evntgen_custom_category'";
  //die($sql_taxonomy);
	$taxonomies = $wpdb->get_results( $sql_taxonomy );
  //die(print_r($taxonomies));
	$sql_paymentmethod = "select * from ".$table_prefix."evntgen_scevents_paymentmethods";
	$payment_methods = $wpdb->get_results( $sql_paymentmethod );
	?>
<script type="text/javascript">
	jQuery(function() {
		//jQuery( "#dtpfromdate" ).datepicker({ dateFormat: "yy-mm-dd" });
		//jQuery( "#dtptodate" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
	function evntgen_getUrlVars()
	{
			var vars = [], hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for(var i = 0; i < hashes.length; i++)
			{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
			}
			return vars;
	}
	//
	function evntgen_get_events_for_bookingcell(eventid){
		  var term_id = jQuery('#eventtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action:'evntgen_get_event_bycat',
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optevent').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optevent').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optevent').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								evntgen_get_eventprice();
							}
							else{
								jQuery('#optevent').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			}).done(function(msg){
					jQuery('#optevent').val(eventid);
			});
	}
	function evntgen_get_events_for_editbooking(eventid){
		  var term_id = jQuery('#eventtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action:'evntgen_get_event_bycat',
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optevent').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optevent').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optevent').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								evntgen_get_eventprice();
							}
							else{
								jQuery('#optevent').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			}).done(function(msg){
					jQuery('#optevent').val(eventid);
			});
	}
	function evntgen_get_eventprice(){
			var arr_events = new Array();
			//var events_contx = jQuery('#multi_events_select');
			var fromdate = jQuery('#dtpfromdate').val();
			var todate = jQuery('#dtptodate').val();
			
			var ull = jQuery('#multi_events_select ul');
			var slis = jQuery('li.selected', ull);
			slis.each(function(i){
			    var sli = jQuery(this).children().children();
	     		arr_events[i] = sli.attr('value');
			});
      jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
            action: 'evntgen_get_eventdata_by_custompost',
            post_ids_arr : arr_events 
          },
					success: function (data) {
              data = data.trim();
              //console.log(data);
              var eventobj = "";
              if(data.length != 0){
                eventobj = jQuery.parseJSON( data );
              }
              //console.log(eventobj.event_startdate);
							var count = data.length;
              if(arr_events.length != 0){
                jQuery('#dtpfromdate').val(eventobj.event_startdate);
                jQuery('#fromhour').val(eventobj.event_starthour);
                jQuery('#fromminute').val(eventobj.event_startminute);
                
                jQuery('#dtptodate').val(eventobj.event_enddate);
                jQuery('#tohour').val(eventobj.event_endhour);
                jQuery('#tominute').val(eventobj.event_endminute);
                jQuery('#noof_seat').val(eventobj.event_noofseat);
              }
					},
					complete: function (data){
						evntgen_calculate_total_price();
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
            action: 'evntgen_get_eventprice_by_custompost',
            post_ids_arr : arr_events ,from_date: fromdate,to_date: todate
          },
					success: function (data) {
              data = data.trim();
              //console.log(data);
							var count = data.length;
              if(arr_events.length != 0){
                jQuery('#txtCustomPrice').val(data);
                jQuery('#hdnoriginal_ticket_price').val(data);
              }
					},
					complete: function (data){
						evntgen_calculate_total_price();
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
	}
	jQuery(document).ready(function(){
			jQuery('.multiselect').multipleSelect({
				placeholder: '<?php _e("Please select Event","evntgen-ustsbooking"); ?>',
				selectAll: false,
				width:'39%',
				onClick: function(view){
					evntgen_get_eventprice();
				}
			});
      jQuery('#noof_ticket').keyup(function(){
        evntgen_calculate_total_price();
      });
			//-------------------------------------------------
			var calltype = evntgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'editevent'){
					<?php
					if(isset($_REQUEST['id'])){
						$id = $_REQUEST['id'];
						global $table_prefix,$wpdb;
						$sql = "select * from ".$table_prefix."evntgen_scevents where event_id=".$id;
						$result = $wpdb->get_results($sql);
            //die('===>'.$id);
						?>
						var booking = <?php echo json_encode($result[0]);?>;
            jQuery('#hdneventbookingid').val(booking['event_booking_id']);
						jQuery('#hdneventid').val(booking['event_id']);
            jQuery('#hdnnoofticket').val(booking['noof_ticket']);
						//jQuery('#eventtype').val(booking['event_type']);
						var eventids = booking['event_id'].split(",");
						jQuery('select.multiselect').multipleSelect('setSelects', eventids);
						jQuery('#dtpfromdate').val(booking['startdate']);
            var starttime = booking['starttime'];
            var starttimearr = starttime.split(':');
            jQuery('#fromhour').val(starttimearr[0]);
            jQuery('#fromminute').val(starttimearr[1]);
						jQuery('#dtptodate').val(booking['enddate']);
						var endtime = booking['endtime'];
            var endtimearr = endtime.split(':');
            jQuery('#tohour').val(endtimearr[0]);
            jQuery('#tominute').val(endtimearr[1]);
            jQuery('#noof_seat').val(booking['']);
            var total_boofseat = <?php echo get_post_meta($id, '_event_noofseat', true)?>;
            jQuery('#noof_seat').val(total_boofseat);
            jQuery('#noof_ticket').val(booking['noof_ticket']);
            
						jQuery('#txtFirstName').val(booking['first_name']);
						jQuery('#txtLastName').val(booking['last_name']);
						jQuery('#txtEmail').val(booking['email']);
	
						jQuery('#txtPhone').val(booking['phone']);
						jQuery('#details').val(booking['details']);
						jQuery('#txtbookingby').val(booking['booking_by']);
						jQuery('#txtCustomPrice').val(booking['custom_price']);
						jQuery('#optpaymentmethod').val(booking['payment_method']);
						jQuery('#optorderstatus').val(booking['order_status']);
					<?php } ?>	
				}	
			}
			
      //---------------------------------	
			jQuery('#dtpfromdate').on("change",function(){
				evntgen_get_eventprice();
			});
			jQuery('#dtptodate').on("change",function(){
				evntgen_get_eventprice();
			});
			//----save booking----
			jQuery('#frmbooking').on('submit',function(e){
	  		 e.preventDefault();
				 evntgen_save_booking();
			});
			//---------------------------
			<?php if(isset($_REQUEST['calendarcell'])){
				$calendarcell = $_REQUEST['calendarcell'];
				$calendarcell_data = explode("|",$calendarcell);
				$cell_month_cat = $calendarcell_data[0];
				$cell_month = $calendarcell_data[1];
				$cell_date =  $calendarcell_data[2];
			?>
				jQuery("#events_multiselect").multiselect("select",<?php echo $cell_month;?>);
				evntgen_get_eventprice();
				jQuery('#eventtype').val(<?php echo $cell_month_cat;?>);
				evntgen_get_events_for_bookingcell(<?php echo $cell_month;?>);
				jQuery('#dtpfromdate').val('<?php echo $cell_date;?>');
				jQuery('#dtptodate').val('<?php echo $cell_date;?>');  
			<?php }?>
			//--------------------------------
	});
	function evntgen_save_booking(){
      var hdneventbookingid = jQuery('#hdneventbookingid').val();
			var hdneventid = jQuery('#hdneventid').val();
      var hdnnoofticket = jQuery('#hdnnoofticket').val();
      var event_name = jQuery('#events_multiselect :selected').text();
			var eventtype='';
			var eventsarr = jQuery('select.multiselect').multipleSelect('getSelects', 'text');
			var events= "";
			for(var j=0;j<eventsarr.length;j++){
				if(j==0){
					events += eventsarr[j];
				}else{
					events += ","+eventsarr[j];
				}
			}
			var arr_ids = new Array();
			var event_id = '';
			/*-------------*/
			var ull = jQuery('#multi_events_select ul');
			var slis = jQuery('li.selected', ull);
			slis.each(function(i){
			    var sli = jQuery(this).children().children();
	     		arr_ids[i] = sli.attr('value');
				if(i==0){
					event_id += sli.attr('value');	
				}else{
					event_id += ","+sli.attr('value');
				}
			});
      //alert(event_id);
      //return;
			var startdate = jQuery('#dtpfromdate').val();
      var fromhour = jQuery('#fromhour').val();
      var fromminute = jQuery('#fromminute').val();
      var starttime = fromhour +":"+ fromminute;
      
			var enddate = jQuery('#dtptodate').val();
      var tohour = jQuery('#tohour').val();
      var tominute = jQuery('#tominute').val();
			var endtime = tohour + ':' + tominute;
      
      var noof_seat = jQuery('#noof_seat').val();
      var noof_ticket = jQuery('#noof_ticket').val();
			var first_name = jQuery('#txtFirstName').val();
			var last_name = jQuery('#txtLastName').val();
			var email = jQuery('#txtEmail').val();
			var phone = jQuery('#txtPhone').val();
			var details = jQuery('#details').val();
			var bookingby = jQuery('#txtbookingby').val();
			var price = jQuery('#txtCustomPrice').val();
			var payment_method = jQuery('#optpaymentmethod').find('option:selected').val();
			if(events == "<?php _e("Please select Event","evntgen-ustsbooking"); ?>"){
				alert('<?php _e("Please choose at Least a Event .","evntgen-ustsbooking"); ?>');
				return;
			}
			else if(email!=''){
				if(!evntgen_validateEmail(email)){
					alert('<?php _e("Please input a valid email Address.","evntgen-ustsbooking"); ?>');
					return false;
				}
			}
      else if(noof_ticket !=''){
        alert('<?php _e("please input number of Ticket.","evntgen-ustsbooking"); ?>');
				return;
      }
			else if(phone==''){
				alert('<?php _e("please input your phone number.","evntgen-ustsbooking"); ?>');
				return;
			}
      else if(price != ''){
        alert('<?php _e("please input Event Ticket Price.","evntgen-ustsbooking"); ?>');
				return;
      }
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
            action:'evntgen_check_event_booking',
            hdneventbookingid: hdneventbookingid,
            event_id : event_id,
            event_name: event_name,
            event: events,
            startdate:startdate,
            starttime: starttime,
            enddate:enddate,
            endtime: endtime,
            noof_seat: noof_seat,
            noof_ticket : noof_ticket
          },
					success: function (data) {
							data = data.trim();
							if(data=='yes'){
                //alert('in yes');  
								alert('<?php _e("Sorry! Already Booked!","evntgen-ustsbooking"); ?>');
								return;
							}
							else if(data=='no'){
                //alert('in no');  
 								jQuery.ajax({
											type: "POST",
                      url: '<?php echo admin_url( 'admin-ajax.php' );?>',
											data: {
                        action:'evntgen_save_event_booking',
                        hdneventbookingid: hdneventbookingid,
                        hdneventid : hdneventid,
                        event_id: event_id,
                        hdnnoofticket : hdnnoofticket,
                        event_name: event_name,
                        event: events,
                        startdate:startdate,
                        starttime: starttime,
                        enddate:enddate,
                        endtime: endtime,
                        noof_seat: noof_seat,
                        noof_ticket: noof_ticket,
                        first_name:first_name,
                        last_name:last_name,
                        email:email,
                        phone:phone,
                        details: details,
                        bookingby: bookingby,
                        price: price,
                        payment_method: payment_method,
                        payment_status:1
                      },
											success: function (data) {
													if(data.length>0){
														alert('<?php _e("added successfully","evntgen-ustsbooking"); ?>');
													}
											},
											error : function(s , i , error){
													console.log(error);
											}
									});
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
			
	}
	function evntgen_validateEmail(email) {
			var atpos=email.indexOf("@");
			var dotpos=email.lastIndexOf(".");
			if (atpos < 1 || dotpos < atpos+2 || dotpos+2 >= email.length) {
					return false;
			}
			return true;
	}
	function evntgen_calculate_total_price(){
    $total_price = 0;
		$original_price = jQuery('#hdnoriginal_ticket_price').val();
    $howmany_ticket = jQuery('#noof_ticket').val();
    if($howmany_ticket == "" || $howmany_ticket == null ){
      $total_price = ($original_price * 1);
    }
    else{
      $total_price = ($original_price * $howmany_ticket);
    }
		jQuery('#txtCustomPrice').val($total_price); 
	}
  </script>

  <?php $current_user = wp_get_current_user();?>	  
  <div id="addbooking_backend" class="wrapper" style="clear:both;">
  <div class="wrap" style="float:left; width:100%;">
    <div>
      <div style="float:left; padding-top:5px;" class="wp-menu-image dashicons-before dashicons-admin-post"></div>
      <div style="float:left;padding-left:3px;"><h2><?php _e("Hotel Booking","evntgen-ustsbooking"); ?></h2></div>
    </div>
    <div class="main_div" style="clear:both;">
       <div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
            <h3 class="top_bar"><?php _e("Add Booking","evntgen-ustsbooking"); ?></h3>
            <form id="frmbooking" action="" method="post" novalidate="novalidate">
              <table style="margin:10px;width:100%;">
                <tr>
                  <td class="bookinglavel"><?php _e("Event","evntgen-ustsbooking"); ?></td>
                  <td class="bookinginput" id="multi_events_select">
                    <select id="events_multiselect" class="multiselect" multiple="multiple" >
                      <?php foreach($taxonomies as $taxo){?>
                      <option disabled="disabled" value="<?php echo $taxo->name;?>"><?php printf(__("%s","evntgen-ustsbooking"), strtoupper($taxo->name));?></option>
                      <?php 
						$term_id = $taxo->term_id;
						$sql_event = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id=tr.object_id inner join ".$table_prefix."postmeta pm on pm.post_id= p.id where p.post_status = 'publish' and p.post_type='evntgen_custom_event' and tt.term_id=".$term_id." and pm.meta_key='_event_price'";
						 $events = $wpdb->get_results($sql_event);	
						 foreach($events as $event){
						?>
                        	<option value="<?php echo $event->ID;?>"><?php printf(__("%s","evntgen-ustsbooking"), $event->post_title);?></option>
                        <?php } ?>
                      <?php } ?>
                    </select><span style="color:red;">*</span>
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("From Date & Time:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" readonly="readonly" id="dtpfromdate" name="dtpfromdate" value="" style="width:230px;" /><input type="text" readonly="readonly" id="fromhour" name="fromhour" style="width:50px" /><input type="text" readonly="readonly" id="fromminute" name="fromminute" style="width:50px" />
                  </td>
                </tr>
                <tr>
                  <td class="bookinglavel">
                    <?php _e("To Date & Time:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" readonly="readonly" id="dtptodate" name="dtptodate" value="" style="width:230px;" /><input type="text" readonly="readonly" id="tohour" name="tohour" style="width:50px" /><input type="text" readonly="readonly" id="tominute" name="tominute" style="width:50px" />
                  </td>
                </tr>
                <tr>
                  <td>Total Seat/Ticket:</td>
                  <td><input type="text" readonly="readonly" id="noof_seat" name="noof_seat" style="width:230px" /></td>
                </tr>
                <tr>
                  <td>How many Ticket:</td>
                  <td><input type="text" id="noof_ticket" name="noof_ticket" style="width:230px" /><span style="color:red;">*</span></td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("First Name:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" id="txtFirstName" name="txtFirstName" value="" />
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Last Name:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" id="txtLastName" name="txtLastName" value="" />
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Email:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" id="txtEmail" name="txtEmail" value="" /><!--<span style="color:red;">*</span>-->
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Phone:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" id="txtPhone" name="txtPhone" value="" /><span style="color:red;">*</span>
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Details:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <textarea cols="44" rows="15" id="details" name="details"></textarea>
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Booking By:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" readonly="readonly" id="txtbookingby" name="txtbookingby" value="<?php printf(__("%s","evntgen-ustsbooking"), $current_user->display_name); ?>" />
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Price:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <input type="text" id="txtCustomPrice" name="txtCustomPrice" value="" /><span style="color:red;">*</span>
                  </td>
                </tr>
                <tr>
                    <td class="bookinglavel">
                    <?php _e("Payment Method:","evntgen-ustsbooking"); ?>
                  </td>
                  <td class="bookinginput">
                    <select id="optpaymentmethod" name="optpaymentmethod" >
                        <?php foreach($payment_methods as $pm){?>
                        	<option value="<?php echo $pm->payment_method;?>"><?php printf(__("%s","evntgen-ustsbooking"), $pm->payment_method);?></option>
                      	<?php }?>  
                    </select>
                  </td>
                </tr>
                <tr>
                    <td></td>
                  <td>
                  <input type="submit" id="btnaddbooking" name="btnaddbooking" value="<?php _e("Add Booking","evntgen-ustsbooking"); ?>" style="width:150px;cursor: pointer;background-color: #0074A2;"/>
                  <input type="hidden" id="hdneventbookingid" name="hdneventbookingid" value="" style="width:150px;"/>
                  <input type="hidden" id="hdneventid" name="hdneventid" value="" style="width:150px;"/>
                  <input type="hidden" id="hdnnoofticket" name="hdnnoofticket" value="" style="width:150px;"/>
                  <input type="hidden" id="hdnoriginal_ticket_price" name="hdnoriginal_ticket_price" value="0" style="width:150px;"/>
                  </td>
                </tr>
              </table>
            </form>
        </div>
       </div>
    </div>
   </div>
  </div>