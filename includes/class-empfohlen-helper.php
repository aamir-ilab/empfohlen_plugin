<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.creativetech-solutions.com
 * @since      1.0.0
 *
 * @package    Empfohlen
 * @subpackage Empfohlen/includes
 */

 
class EmpHelper {
 
	public function __construct() {}

	static function test(){ echo "EmpHelper test "; }

	static function is_duration_passed_away($date, $duration){
		

		 // echo "<pre> is_duration_passed_away date = "; print_r( $date ); echo "</pre> ";  
		 // echo "<pre> is_duration_passed_away duration = "; print_r( $duration ); echo "</pre> ";  

		 $exp_date = new DateTime($date);
		 $now = new DateTime();

		 if(empty($duration)){ return ''; }
		 $duration_array =  explode(':', $duration);

		 // echo "<pre> exp_date "; print_r(  $exp_date ); echo "</pre> ";  

		 if (!empty($duration_array[0])  && ($duration_array[0] > 0)) {
		 		$hoursToAdd =  (int) $duration_array[0]; 
		 		$exp_date->add(new DateInterval("PT{$hoursToAdd}H"));
		 }

		 if (!empty($duration_array[1])  && ($duration_array[1] > 0)) {
		 		$minutsToAdd = (int) $duration_array[1]; 
		 		$exp_date->add(new DateInterval("PT{$minutsToAdd}M"));
		 }

		 if (!empty($duration_array[2])  && ($duration_array[2] > 0)) {
		 		$secondsToAdd = (int) $duration_array[2]; 
		 		$exp_date->add(new DateInterval("PT{$secondsToAdd}S"));
		 }

		  // echo "<pre>exp_date after duration added   "; print_r( $exp_date ); echo "</pre> ";  
		  // echo "<pre>duration_array  "; print_r( $duration_array ); echo "</pre> ";  


 
			if($exp_date < $now) {
				return false; 
			}else{
				return true; 
			}



 

 

	}
		



	// // function to convert date to readable hours minuts seconds format. 
	// static function duration_to_readable($duration){

	// }


	// function to convert date to readable hours minuts seconds format. 
	static function duration_to_readable($duration){
		if(empty($duration)){ return ''; }
 		$duration_array =  explode(':', $duration);

 		// echo "<pre> duration_to_readable "; print_r( $duration ); echo "</pre> ";  
 		// echo "<pre> duration_array "; print_r( $duration_array  ); echo "</pre> ";  

 		$ret = '<span class="p_duration">';
 			if (!empty($duration_array[0])  && ($duration_array[0] >0)) { 
 				$hour = (int) $duration_array[0]; 
 				$ret .= '<span class="hour">';
 				$ret .=	($hour > 1)?($hour.__(' hours ', 'empfohlen')):($hour.__(' hour', 'empfohlen'));
 				$ret .= '</span>';
 			}
 			if (!empty($duration_array[1])  && ($duration_array[1] >0)) { 
 				$minute = (int) $duration_array[1]; 
 				$ret .= '<span class="minute">';
 				$ret .=	($minute > 1)?($minute.__(' minutes ', 'empfohlen')):($minute.__(' minute', 'empfohlen'));
 				$ret .= '</span>';
 			}
 			if (!empty($duration_array[2])  && ($duration_array[2] >0)) { 
 				$second = (int) $duration_array[2]; 
 				$ret .= '<span class="second">';
 				$ret .=	($second > 1)?($second.__(' seconds ', 'empfohlen')):($second.__(' second', 'empfohlen'));
 				$ret .= '</span>';
 			}
 		$ret .= '</span>';
 		return $ret;

	}





	static function project_expiration_date_label($date){

		if(empty($date)){ return ''; }


		// $today      = new DateTime('now');
		// $p_date      = new DateTime($date);
		// $difference = $today->diff($p_date);
		// echo $difference->format('%h hours %i minutes %s seconds until tomorrow');
		// echo "<pre> time "; print_r( time() ); echo "</pre> ";  

		 // var_dump($date);
			$exp_date = new DateTime($date);
			$now = new DateTime();
			
			// echo "<pre> date "; print_r( $date ); echo "</pre> ";  
			//echo "<pre> exp_date "; print_r( $exp_date ); echo "</pre> ";  
			// echo $exp_date->date;

			// $exp_date = new \DateTime($date);
			// $test_date = $exp_date->date;
			// echo ' test_date '.$test_date;

			$ret = '<div class="exp_duration">';
			 if($exp_date < $now) {
			 		$ret  .= '<span class="d_expired">'.__('Expired: ', 'empfohlen').EmpHelper::Expired_humanTiming($exp_date).' '.__('ago', 'empfohlen').'</span>';
			 }else{
			 		$ret  .= '<span class="d_left">'.__('Time Left: ', 'empfohlen').EmpHelper::Left_humanTiming($exp_date).' '.__('remaning', 'empfohlen').'</span>';
			 }
			$ret .= '</div>';

			// echo $ret;

		 return $ret;
	}




// convert date time to human timing string (1 day ago)
function Expired_humanTiming ($dateTime){
		
	 // echo "<pre> dateTime "; print_r( $dateTime ); echo "</pre> ";   
		// $dateTime = new DateTime($dateTime);
		// echo "<pre> dateTime "; print_r( $dateTime ); echo "</pre> ";  

		if(empty($dateTime)){ return null;}
		$time = strtotime( $dateTime->format('Y-m-d H:i:s'));

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
	}


	// convert date time to human timing string (1 day ago)
static function Left_humanTiming ($dateTime){
		
		if(empty($dateTime)){ return null;}

		$time = strtotime( $dateTime->format('Y-m-d H:i:s'));

    $time = $time - time(); // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
	}





	// function to check if project expired or not. 
	static function isExpired ($dateTime){
		
			if(empty($dateTime)){ return false;}
			$exp_date = new DateTime($dateTime);
			$now = new DateTime();
			if($exp_date < $now) { 
				return true; 
			}else{ 
				return false; 
			}
	}



// function to convert currency to currency code. 
static function currency_to_code($currency){

	if(empty($currency)){ return null; }
	 
	 	$currency_code_array = EmpHelper::get_currency_list();

	if (isset( $currency_code_array[$currency] )){
		// return $currency_code_array[$currency];
		return $currency_code_array[$currency]['code'];
	}

}





// function to get currency list for dropdown. 
static function get_currency_list(){

	$currency_code_array = array();
	$currency_code_array['GBP'] = array( 'code' => '&#163', 'title' => 'Pound sterling');
	$currency_code_array['USD'] = array( 'code' => '&#36;', 'title' => 'United States dollar');
	$currency_code_array['JPY'] = array( 'code' => '&#165;', 'title' => 'Japanese yen');
	$currency_code_array['AUD'] = array( 'code' => '&#36;', 'title' => 'Australian dollar');
	$currency_code_array['EUR'] = array( 'code' => '&#8364;', 'title' => 'Euro');

	return $currency_code_array;

}





// function to get currency list for dropdown
static function currency_converter($from,$to,$amount) {
	 $url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to"; 
	 
	 $request = curl_init(); 
	 $timeOut = 0; 
	 curl_setopt ($request, CURLOPT_URL, $url); 
	 curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1); 
	 
	 curl_setopt ($request, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 
	 curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut); 
	 $response = curl_exec($request); 
	 curl_close($request); 
	 
	 return $response;
}



static function cc_tobase($from,$amount) {

	$empfohlen_setting_options = get_option( 'emp_setting' );
  $emp_forex = (array) $empfohlen_setting_options['emp_forex'];
  $return_amount = 0;

  $amount = $amount;
  // echo "<pre> cc_tobase empfohlen_setting_options "; print_r( $empfohlen_setting_options ); echo "</pre> ";  
  // echo "<pre> emp_member_dashboard "; print_r( $empfohlen_setting_options['emp_member_dashboard'] ); echo "</pre> ";  
  // echo "<pre> emp_forex['USD'] "; print_r( $emp_forex['USD'] ); echo "</pre> ";  

  // echo "<pre> cc_tobase emp_forex "; print_r( $emp_forex ); echo "</pre> ";  
  // echo "<pre> cc_tobase from "; print_r( $from ); echo "</pre> ";  
  // echo "<pre> cc_tobase amount "; print_r( $amount ); echo "</pre> ";  

  
  if(!empty($from) && isset($emp_forex[$from]) && ($amount > 0)){
  	$return_amount =  ($amount/$emp_forex[$from]);
  	// echo "<pre> cc_tobase emp_forex[from] "; print_r( $emp_forex[$from] ); echo "</pre> ";  
  	// echo "<pre> return_amount "; print_r(  $return_amount ); echo "</pre> ";  
  	// echo "<pre> emp_forex['USD'] "; print_r(  $emp_forex[USD] ); echo "</pre> ";  
  	$return_amount = round( $emp_forex['USD'] * $return_amount, 4);
  }
  return $return_amount;

}




static function cc_base_to_currency($to,$amount) {

	// echo "<pre> to "; print_r( $to ); echo "</pre> ";  
	// echo "<pre> amount "; print_r( $amount ); echo "</pre> ";  

	$empfohlen_setting_options = get_option( 'emp_setting' );
  $emp_forex = (array) $empfohlen_setting_options['emp_forex'];
  $return_amount = 0;

  $amount =   $amount;
  if(!empty($to) && isset($emp_forex[$to]) && ($amount > 0)){
  	$return_amount =  ($amount/$emp_forex['USD']);
  	$return_amount = round( $emp_forex[$to] * $return_amount, 4);
  }
  return $return_amount;
}


static function getUserCurrency($user_id) {
	$return = 'USD';
	$user_currency = get_user_meta( $user_id, 'user_currency' , true );
	if(!empty($user_currency)){ $return = $user_currency;  }
	return $return;
}


static function add_log_activity($user_id, $title, $log){ 
	global $wpdb;
	$wpdb->insert( 
	    $wpdb->prefix.'log_activity', 
	    array( 
	        'user_id'    => (int) $user_id,
	        'title'    	 => $title,
	        'log' 			=>  $log,
	        'date'      => date()
	    )
	);
	 
	 // echo "<pre>  "; print_r( $wpdb->insert_id ); echo "</pre> ";  
	 // echo "<pre>  "; print_r( $wpdb ); echo "</pre> "; exit;  

	// $record_id = $wpdb->insert_id;
}



static function successBannerHtml($message){

	$b_html = '
	<div class="cmodal relative alert alert-success alert-dismissible mt_20" style="background-color: #8bc34ad4;color: white;font-weight: bold;">
      <div class="close_nofi">
      	<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
      </div>
      <strong>'.__('Success! ','empfohlen').' </strong> '.$message.'   
  </div>'; 

  return $b_html;

}


static function errorBannerHtml($message){

	$b_html = '
	<div id="top-alert" class="cmodal relative alert alert-danger alert-dismissible mt_20" role="alert">
    <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
    <strong>'.__('Error! ','empfohlen').' </strong>'.$message.'
   </div>'; 

  return $b_html;

}


// function to check if user can access project info or not. 
// check if user skills match with at least on skill of project. 
// check if user is directly selected to this project.  
static function userCanAccessProject($user_id, $porject_id){
		
		// check if user is directly selected to this project. 
		$project_members = get_field('members', $porject_id);
		$user_select_as_member = false; 

		if(!empty($project_members)){
			$user_select_as_member = (in_array($user_id, $project_members)) ? true : false;
			if($user_select_as_member){
				return true; 
			}
		}

		// check if project at least one skills match with at least on skill of user. 
		$user_skills 		= get_user_meta($user_id, 'user_skill', true);
		$project_skills = get_the_terms( $porject_id, 'skill' );
		$project_skills = wp_list_pluck($project_skills, 'term_id');

		if(!empty($user_skills) && !empty($project_skills)){
			return array_intersect($project_skills, $user_skills);
		}

		return false; 
}


 static function getNewRegisterUserSkills(){
 	$empfohlen_setting_options = get_option( 'emp_setting' );
  $emp_new_register_skill = (array) $empfohlen_setting_options['emp_new_register_skill'];
  return $emp_new_register_skill;
 }




 static function getTaskProjectIDs($user_id){

 }

}
