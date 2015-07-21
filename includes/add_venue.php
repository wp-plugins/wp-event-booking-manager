<script type="text/javascript">
	jQuery(document).ready(function(){
			var calltype = evntgen_getUrlVars()["calltype"];
			if(calltype){
				if(calltype = 'editvenue'){
					<?php
          if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            global $table_prefix,$wpdb;
            $sql = "select * from ".$table_prefix."evntgen_venues where id=".$id;
            $result = $wpdb->get_results($sql);
            ?>
            var venue = <?php echo json_encode($result[0]);?>;
            jQuery('#hdnvenueid').val(venue['id']);
            jQuery('#venue_name').val(venue['venue_name']);
            jQuery('#venue_address').val(venue['venue_address']);
            jQuery('#city').val(venue['city']);
            jQuery('#country').val(venue['country']);
            jQuery('#postal_code').val(venue['postal_code']);
            jQuery('#phone').val(venue['phone']);
            jQuery('#website').val(venue['website']);
          <?php } ?>
				}
			}	
			//
	});
</script>
<?php $country_data = evntpg_currency_table_query();?>
<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2>Venue</h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Add Venue</h3>
        	<form id="frmaddvenue" method="post" action="" novalidate="novalidate">
          	<table style="padding:10px;">
              <tr>
                <td>Venue Name</td>
                <td><input type="text" name="venue_name" id="venue_name" value="" /><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td>Venue Address</td>
                <td><textarea cols="50" rows="5" name="venue_address" id="venue_address"></textarea><span style="color:red;">*</span> </td>
              </tr>
              <tr>
                <td>City</td>
                <td><input type="text" name="city" id="city" value="" /></td>
              </tr>
              <tr>
                <td>Country</td>
                <td>
                  <select name="country" id="country">
                      <option value="">Select</option>
                    <?php
                      foreach($country_data as $country){
                        echo '<option value="'.$country->country.'" '.((get_option('evntpg_base_country')==$country->country) ? 'selected="selected"':'').' >'.$country->country.'</option>';
                      }
                      ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td>Postal Code</td>
                <td><input type="text" name="postal_code" id="postal_code" value="" /></td>
              </tr>
              <tr>
                <td>Phone</td>
                <td><input type="text" name="phone" id="phone" value="" /><span style="color:red;">*</span></td>
              </tr>
              <tr>
                <td>Website</td>
                <td><input type="text" name="website" id="website" value="" /></td>
              </tr>
              <tr><td colspan="2"></td></tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddvenue" name="btnaddvenue" value="Add Venue" style="width:150px;background-color:#0074A2;"/>
                	<input type="hidden" id="hdnvenueid" name="hdnvenueid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
   </div>
  </div>