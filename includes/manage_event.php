<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."evntgen_scevents";
	$events = $wpdb->get_results($sql);
	?>
  <script type="text/javascript">
		jQuery(document).ready(function(){
				//============================= Search Script ========================================
				jQuery('#btnsearchevent').on('click',function(){
						var searchtext = jQuery('#txtsearchevent').val();
						jQuery.ajax
						({
								type: "POST",
                url: '<?php echo admin_url( 'admin-ajax.php' );?>',
								data: {
                  action: 'evntgen_search_event',
                  searchtext: searchtext
                },
								success: function(data)
								{
								},
								error : function(s , i , error){
										console.log(error);
								}
						}).done(function(data){
              data = data.trim();
              evntgen_loading_hide();
              jQuery("#inner_content").html(data);
            });
						
						
				});
				//============================= Pagination Script=====================================
				evntgen_load_moredeals_data(1);
				/*----------------More Deals------------------*/
				function evntgen_load_moredeals_data(page){
						evntgen_loading_show();                    
						jQuery.ajax
						({
								type: "POST",
                url: '<?php echo admin_url( 'admin-ajax.php' );?>',
								data: {
                  action: 'evntgen_load_manageevent_data',  
                  page: page
                },
								success: function(msg)
								{
								}
						}).done(function(msg){
                evntgen_loading_hide();
                jQuery("#inner_content").html(msg);
            });
				
				}
				/*---------------------------------------------*/
				function evntgen_loading_show(){
						jQuery('#loading').html("<img src='<?php echo GENUSTSEVENT_PLUGIN_URL; ?>/images/loading.gif'/>").fadeIn('fast');
				}
				function evntgen_loading_hide(){
						jQuery('#loading').fadeOut('fast');
				}                
				jQuery('#inner_content').delegate('.pagination li.active','click',function(){
						var page = jQuery(this).attr('p');
						//loadData(page);
						evntgen_load_moredeals_data(page);
						jQuery('html, body').animate({
								scrollTop: jQuery("#content_top").offset().top
						}, 1950);
						
				});           
				jQuery('#inner_content').delegate('#go_btn','click',function(){
						var page = parseInt(jQuery('.goto').val());
						var no_of_pages = parseInt(jQuery('.total').attr('a'));
						if(page != 0 && page <= no_of_pages){
								//loadData(page);
								evntgen_load_moredeals_data(page);
								jQuery('html, body').animate({
										scrollTop: jQuery("#content_top").offset().top
								}, 2050);
						}else{
								alert('Enter a PAGE between 1 and '+no_of_pages);
								jQuery('.goto').val("").focus();
								return false;
						}
						
				});
				//=========================== End pagination Script=====================================
				jQuery('#inner_content').delegate('#lnkapprove','click',function(e){
					e.preventDefault();
					var eventid = jQuery(this).parent().children('#hdneventid').val();
					jQuery.ajax({
							type: "POST",
              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
                action: 'evntgen_activate_event',
                event_id:eventid
              },
							success: function (data) {
									var count = data.length;
									if(count>0){
										alert('Appointment Activated');
									}
							},
							error : function(s , i , error){
									console.log(error);
							}
					});
					
				});	
				
				jQuery('#inner_content').delegate('#delete_event','click',function(e){
					e.preventDefault();
          if(!confirm('Are you sure want to deletes')){
            return false;
          }
					var eventid = jQuery(this).parent().children('#hdneventid').val();
					jQuery.ajax({
							type: "POST",
              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
                action: 'evntgen_delete_event',
                event_id:eventid
              },
							success: function (data) {
									var count = data.length;
									if(count>0){
										alert('event Deleted');
									}
							},
							error : function(s , i , error){
									console.log(error);
							}
					});
					console.log(jQuery(this).parent().parent().remove());
				});
					
		});
	</script>
  <style type="text/css">
		#btnsearchevent{
			background:url('<?php echo GENUSTSEVENT_PLUGIN_URL ?>/images/search.png') no-repeat;
			width: 30px; 
			height: 30px; 
			cursor:pointer;
		}
	</style>
	<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    
    <div style="width:50%;float:left;"><h2>Manage Event Booking</h2></div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchevent" id="txtsearchevent" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchevent" name="btnsearchevent" value="" />
      </form>
      <!--<img src="<?php// echo GENUSTSEVENT_PLUGIN_URL ?>/images/search.png" height="20px" width="20px" />-->
    </div>
    
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Event Bookings</h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
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
          <tr>
					<?php
          foreach($events as $event){
          ?>
            <tr class="alternate">
                <td><?php echo $event->event_id;?></td>
                <td><?php echo $event->startdate;?></td>
                <td><?php echo $event->starttime;?></td>
                <td><?php echo $event->enddate;?></td>
                <td><?php echo $event->endtime;?></td>
                <td><?php echo $event->email;?></td>
                <td><?php echo $event->phone;?></td>
                
                <td>
                  <?php //if(!$event->confirmed):?><!--<a id="lnkapprove" href="" > Approve </a>&nbsp;&nbsp;&nbsp;<?php //else :?><span id="" > <b>Approved </b></span>&nbsp;&nbsp;&nbsp;--><?php //endif;?>
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-event-menu&calltype=editevent&id=<?php echo $event->event_id;?>">edit</a>
                  &nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" id="delete_event">delete</a>
                  <input type="hidden" id="hdneventid"  name="hdneventid" value="<?php echo $event->event_id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
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
        </table>
				</div>
				</div>
		  </div>
	  </div>
	 </div>
  </div>
  
  <div id='loading'></div>