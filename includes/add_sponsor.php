<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = evntgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'editsponsor'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."evntgen_sponsors where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var sponsor = <?php echo json_encode($result[0]);?>;
            jQuery('#hdnsponsorid').val(sponsor['id']);
            jQuery('#sponsor_name').val(sponsor['sponsor_name']);
            jQuery('#phone').val(sponsor['phone']);
            jQuery('#email').val(sponsor['email']);
            jQuery('#website').val(sponsor['website']);
            jQuery('#address').val(sponsor['address']);
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
    <h2>Sponsors</h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Add Sponsors</h3>
        	<form id="frmaddsponsor" method="post" action="" novalidate="novalidate">
          	<table style="padding:10px;">
              <tr>
                <td>Sponsor Name</td>
                <td><input type="text" name="sponsor_name" id="sponsor_name" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td>Phone Number</td>
                <td><input type="text" name="phone" id="phone" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td>Email</td>
                <td><input type="text" name="email" id="email" value="" /> </td>
              </tr>
              <tr>
                <td>Website</td>
                <td><input type="text" name="website" id="website" value="" /> </td>
              </tr>
              <tr>
                <td>Address</td>
                <td>
                  <input type="text" name="address" id="address" value="" /><span style="color:red;">*</span>
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddsponsor" name="btnaddsponsor" value="Add Sponsor" style="width:150px;background-color:#0074A2;"/>
                  <input type="hidden" id="hdnsponsorid" name="hdnsponsorid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
   </div>
  </div>