	jQuery(document).ready(function(){
			//----save appointment----
			jQuery('#frmaddorganizer').on('submit',function(e){
	  		 e.preventDefault();
				 //alert('main function..');
				 evntgen_save_organizer();
			});
			jQuery('#frmaddorganizer_popup').on('submit',function(e){
	  		 e.preventDefault();
				 //alert('call ed');
				 evntgen_save_organizer_popup();
			});
			jQuery('#btnAddorganizer_popup').on('click',function(e){
	  		 e.preventDefault();
				 //alert('call ed');
				 evntgen_save_organizer_popup();
			});
			
			jQuery('#frmaddsponsor').on('submit',function(e){
	  		 e.preventDefault();
				 //alert('main function..');
				 evntgen_save_sponsor();
			})
      jQuery('#btnAddsponsor_popup').on('click',function(e){
	  		 e.preventDefault();
				 evntgen_save_sponsor_popup();
			})
			
			jQuery('#frmaddvenue').on('submit',function(e){
	  		 e.preventDefault();
				 evntgen_save_venue();
			});
      jQuery('#btnAddvenue_popup').on('click',function(e){
	  		 e.preventDefault();
				 evntgen_save_venue_popup();
			});
			jQuery('#frmaddschedule').on('submit',function(e){
				 //alert('called...');
	  		 e.preventDefault();
				 evntgen_save_schedule();
			});
			jQuery('#optschedules').on("change",function(){
				//alert('called....');
				evntgen_set_schedule_session();	
			});
			
	});
	// Read a page's GET URL variables and return them as an associative array.
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
	function evntgen_save_organizer(){
		//alert('save function..1.');
		var hdnorganizer_id = jQuery('#hdnorganizerid').val();
		var organizer_name = jQuery('#organizer_name').val();
		var phone = jQuery('#phone').val();
		var website = jQuery('#website').val();
		var email = jQuery('#email').val();
		if(organizer_name == ""){
			alert('Please input a Organizer Name');
			return;
		}
    else if(phone == ""){
			alert('Please input a Phone Number');
			return;
		}
		/*else if(website == ""){
			alert('Please input Website');
			return;
		}*/
		else if(email ==""){
			alert('Please input Email');
			return;
		}
		jQuery.ajax({
				type: "POST",
						url: scEventAjax.ajaxurl,
						data: {
							action: 'evntgen_add_organizer_ajax_request',	
							hdnorganizerid: hdnorganizer_id, 
							organizer_name: organizer_name, 
							phone: phone, 
							website:website, 
							email: email
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
              jQuery('#organizer_name').val('');
              jQuery('#phone').val('');
              jQuery('#website').val('');
              jQuery('#email').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
		
	}
	function evntgen_save_organizer_popup(){
		var hdnorganizer_id = jQuery('#hdnorganizerid').val();
		var organizer_name = jQuery('#organizer_name').val();
		var phone = jQuery('#starttime').val();
		var website = jQuery('#endtime').val();
		var email = jQuery('#timeinterval').val();
		if(organizer_name == ""){
			alert('Please input a Organizer Name');
			return;
		}
    else if(phone == ""){
			alert('Please input a Phone Number');
			return;
		}
		else if(website == ""){
			alert('Please input Website');
			return;
		}
		else if(email ==""){
			alert('Please input Email');
			return;
		}
		jQuery.ajax({
				type: "POST",
						url: scEventAjax.ajaxurl,
						data: {
							action: 'evntgen_add_organizer_ajax_request',	
							hdnorganizerid: hdnorganizer_id, 
							organizer_name: organizer_name, 
							phone: phone, 
							website:website, 
							email: email
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
							jQuery('.es_popup_overlay').hide();
							jQuery('.es_popup_content').hide();
							//jQuery('#frmaddschedule').submit();
						}
				},
				error : function(s , i , error){
						console.log(error);
				},
				complete: function(data){
					window.location.reload();
				}
		});
	} 
	function evntgen_save_sponsor(){
		//alert('save function..1.');
		var hdnsponsor_id = jQuery('#hdnsponsorid').val();
		//alert(hdnorganizer_id);
		var sponsor_name = jQuery('#sponsor_name').val();
		var phone = jQuery('#phone').val();
		var email = jQuery('#email').val();
		var website = jQuery('#website').val();
    var address = jQuery('#address').val();
    if(sponsor_name == ""){
			alert('Please input a Sponsor Name');
			return;
		}
    else if(phone == ""){
			alert('Please input a Phone Number');
			return;
		}
    else if(address ==""){
			alert('Please input Address.');
			return;	
		}
		
		jQuery.ajax({
				type: "POST",
						url: scEventAjax.ajaxurl,
						data: {
							action: 'evntgen_add_sponsor_ajax_request',	
							hdnsponsorid: hdnsponsor_id, 
							sponsor_name: sponsor_name, 
              phone: phone, 
							email:email, 
							website: website,
							address: address
						},
					success: function (data) {
						if(data.length>0){
							alert('Added Successfully');
              jQuery('#sponsor_name').val('');
              jQuery('#phone').val('');
              jQuery('#email').val('');
              jQuery('#website').val('');
              jQuery('#address').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});

	}
  function evntgen_save_sponsor_popup(){
		var hdnsponsor_id = jQuery('#hdnsponsorid').val();
		var sponsor_name = jQuery('#sponsor_name').val();
		var phone = jQuery('#phone').val();
		var email = jQuery('#email').val();
		var website = jQuery('#website').val();
    var address = jQuery('#address').val();
		if(sponsor_name == ""){
			alert('Please input a Sponsor Name');
			return;
		}
    else if(phone == ""){
			alert('Please input a Phone Number');
			return;
		}
		else if(email == ""){
			alert('Please input Email');
			return;
		}
		else if(website ==""){
			alert('Please input Website.');
			return;	
		}
		else if(address ==""){
			alert('Please input Address.');
			return;	
		}
		jQuery.ajax({
				type: "POST",
						url: scEventAjax.ajaxurl,
						data: {
							action: 'evntgen_add_sponsor_ajax_request',	
							hdnsponsorid: hdnsponsor_id, 
							sponsor_name: sponsor_name, 
              phone: phone, 
							email:email, 
							website: website,
							address: address
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
              jQuery('.es_popup_overlay').hide();
							jQuery('.es_popup_content').hide();
						}
				},
				error : function(s , i , error){
						console.log(error);
				},
				complete: function(data){
					window.location.reload();
				}
		});
	}
	function evntgen_save_venue(){
		var hdnvenue_id = jQuery('#hdnvenueid').val();
		var venue_name = jQuery('#venue_name').val();
		var venue_address = jQuery('#venue_address').val();
		var city = jQuery('#city').val();
    var country = jQuery('#country').val();
    var postal_code = jQuery('#postal_code').val();
    var phone = jQuery('#phone').val();
    var website = jQuery('#website').val();
    
		if(venue_name == ""){
			alert('Please input a Venue Name');
			return;
		}
		else if(venue_address == ""){
			alert('Please input an Address');
			return;
		}
    else if(phone == ""){
			alert('Please input Phone');
			return;
		}
		jQuery.ajax({
				type: "POST",
						url: scEventAjax.ajaxurl,
						data: {
							action: 'evntgen_add_venue_ajax_request',	
							hdnvenueid: hdnvenue_id, 
							venue_name: venue_name, 
							venue_address: venue_address, 
							city: city,
              country: country,
              postal_code: postal_code,
              phone: phone,
              website: website
						},
					success: function (data) {
            console.log(data);
						if(data.length>0){
							alert('added successfully');
              jQuery('#venue_name').val('');
              jQuery('#venue_address').val('');
              jQuery('#city').val('');
              jQuery('#postal_code').val('');  
              jQuery('#phone').val('');
              jQuery('#website').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
		
	}
  function evntgen_save_venue_popup(){
		var hdnvenue_id = jQuery('#hdnvenueid').val();
		var venue_name = jQuery('#venue_name').val();
		var venue_address = jQuery('#venue_address').val();
		var phone = jQuery('#phone').val();
		if(venue_name == ""){
			alert('Please input a Venue Name');
			return;
		}
		else if(venue_address == ""){
			alert('Please input Venue Address');
			return;
		}
    else if(phone == ""){
			alert('Please input Phone');
			return;
		}
		jQuery.ajax({
				type: "POST",
						url: scEventAjax.ajaxurl,
						data: {
							action: 'evntgen_add_venue_ajax_request',	
							hdnvenueid: hdnvenue_id, 
							venue_name: venue_name, 
							venue_address: venue_address, 
							description: description 
						},
					success: function (data) {
						if(data.length>0){
							alert('added successfully');
              jQuery('.es_popup_overlay').hide();
							jQuery('.es_popup_content').hide();
						}
				},
				error : function(s , i , error){
						console.log(error);
				},
				complete: function(data){
					window.location.reload();
				}
		});
		
	}
	function evntgen_save_schedule(){
    //alert('in save schedule');
		var hdnschedule_id = jQuery('#hdnscheduleid').val();
		var schedule_name = jQuery('#schedule_name').val();
		var optorganizer = jQuery('#organizer').val();
		var optsponsor = jQuery('#optsponsor').val();
		var optvenue = jQuery('#optvenue').val();
    if(schedule_name == ""){
      alert('Please Input a Schedule Name');
      return;
    }
    jQuery.ajax({
				type: "POST",
					url: scEventAjax.ajaxurl,
					data: {
						action: 'evntgen_add_schedule_ajax_request',	
						hdnscheduleid: hdnschedule_id, 
						schedule_name: schedule_name, 
						optsponsor: optsponsor, 
						optvenue: optvenue,
						optorganizer: optorganizer
					},
					success: function (data) {
            //alert(data);
						if(data.length>0){
							alert('added successfully');
              jQuery('#schedule_name').val('');
						}
				},
				error : function(s , i , error){
						console.log(error);
				}
		});
	}
	
	function evntgen_set_schedule_session(){
			var scheduleid = jQuery("select[name=optschedules] option:selected").val();
			jQuery.ajax({
					type: "POST",
					url: scEventAjax.ajaxurl,
					data: {
						action: 'evntgen_set_ajax_schedule_session',
						scheduleid : scheduleid
						},
					success: function (data) {
						console.log(data);
					},
					error : function(s , i , error){
						console.log(error);
					},
					complete: function(data){
						jQuery('#frmschedules').submit();
					}
			});
	}