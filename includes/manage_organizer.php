<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."evntgen_organizers";
	$organizers = $wpdb->get_results($sql);
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
    jQuery('#inner_content').delegate("#delete_organizer","click",function(e){
      e.preventDefault();
      if(!confirm('Are you sure want to delete')){
        return false;
      }
      var organizerid = jQuery(this).parent().children('#hdnorganizerid').val();//jQuery('#hdneventid').val();
      jQuery.ajax({
          type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>?calltype=delete_organizer',
          data: {
            action: 'evntgen_event_operations',
            organizer_id:organizerid
          },
          success: function (data) {
              var count = data.length;
              if(count>0){
                alert('Organizer Deleted');
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
    
    <div style="width:50%;float:left;">
    	<h2>
      	Organizer
        <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=add-organizer-menu">Add New</a>
    	</h2>
    </div>
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
        <h3 class="top_bar">Manage Organizer</h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th>Organizer Name</th>
              <th>Phone</th>
              <th>Website</th>
              <th>Email</th>
              <th>Operations</th>
            </tr>
          </thead>
          <tr>
					<?php
          foreach($organizers as $organizer){
          ?>
            <tr class="alternate">
                <td><?php echo $organizer->organizer_name;?></td>
                <td><?php echo $organizer->phone;?></td>
                <td><?php echo $organizer->website;?></td>
                <td><?php echo $organizer->email;?></td>
                
                <td>
                  <!-- <a href="<?php //echo get_option('siteurl');?>/wp-admin/edit.php?post_type=custom_event&page=add-organizer-menu&calltype=editorganizer&id=<?php //echo $organizer->id;?>">edit</a> -->
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-organizer-menu&calltype=editorganizer&id=<?php echo $organizer->id;?>">edit</a>
                  &nbsp;&nbsp;&nbsp;<a id="delete_organizer" style="cursor:pointer;" >delete</a>
                  <input type="hidden" id="hdnorganizerid"  name="hdnorganizerid" value="<?php echo $organizer->id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          </tr>
          <tfoot>
            <tr>
              <th>Organizer Name</th>
              <th>Phone</th>
              <th>Website</th>
              <th>Email</th>
              <th>Operations</th>
            </tr>
          </tfoot>
        </table>
				</div>
				</div>
		  </div>
	  </div>
	 </div>
  </div>