(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	 console.log(' gt ', jQuery('.generate_task'));

	  jQuery(document).ready(function() {
		  
	  	// generate task 
		  jQuery('.generate_task').on('click',function(event){
			 	event.preventDefault();
			 	console.log(' Generating task ');

			 	var current_button =  jQuery(this);
			 	jQuery(current_button).html('Processing..');


			 	var rid = jQuery(this).attr('data-rid');
			 	console.log(' rid ', rid); 

			 	console.log(' ajaxurl  ', ajaxurl )
			 	var data = { 
			 			action: 'generate_task',
			 			rid: rid 
			 		}


			 	jQuery.ajax({
	        url: ajaxurl,
	       	type: 'POST',
	        dataType: 'json',
	        data: data,
	        success: function (response) {
	        		 		// response = jQuery.parseJSON(response);
	        		 		console.log('success response = ', response);
	        		 if( response && response.status == 'success') {
	        		 		console.log('response.status = ', response.status);
	        		 		if(response.data){ jQuery(current_button).replaceWith(response.data);	 }
	        		 		location.reload();
	        		 }else if(response && response.status == 'error'){
	        		 		alert('Error: '+response.message);
	        		 		if(response.data){ jQuery(current_button).replaceWith(response.data);	 }
	        		 }else{
	        		 		alert('Error Creating Task');
	        		 }
	        		 // $('.emp_reg_message').html('<p>'+response.message+'</p>');
	        },
	        error: function(jqXHR, textStatus, errorThrown) {
	        	 console.log('error jqXHR ');
	           console.log(textStatus, errorThrown);
	        }
	    });

			}); // generate_task end here



			// Generate Invoice and make the task complete 
		  jQuery('.generate_task_invoice').on('click',function(event){
			 	event.preventDefault();
			 	
			 	console.log(' Generating invoice ', jQuery(this));

			 	var tid = jQuery(this).attr('data-tid');
			 	console.log(' tid ', tid); 

			 	var current_button =  jQuery(this);
			 	jQuery(current_button).html('Processing..');

				jQuery.confirm({
						boxWidth: '30%',
				    title: 'Confirmation?',
				    content: 'Are you sure to Complete task and generate invoice',
				    type: 'green',
				    buttons: {   
				        ok: function(){

				        	var data = { 
							 			action: 'task_complete_generate_invoice',
							 			tid: tid 
							 		}
							 		
								 	jQuery.ajax({
						        url: ajaxurl,
						       	type: 'POST',
						        dataType: 'json',
						        data: data,
						        success: function (response) {
				        		 	 // response = jQuery.parseJSON(response);
				        		 		console.log('success response = ', response);
					        		 if( response && response.status == 'success') {
					        		 		console.log('response.status = ', response.status);
					        		 		if(response.data){ jQuery(current_button).replaceWith(response.data);	 }
					        		 		location.reload();
					        		 }else if(response && response.status == 'error'){
					        		 		alert('Error: '+response.message);
					        		 		if(response.data){ jQuery(current_button).replaceWith(response.data);	 }
					        		 }else{
					        		 		alert('Error Creating Task');
					        		 }
					        		 $('.emp_reg_message').html('<p>'+response.message+'</p>');
						        },
						        error: function(jqXHR, textStatus, errorThrown) {
						        	 console.log('error jqXHR ');
						           console.log(textStatus, errorThrown);
						        }
						    });

				        },
				        cancel: function(){
				                console.log('the user clicked cancel');
				        }
				    }
				}); // confirm end here


  
 
			 	

			}); // generate_task end here





	 }); // doc ready 

	


})( jQuery );
