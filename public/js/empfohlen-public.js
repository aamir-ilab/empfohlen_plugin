(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	

	 

	 $(document).ready(function(){

	 	console.log(' d ready   ');
	 	
	 	// request button click 
	 	jQuery('body').on('click','.p_request_btn',function(){
	 		console.log(' click  ');

	 		var project_item = jQuery(this).closest('.project_item'); 
	 		var pid = parseInt(jQuery(this).data('pid')); 
	 		console.log(' pid = ', pid);

	 		var data = {
	 			pid: pid,
	 			action : 'project_submit_request'
	 		};

	 		var r = confirm("Are you sure you want to submit request!");
		  if (r == true) {
		    console.log(' confirm true ');
		    // Collect data from inputs			     
		    var ajax_url = emp_vars.emp_ajax_url;
		    // Do AJAX request
		    $.ajax({
		        url: ajax_url,
		       	type: 'POST',
		        dataType: 'json',
		        data: data,
		        // dataType: "json",
		        // contentType: 'application/json; charset=utf-8',
		        success: function (response) {
		        		 // response = jQuery.parseJSON(response);
		        		 console.log('success response = ', response);
		        		 if( response && response.status == 'success') {
		        		 	console.log('response.status = ', response.status);
		        		 	$(project_item).replaceWith(response.row_html);
		        		 } 
		        		 // $('.emp_reg_message').html('<p>'+response.message+'</p>');
		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		        	 console.log('error jqXHR ');
		           console.log(textStatus, errorThrown);
		        }
		    });
		  }  
	 	});
	 	// p_request_btn click end here.  


	 	// project info button click 
	 	jQuery('body').on('click','.p_infoButton,.p_title_expandable,.tick_infoButton,.review_infoButton,.review_title',function(){
	 		console.log(' p_info click '); 
	 		var pid = parseInt(jQuery(this).data('pid')); 
	 		if (jQuery(this).closest('.project_item').length > 0){
	 			jQuery(this).closest('.project_item').toggleClass('show_detail', 5000);
	 		}

	 		if (jQuery(this).closest('.ticket_item').length > 0){
	 			jQuery(this).closest('.ticket_item').toggleClass('show_description', 5000);
	 		}

	 		if (jQuery(this).closest('.review_item').length > 0){
	 			jQuery(this).closest('.review_item').toggleClass('show_description', 5000);
	 		}
	 		
	 	}); 
	 	// p_info button click end 
	 	// 
	 	// add class to body on tab change

		jQuery(function() {
			var loc = window.location.href; // returns the full URL
			// console.log(loc);
			if(/jobs/.test(loc)){
				$('#main').addClass('jobs');

			} else if(/overview/.test(loc)){
			$('#main').addClass('overview');
			}else if(/setting/.test(loc)){
			$('#main').addClass('setting');
			}else if(/pay/.test(loc)){
			$('#main').addClass('pay');
			}
		});
     
		// jQuery('.tabs li').click(function(e) {
		// jQuery(this).addClass('current');
		// //.siblings().removeClass('current');
		// });
          


		// payout_btn click 
    // jQuery('.payout_btn').on('click', function(){
    // 	var currency = jQuery(this).attr('data-currency');
    // 	var current_item = jQuery(this); 

    // 	jQuery(this).prop('disabled', true).html('Processing..');

    // 	console.log(' payout_btn currency ', currency);
    // 	var data = {
	 		// 	currency: currency,
	 		// 	action : 'payout_withdrawl_submit',
	 		// 	security: jQuery('#payout_nonce').val()
	 		// };
    // 	var ajax_url = emp_vars.emp_ajax_url;
		  //   // Do AJAX request
		  //   $.ajax({
		  //       url: ajax_url,
		  //      	type: 'POST',
		  //       dataType: 'json',
		  //       data: data,
		  //       success: function (response) {
		  //       		 console.log('success response = ', response);
		  //       		 if( response && response.status == 'success') {
		  //       		 		jQuery(current_item).replaceWith('<p>'+response.status+' : '+response.message);
		  //       		 }else{
		  //       		 	jQuery(current_item).replaceWith('<p>'+response.status+' : '+response.message);
		  //       		 }
		  //       },
		  //       error: function(jqXHR, textStatus, errorThrown) {
		  //       	 console.log('error jqXHR ');
		  //          console.log(textStatus, errorThrown);
		  //       }
		  //   });
    // });    





     
    jQuery('.p_ticket_form_display').on('click', function(){
    	console.log(' p_ticket_form_display  '); 
    	jQuery('.p_ticket_form').toggleClass('hidden');
    });
	


	 jQuery('.ticket_info').on('click', function(){
    	console.log(' ticket_info  '); 
    	// jQuery('.p_ticket_form').toggleClass('hidden');
    	jQuery(this).closest('.ticket_item').toggleClass('show_description');
    });



    // jQuery('.empfohlen_form_ticket').on('submit',function(event){
    // 	console.log(' empfohlen_form_ticket submit  ');
    // 	event.preventDefault();


    // 	// var ticket_form = new FormData(document.getElementById('empfohlen_form_ticket')); 
    // 	var data = {
    // 		action: 
    // 	}
    // 	console.log(' ticket_form ', ticket_form);

    // 	// var ticket_form = new FormData(jQuery('.empfohlen_form_ticket')); 

    // 	jQuery.ajax({
		  //       url: ajax_url,
		  //      	type: 'POST',
		  //       dataType: 'json',
		  //       data: ticket_form,
		  //       success: function (response) {
		  //       		 console.log('success response = ', response);
		  //       		 // if( response && response.status == 'success') {
		  //       		 // 		jQuery(current_item).replaceWith('<p>'+response.status+' : '+response.message);
		  //       		 // }else{
		  //       		 // 	jQuery(current_item).replaceWith('<p>'+response.status+' : '+response.message);
		  //       		 // }
		  //       },
		  //       error: function(jqXHR, textStatus, errorThrown) {
		  //       	 console.log('error jqXHR ');
		  //          console.log(textStatus, errorThrown);
		  //       }
		  // });


    // });



    // span.onclick = function() { modal.style.display = "none"; }

    jQuery('.close_reg').on('click', function(){
    	jQuery(this).closest('.cmodal').hide();
    });

    jQuery('.close_nofi').on('click', function(){
    	jQuery(this).closest('.cmodal').hide();
    });









    // function to resend email verification code to email for non verified account.  
    jQuery('.resend_v_email_btn').on('click', function(){
    	// var currency = jQuery(this).attr('data-currency');
    	var current_item = jQuery(this); 

    	jQuery(this).prop('disabled', true); 
    	jQuery('.resend_v_email_message').html('Sending Email..');
    	var data = {
	 			action : 'email_verification_resend_submit',
	 			security: jQuery('#email_verification_nonce').val()
	 		};
    	var ajax_url = emp_vars.emp_ajax_url;
		    // Do AJAX request
		    $.ajax({
		        url: ajax_url,
		       	type: 'POST',
		        dataType: 'json',
		        data: data,
		        success: function (response) {
		        		 console.log('success response = ', response);
		        		 if( response && response.status == 'success') {
		        		 		jQuery('.resend_v_email_message').replaceWith('<p>'+response.status+' : '+response.message);
		        		 }else{
		        		 	jQuery('.resend_v_email_message').replaceWith('<p>'+response.status+' : '+response.message);
		        		 }
		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		        	 console.log('error jqXHR ');
		           console.log(textStatus, errorThrown);
		        }
		    });
    }); 





    // enalbel editing on field on setting page 
    jQuery('.editable').on('click', function(){
    	jQuery(this).addClass('edit');
    });


    // enalbel editing on field on setting page 
    jQuery('.reset_password_link').on('click', function(){ console.log(' reset_password_link '); 
    	jQuery(this).closest('.password_reset_box').addClass('edit');
    });


    jQuery('.editable.focus_close input').on('focusout', function(){
    	 console.log(' focus out ' + jQuery(this).val());
    	 
    	 jQuery(this).closest('.editable').find('span').html(jQuery(this).val());

    	 jQuery(this).closest('.editable').removeClass('edit');
    });





    // add review button click  
    jQuery('.add_review_btn').on('click', function(){
    	console.log(' add_review_btn '); 
    	jQuery(this).closest('.project_item').find('.review_submit').toggleClass('hidden');
    });

    // remove the selected file when click 
    jQuery('.empfohlen_form_review').on('click', '.removeSelectedFile',function(){
    	jQuery(this).closest('.additional_info_edit').remove();
    });


    // add new upload file field on click 
    jQuery('.add_review_file').on('click', function(){
    	var new_file_select  = '<div class="additional_info_edit review_files_upload mt_20 mb_20">'; 
    			new_file_select += 	'<input class="file-upload-field" type="file" name="review_files[]">'; 
    			new_file_select += '</div>';

    	jQuery(this).closest('.review_files_upload_cont').prepend(new_file_select);
    }); 

    // function to append the file infor preview when selected  
    jQuery('.empfohlen_form_review').on('change', 'input[name="review_files[]"]', function(e){  // })
    // jQuery('input[name="review_files[]"]').change(function(e){
    	
    	jQuery(this).closest('.additional_info_edit').addClass('has_selected_file');
    	
    	console.log(' review_files chagne ');
    	console.log(' e.target ', e.target.files);
    	var fileName = e.target.files[0].name;

    	var fileSize = (e.target.files[0].size / 1024);
    			fileSize = (Math.round(fileSize * 100) / 100);
    	
    	var fileType = e.target.files[0].type; 

    	console.log( ' emp_vars ', emp_vars ); 

    	var file_select_html = '<div class="file_wrap">'; 
    	file_select_html  	+= '	<div class="file-icon">'; 
    	file_select_html  	+= '		<img src="'+emp_vars.empfohlen_uri+'images/icons8-file.svg">'; 
    	file_select_html  	+= '	</div>'; 
    	file_select_html  	+= '	<div class="file-info">'; 
    	file_select_html  	+= '		<p><strong>File Name: </strong> '+fileName+' </p>';
    	file_select_html  	+= '		<p><strong>File Type:</strong> '+fileType+' </p>'; 
    	file_select_html  	+= '		<p><strong>File size:</strong> '+fileSize+' KB </p>'; 
    	file_select_html  	+= '	</div>'; 

    	file_select_html  	+= '	<div class="file_action">'; 
    	file_select_html  	+= '		<a class="cancel_icon removeSelectedFile">&#10005;</a>';
    	file_select_html  	+= '	</div>'; 

    	file_select_html  	+= '</div>'; 

    	jQuery(this).closest('.additional_info_edit').append(file_select_html);

    });




    // function to submit payout withdrawl form. 
    jQuery('.withdrawl_btn').on('click', function(){
    	console.log(' withdrawl_btn '); 

    	jQuery('.payout_withdrawl_body .info_box').html('');

    	var total_user_price =  parseInt(jQuery('#total_user_price').val());
    	var payout_amount =  parseInt(jQuery('#payout_amount').val());
    	var payout_description =  jQuery('#payout_description').val();

    	console.log(' total_user_price ', total_user_price);
    	console.log(' payout_amount ', payout_amount);

    	if( payout_amount > total_user_price ){

    		var info_message  = '<div id="top-alert" class="cmodal relative alert alert-danger alert-dismissible mt_20" role="alert">'; 
    				info_message += '<div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>'; 
    				info_message += '<strong>Error ! </strong> You can  withdrawl maximun '+total_user_price+'  amount '; 
    				info_message += '</div>'; 

    		jQuery('.payout_withdrawl_body .info_box').html(info_message);

    		return false; 
    	}


    	var info_message  = '<div id="top-alert" class="cmodal relative alert alert-danger alert-dismissible mt_20" role="alert">'; 
  				info_message += '<div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>'; 
  				info_message += 'Processing your request please wait... '; 
  				info_message += '</div>'; 

  		jQuery('.payout_withdrawl_body .info_box').html(info_message);
  		jQuery('.payout_withdrawl_body .payment_info').addClass('hidden');


    // 	var current_item = jQuery(this); 
    // 	jQuery(this).prop('disabled', true).html('Processing..');
    // 	console.log(' payout_btn currency ', currency);
     	var data = {
     		payout_amount: payout_amount,
     		payout_description: payout_description,
	 			action : 'payout_withdrawl_submit',
	 			security: jQuery('#payout_nonce').val()
	 		};
    	var ajax_url = emp_vars.emp_ajax_url;
		    // Do AJAX request
		    $.ajax({
		        url: ajax_url,
		       	type: 'POST',
		        dataType: 'json',
		        data: data,
		        success: function (response) {
		        		 console.log('success response = ', response);
		        		 if( response && response.status == 'success') {
		        		 		 var info_message = '<div class="cmodal relative alert alert-success alert-dismissible mt_20" style="background-color: #8bc34ad4;color: white;font-weight: bold;">'; 
					  				info_message 	+= '<div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>'; 
					  				info_message 	+= '<strong><p>'+response.status+' : '+response.message+'.</strong> '; 
					  				info_message 	+= '</div>'; 
					  				jQuery('.payout_withdrawl_body .info_box').html(info_message);
		        		 }else{
		        		 	var info_message = '<div id="top-alert" class="cmodal relative alert alert-danger alert-dismissible mt_20" role="alert">'; 
				  				info_message 	+= '<div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>'; 
				  				info_message 	+= '<strong><p>'+response.status+' : '+response.message+'.</strong> ';  
				  				info_message 	+= '</div>'; 
				  				jQuery('.payout_withdrawl_body .info_box').html(info_message);
		        		 }
		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		        	console.log('error jqXHR ');
		          console.log(textStatus, errorThrown);
		          var info_message = '<div id="top-alert" class="cmodal relative alert alert-danger alert-dismissible mt_20" role="alert">'; 
		  				info_message 	+= '<div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>'; 
		  				info_message 	+= '<strong>Error creating withdrawl request.</strong> '; 
		  				info_message 	+= '</div>'; 
		  				jQuery('.payout_withdrawl_body .info_box').html(info_message);
		        }
		    });

    });


	 });// doc ready end 


})( jQuery );
