jQuery(document).ready(function($) {
 
  /** * When user clicks on button... **/

  $('#emp_register_btn').click( function(event) {
 	
  	console.log(' emp_register_btn ');

  	$('.emp_reg_message').html('');
    /**
     * Prevent default action, so when user clicks button he doesn't navigate away from page
     *
     */
    if (event.preventDefault) {
        event.preventDefault();
    } else {
        event.returnValue = false;
    }
 
    // Show 'Please wait' loader to user, so she/he knows something is going on
    // $('.indicator').show();
 
    // If for some reason result field is visible hide it
    // $('.result-message').hide();
 
    // Collect data from inputs
    var reg_nonce 	= $('#emp_registration_form input[name="emp_register_nonce"]').val();
    var reg_user  	= $('#emp_registration_form input[name="emp_user_login"]').val();
    var reg_fname  	= $('#emp_registration_form input[name="emp_user_first"]').val();
    var reg_email  	= $('#emp_registration_form input[name="emp_user_email"]').val();
    var reg_pass  	= $('#emp_registration_form input[name="emp_user_pass"]').val();
    var reg_pass_c  = $('#emp_registration_form input[name="emp_user_pass_confirm"]').val();
 
     
    var ajax_url = emp_reg_vars.emp_ajax_url;
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
        		 } 
        		 $('.emp_reg_message').html('<p>'+response.message+'</p>');
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	 console.log('error jqXHR ');
           console.log(textStatus, errorThrown);
        }
    });


   
 
  });
});