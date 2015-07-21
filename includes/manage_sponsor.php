<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."evntgen_sponsors";
	$sponsors = $wpdb->get_results($sql);
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
	jQuery('#inner_content').delegate("#delete_sponsor","click",function(e){
		e.preventDefault();
    if(!confirm('Are you sure want to delete')){
      return false;
    }
		var sponsorid = jQuery(this).parent().children('#hdnsponsorid').val();//jQuery('#hdneventid').val();
		jQuery.ajax({
				type: "POST",
        url: '<?php echo admin_url( 'admin-ajax.php' );?>?calltype=delete_sponsor',
				data: {
          action: 'evntgen_event_operations',
          sponsor_id:sponsorid
        },
				success: function (data) {
						var count = data.length;
						if(count>0){
							alert('Sponsor Deleted');
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
		#btnsearchsponsor{
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
      	Sponsor
        <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=add-sponsors-menu">Add New</a>
    	</h2>
    </div>
    <div style="width:29%;float:left;margin-top:15px;">
    	<form id="frmsearchb" method="post" action="">
      	<input type="text" name="txtsearchsponsor" id="txtsearchsponsor" value="" style="width:250px;height:40px;" />
      	<input type="button" id="btnsearchsponsor" name="btnsearchsponsor" value="" />
      </form>
      <!--<img src="<?php// echo GENUSTSEVENT_PLUGIN_URL ?>/images/search.png" height="20px" width="20px" />-->
    </div>
    
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Manage Sponsor</h3>
				<div id="inner_content">		
        	<div class="data"></div>
			  	<div class="pagination"></div>			
				 <table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
          <thead>
            <tr>
              <th>Sponsor Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Website</th>
              <th>Address</th>
              <th></th>
            </tr>
          </thead>
          <tr>
					<?php
          foreach($sponsors as $sponsor){
          ?>
            <tr class="alternate">
                <td><?php echo $sponsor->sponsor_name;?></td>
                <td><?php echo $sponsor->phone;?></td>
                <td><?php echo $sponsor->email;?></td>
                <td><?php echo $sponsor->website;?></td>
                <td><?php echo $sponsor->address;?></td>
                <td>
                  <a href="<?php echo get_option('siteurl');?>/wp-admin/admin.php?page=add-sponsors-menu&calltype=editsponsor&id=<?php echo $sponsor->id;?>">edit</a>
                  &nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" id="delete_sponsor" >delete</a>
                  <input type="hidden" id="hdnsponsorid"  name="hdnsponsorid" value="<?php echo $sponsor->id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
          </tr>
          <tfoot>
            <tr>
              <th>Sponsor Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Website</th>
              <th>Address</th>
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