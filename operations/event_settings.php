<?php 
//define('CCB_PROCESSING_BG_COLOR','7FCA27') ;
//define('evntgen_BOOKED_BG_COLOR','138219') ;
/*$options = array (
								 '_processing_bg_color',
								 '_booked_bg_color'
								 );*/
$options = array (
								 '_booked_bg_color'
								 );

if(isset($_REQUEST['reset'])){
  if($_REQUEST['reset']){
    foreach($options as $opt){
      delete_option ($opt);
      $_POST[$opt]='';
      add_option( $opt, $_POST[$opt] );	
    }
  }
}
								 
if ( count($_POST) > 0 && isset($_POST['savesettings']) ){
	foreach($options as $opt ){
			delete_option ( $opt, $_POST[$opt] );
			add_option ( $opt, $_POST[$opt] );
	}
	
}

//$processing_bg_color = evntgen_scevent_get_opt_val('_processing_bg_color',CCB_PROCESSING_BG_COLOR); 
$booked_bg_color = evntgen_scevent_get_opt_val('_booked_bg_color',EVNTGEN_BOOKED_BG_COLOR); 

?>

<div>
  <div id="icon-link-manager" class="icon32"></div>
  <h2>Settings</h2><br>
  <div id="namediv" class="stuffbox" style="width:45%;min-height:187px;">
		<h3 class="top_bar" style="padding:8px;">Appointment Settings</h3>
    	
      <form id="frmeventsettings" action="" method="post">
      	<table>
        <!--	<tr>
          	<td>Processing Background: </td>
            <td><input class="color" type="text" name="_processing_bg_color" id="_processing_bg_color" value="<//?php echo $processing_bg_color;?>" /></td>
          </tr>-->
          <tr>
          	<td>Booked Background: </td>
            <td><input class="color" type="text" name="_booked_bg_color" id="_booked_bg_color" value="<?php echo $booked_bg_color;?>" /></td>
          </tr>
          <tr>
          	<td></td>
            <td></td>
          </tr>
        </table>
        <div style="float:left;margin-top:17px;padding-left:4px;">
        	<input type="submit" name="savesettings" class="button-primary" style="width:100px;" value="Save Changes" />
        </div>
        <div style="float:left;margin-top:17px;margin-left:5px;">
          <form method="post" action="" >
            <input type="submit" name="reset" class="button-primary" style="width:100px" value="Default Settings" />
          </form>
        </div>
      </form>
      </div>
      
	</div>
<div style="clear:both;"></div>