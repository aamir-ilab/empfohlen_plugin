<?php if ( ! defined( 'ABSPATH' ) ) exit; 
//Exit if accessed directly 

 
// usage: $result = get_empfohlen_form_profile();
function get_empfohlen_form_profile($redirect=false) {
  if (is_user_logged_in()){  
    // require_once EMPFOHLEN_DIR . 'public/partials/profile/profile.php';
    require_once EMPFOHLEN_DIR . 'public/partials/profile/profile_new.php';
  } 
}
// print form #1
/* usage: <?php the_empfohlen_form_profile(); ?> */
function the_empfohlen_form_profile($redirect=false) {
  echo get_empfohlen_form_profile($redirect);
}
// shortcode for form #1
// usage: [empfohlen_form_profile] in post/page content
add_shortcode('empfohlen_form_profile','empfohlen_form_profile_shortcode');
function empfohlen_form_profile_shortcode ($atts,$content=false) {
  $atts = shortcode_atts(array(), $atts);
  return get_empfohlen_form_profile($atts);
}
  





add_action( 'parse_request', 'emp_profile_save' );
function emp_profile_save(){
  if( isset( $_POST['action'] ) && $_POST['action'] == 'edit_profile' ){

   $is_submitted = (isset($_POST['emp_profile_nonce']) && wp_verify_nonce($_POST['emp_profile_nonce'], 'emp-profile-nonce')) ? true : false;
   if($is_submitted){

      if ( ! session_id() ) { session_start(); }
      $_SESSION['prof_error']   = '';
      $_SESSION['prof_success'] = '';


      $postData = $_POST;

      // echo "<pre> postData "; print_r( $postData ); echo "</pre> ";  exit; 

      $current_user = wp_get_current_user();
      $userData = $current_user->data;
       // echo "<pre> userData "; print_r( $userData ); echo "</pre> ";  exit; 

      $user_id = $userData->ID;
      $postData = $_POST;


      $first_name     = !empty($postData['emp_user_first'])?(sanitize_text_field($postData['emp_user_first'])):'';
      $last_name      = !empty($postData['emp_user_last'])?(sanitize_text_field($postData['emp_user_last'])):'';
      $email          = !empty($postData['emp_user_email'])?(sanitize_text_field($postData['emp_user_email'])):'';
      // $password       = $postData['emp_user_pass'];
      // $password_c     = $postData['emp_user_pass_confirm'];
      $birthday       = sanitize_text_field($postData['dobday']).'-'.sanitize_text_field($postData['dobmonth']).'-'.sanitize_text_field($postData['dobyear']);
      $address        = sanitize_text_field($postData['emp_user_address']);
      $city           = sanitize_text_field($postData['emp_user_city']);
      $state          = sanitize_text_field($postData['emp_user_state']);
      $zip            = sanitize_text_field($postData['emp_user_zip']);
      $contact        = sanitize_text_field($postData['emp_user_contact']);
      $currency       = sanitize_text_field($postData['emp_user_currency']);

      $skills         = $postData['tax_skill'];
      $user_group     = $postData['user_group'];


      
      if(!empty( $first_name )){
        $userData->display_name = $first_name;
      }

      if(!empty( $last_name )){
        $userData->user_nicename = $last_name;
        $userData->nickname      = $last_name;
      }

      if(!empty( $email )){
        $userData->user_email = $email;
      }

      $user_data = wp_update_user( $userData  );

      // if user decided to change his currency then convert all earning to new currency 
      $user_currency = EmpHelper::getUserCurrency($current_user->ID);
      if(isset($currency) && !empty($currency) && ($currency != $user_currency ) ){
        $total_user_price =  get_field('balance_amount',  'user_'.$userData->ID ); 
        if(!empty($total_user_price)) {
          $user_earning_baseprice = EmpHelper::cc_tobase($user_currency, $total_user_price);
          $user_earning_converted_currency = EmpHelper::cc_base_to_currency($currency, $user_earning_baseprice); 
          update_user_meta( $user_id, 'balance_amount', $user_earning_converted_currency );
          update_user_meta( $user_id, 'user_currency', $currency );

          $log_act  = ' Old  currency = '.$user_currency.' amount = '.$total_user_price;
          $log_act .= ' \r\n New currency = '.$currency.'  amount = '.$user_earning_converted_currency;

          EmpHelper::add_log_activity($user_id,'Currency changed', $log_act);

        }
      }


       
      if(isset($birthday) && !empty($birthday)){ update_user_meta( $user_id, 'birthday', $birthday ); }
      if(isset($address)  && !empty($address)){  update_user_meta( $user_id, 'address', $address ); }
      if(isset($contact)  && !empty($contact)){  update_user_meta( $user_id, 'contact', $contact ); }
      if(isset($city)     && !empty($city)){     update_user_meta( $user_id, 'city', $city ); }
      if(isset($state)    && !empty($state)){    update_user_meta( $user_id, 'state', $state ); }
      if(isset($zip)      && !empty($zip)){      update_user_meta( $user_id, 'zip', $zip ); }
      
     

      


      if(isset( $postData['tax_skill'] )){
        $skills = array_map( 'intval', $postData['tax_skill'] );
        $skills = array_unique( $skills );
        update_user_meta( $user_id, 'user_skill', $skills );
      }

      

      $profilepicture = $_FILES['profilepicture'];
      
      if(!empty($profilepicture)  && empty($profilepicture['error'])  ){

          require_once(ABSPATH . 'wp-load.php');
          $wordpress_upload_dir = wp_upload_dir();
          $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
          $new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
          $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
          // looks like everything is OK
          if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
           
                $upload_id = wp_insert_attachment( array(
                  'guid'           => $new_file_path, 
                  'post_mime_type' => $new_file_mime,
                  'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
                  'post_content'   => '',
                  'post_status'    => 'inherit'
                ), $new_file_path );
               
                // wp_generate_attachment_metadata() won't work if you do not include this file
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
               
                // Generate and save the attachment metas into the database
                wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
                update_user_meta( $user_id, 'profile_image',  $wordpress_upload_dir['url'] . '/' . basename( $new_file_path )  );
                
           
          }

    }// profilepicture


    
    $emp_user_current_password  = isset($postData['emp_user_current_password'])?(sanitize_text_field($postData['emp_user_current_password'])):'';
    $emp_user_new_password      = isset($postData['emp_user_new_password'])?(sanitize_text_field($postData['emp_user_new_password'])):'';
    $emp_user_new_password_conf = isset($postData['emp_user_new_password_conf'])?(sanitize_text_field($postData['emp_user_new_password_conf'])):'';
  
    if (!empty($emp_user_current_password) && !empty($emp_user_new_password) && !empty($emp_user_new_password_conf)){
      if( $emp_user_new_password !== $emp_user_new_password_conf ){
         $_SESSION['prof_error'] = 'Confirm password does not match with new password';
      }else{
        // check if password correct. 
        if (wp_check_password( $emp_user_current_password, $current_user->user_pass, $current_user->data->ID )) {
            $udata['ID'] = $current_user->data->ID;
            $udata['user_pass'] =  $emp_user_new_password;
            $uid = wp_update_user( $udata );
            if(!$uid) { $_SESSION['prof_error'] = 'Sorry! Failed to update your account password details.';  } 
        }else{
          $_SESSION['prof_error'] = 'Current Password does not match the existing password';
        }
      }
    }

    $_SESSION['prof_success'] = 'Profile saved succesfully';
    wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
    exit();

   }// is_submitted
  } // action edit_profile
}// function 


 
// function emp_reg_new_user() {
  
//    // echo "<pre> emp_reg_new_user  _POST = "; print_r( $_POST['data'] ); echo "</pre> ";  
//    // echo "<pre>  default_role "; print_r( $default_role ); echo "</pre> ";  

//     $data_params = array();
//     parse_str($_POST['data'], $data_params);
//     // echo "<pre> data_params "; print_r(  $data_params ); echo "</pre> ";   
    
//     $return['status']   =  'initial';
//     $return['message']  =  '';

//      // echo "<pre>  emp_register_nonce = "; print_r( $data_params['emp_register_nonce'] ); echo "</pre> ";  

//     // Verify nonce
//     if( !isset( $data_params['emp_register_nonce'] ) || !wp_verify_nonce( $data_params['emp_register_nonce'], 'emp-register-nonce' ) ){
//           $return['status'] =  'error';
//           $return['message'] =  'Ooops, something went wrong, please try again later';
//           wp_send_json($return); 
//     }

//     // Post values
//     $username       = $data_params['emp_user_login'];
//     $first_name     = $data_params['emp_user_first'];
//     $last_name      = $data_params['emp_user_last'];
//     $email          = $data_params['emp_user_email'];
//     $password       = $data_params['emp_user_pass'];
//     $password_c     = $data_params['emp_user_pass_confirm'];
//     $birthday       = $data_params['emp_user_birthday'];
//     $address        = $data_params['emp_user_address'];
//     $skills         = $data_params['tax_skill'];
 

//     if ( empty($username) || empty($email)  || empty($password) || empty($password_c) )  {
//        $return['status']    =  'v_error'; 
//        $return['message']   =  'please enter all required field'; 

//         // echo "<pre> username "; print_r( $username ); echo "</pre> ";  
//         // echo "<pre> email "; print_r( $email ); echo "</pre> ";  
//         // echo "<pre> passwor "; print_r( $password ); echo "</pre> ";  
//         // echo "<pre> password_c "; print_r( $password_c ); echo "</pre> ";   

//        wp_send_json( $return); 
//     }

//     if( $password !== $password_c  ){
//        $return['status'] =  'v_error'; 
//        $return['message'] =  'password do not match'; 
//        wp_send_json( $return ); 
//     }


//     $userdata = array(
//         'user_login' => $username,
//         'user_pass'  => $password,
//         'user_email' => $email,
//         'first_name' => $first_name,
//         'nickname'   => $last_name,
//         'role'       =>  get_option('default_role', 'member'),
//     );


 
//     $user_id = wp_insert_user( $userdata ) ;
 
//     // Return
//     if( !is_wp_error($user_id) ) {
//         // echo '1';
//         $return['status'] =  'success';
//         $return['message'] =  'User succesfully created';
//         $return['user_id'] =  $user_id;
//         wp_send_json($return); 
//     } else {
//         $return['status'] =  'error';
//         $return['message'] =  $user_id->get_error_message();
//         wp_send_json( $return); 
//     }
//   die();
// }
 
// add_action('wp_ajax_emp_emp_profile_save', 'emp_profile_save');
// add_action('wp_ajax_nopriv_emp_register_user', 'emp_profile_save');

//  