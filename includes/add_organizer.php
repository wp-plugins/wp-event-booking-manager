<style type="text/css">
	.organizer_note{
		font-style:italic;
		font-size:12px;
	}
  .asterisk{
    width:20px;
  }
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = evntgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'editorganizer'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."evntgen_organizers where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var organizer = <?php echo json_encode($result[0]);?>;
            console.log(organizer);
            //console.log(jQuery('#hdnorganizerid').val(3));
            jQuery('#hdnorganizerid').val(organizer['id']);
            //alert(jQuery('#organizer_name'));
            jQuery('#organizer_name').val(organizer['organizer_name']);
            jQuery('#phone').val(organizer['phone']);
            jQuery('#website').val(organizer['website']);
            jQuery('#email').val(organizer['email']);
          <?php } ?>  
				}
			}	
			//
	});
	
</script>
<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2>Organizer</h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Add Organizer</h3>
            <form id="frmaddorganizer" method="post" action="" novalidate="novalidate">
            <table style="padding:10px;" >
              <tr>
                <td>Organizer Name</td>
                <td><input type="text" name="organizer_name" id="organizer_name" value="" /> </td>
                <td class="asterisk"><span style="color:red;">*</span></td>
              </tr>
              <tr>
                <td>Phone</td>
                <td>
                	<input type="text" name="phone" id="phone" value="" />
                </td>
                <td class="asterisk"><span style="color:red;">*</span></td>
              </tr>
              <tr>
                <td>Website</td>
                <td>
                	<input type="text" name="website" id="website" value="" />
                </td>
                <td class="asterisk"></td>
              </tr>
              <tr>
                <td>Email</td>
                <td>
                	<input type="text" name="email" id="email" value="" />
                </td>
                <td class="asterisk"><span style="color:red;">*</span></td>
              </tr>
              <tr><td colspan="2"></td></tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddorganizer" name="btnaddorganizer" value="Add Organizer" style="width:150px;background-color:#0074A2;"/>
                  <input type="hidden" id="hdnorganizerid" name="hdnorganizerid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
            </form>
				</div>
      </div>
    </div>
   </div>
  </div>