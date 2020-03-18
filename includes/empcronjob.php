<?php if ( ! defined( 'ABSPATH' ) ) exit; 



if (!wp_next_scheduled('emp_forex_rates_cron')){
  	wp_schedule_event(time(), 'daily', 'emp_forex_rates_cron');
}



// the CRON hook for firing function
add_action('emp_forex_rates_cron', 'emp_forex_rates_cron_callback');
#add_action('wp_head', 'myplugin_cron_function'); //test on page load

// the actual function
function emp_forex_rates_cron_callback() {
    // see if fires via email notification
    // wp_mail('user@domain.com','Cron Worked', date('r'));
	$forex_data = array();
	$empfohlen_setting_options = get_option('emp_setting');
	
	if(!empty($empfohlen_setting_options['emp_forex']) && !empty($empfohlen_setting_options['emp_forex_api'])){
		// echo "<pre> empfohlen_setting_options "; print_r( $empfohlen_setting_options ); echo "</pre> ";  exit; 
		// echo "<pre> empfohlen_setting_options "; print_r( $empfohlen_setting_options['emp_forex']); echo "</pre> ";  // exit; 
		$currency_list = array_keys(EmpHelper::get_currency_list()); 
		$currency_string = implode(',', $currency_list);

		// echo "<pre> currency_list "; print_r( $currency_list ); echo "</pre> ";
		// echo "<pre>  currency_string "; print_r(  $currency_string ); echo "</pre> ";  

		$forex_data 				= $empfohlen_setting_options['emp_forex'];
		$forex_data['USD'] 	= 1;

    $forex_url 	= 'http://apilayer.net/api/live?access_key='.$empfohlen_setting_options['emp_forex_api'].'&currencies='.$currency_string.'&source=USD&format=1'; 
	  $forex_resp 	= wp_safe_remote_get($forex_url);
	  $forex_body 	= wp_remote_retrieve_body( $forex_resp );
	  $forex_json 	= json_decode( $forex_body );

		if($forex_json->success && !empty($forex_json->quotes)){
		 		foreach ($forex_json->quotes as $qkey => $qvalue) {
		 			 $currency_code = str_replace('USD', '', $qkey); 
		 			 $forex_data[$currency_code] = $qvalue; 
		 			 // echo "<pre> currency_code "; print_r( $currency_code ); echo "</pre> ";  
		 			 // echo "<pre> qkey "; print_r( $qkey ); echo "</pre> ";  
		 			 // echo "<pre> qvalue "; print_r( $qvalue ); echo "</pre> ";  
		 		}

		 		$empfohlen_setting_options['emp_forex'] = $forex_data;

		 		update_option( 'emp_setting', $empfohlen_setting_options );


		 		 $to = get_bloginfo('admin_email');
		     $subject = 'Empohlen Succesfully updating forex rates';
		     $message = "Forex rates succesfully updated throug cron job";

		     $message .= '<p>Base Currency USD = 1 </p>';


		     if(!empty($forex_data)){
		     	foreach ($forex_data as $fkey => $fvalue) {
		     		 $message .= '<p> Currency '.$fkey.' = '.$fvalue.'</p>';
		     	}
		     }

		     wp_mail( $to, $subject, $message);


		 }else{

		 	 $to = get_bloginfo('admin_email');
	     $subject = 'Empohlen Error updating forex rates';
	     $message = "There has been an error while updating forex exchange rates cron job";
	     wp_mail( $to, $subject, $message);
		}
 
}




}





