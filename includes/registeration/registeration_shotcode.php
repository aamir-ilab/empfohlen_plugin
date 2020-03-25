<?php if ( ! defined( 'ABSPATH' ) ) exit; 
//Exit if accessed directly 
 
 if ( ! session_id() ) { session_start(); }
// ======= LOGIN FORM =====>
 
 
// return form #1
// usage: $result = get_empfohlen_form_login();
function get_empfohlen_form_login($redirect=false) {
  global $empfohlen_form_count;
  ++$empfohlen_form_count;
  
  
   $empfohlen_setting_options = get_option( 'emp_setting' );
   $emp_register_page = (int) $empfohlen_setting_options['emp_register_page'];  
   $register_url = get_permalink($emp_register_page);
  if (!is_user_logged_in()) :
    $return = "<form action=\"\" method=\"post\" class=\"empfohlen_form empfohlen_form_login\">\r\n";
    
    global $_SESSION;
    // echo "<pre> _SESSION "; print_r(  $_SESSION ); echo "</pre> ";  
    $success = array();
    if( isset($_SESSION['login_success']) && !empty($_SESSION['login_success']) ){
        $success[]   =  $_SESSION['login_success'];
    }
    if( isset($_SESSION['reg_success']) && !empty($_SESSION['reg_success']) ){
         $success[]   =  $_SESSION['reg_success'];
         do_shortcode('[empfohlen_registeration_cong]');
    }

    if( isset($_SESSION['account_verified_success']) && !empty($_SESSION['account_verified_success']) ){
         $success[]   =  $_SESSION['account_verified_success'];
    }

    

    // do_shortcode('[icegram messages="404"]');
    // do_shortcode('[icegram messages="406"]');
    // do_shortcode('[empfohlen_registeration_cong]');
    // do_shortcode('[icegram messages="407"]');
   
    $error     =  isset($_SESSION['login_error'])?($_SESSION['login_error']):'';
      unset($_SESSION['login_success']);  
      unset($_SESSION['reg_success']);  
      unset($_SESSION['login_error']);  
      unset($_SESSION['account_verified_success']); 
    // echo "<pre> success "; print_r( $success ); echo "</pre> ";  
    // echo "<pre> error "; print_r( $error ); echo "</pre> ";  
    
    $empfohlen_setting_options = get_option( 'emp_setting' );
    $emp_member_dashboard = $empfohlen_setting_options['emp_member_dashboard']; 
  //$return .= '<div class="emp_reg_message">';
    if (!empty($error)){
      if( is_array($error)){
        foreach ($error as $err) { 
         //$return .= "<p class=\"error alert alert-danger-custom\">{$err}</p>\r\n";}
         $return .= EmpHelper::errorBannerHtml( $err ); }
          
      }else{
        //$return .= "<p class=\"error alert alert-danger-custom\">{$error}</p>\r\n";
        $return .= EmpHelper::errorBannerHtml( $error ); 
      }
    }
    if (!empty($success)){
       if(is_array($success)){
         foreach ($success as $succ) { 
          //$return .= "<p class=\"success alert alert-success-custom\">{$succ}</p>\r\n"; 
          $return .= EmpHelper::successBannerHtml( $succ );
        }
        
       }else{
        //$return .= "<p class=\"success alert alert-success-custom\">{$success}</p>\r\n";
        $return .= EmpHelper::successBannerHtml( $success );
       }
     }
      
    //$return .= '</div>';
    $return .= '<div class="field-wrapper">';
    $return .= '<label for="username">'.__("Username","empfohlen").'</label>';
    $return .= '<input id="username" name="username" required="" type="text" placeholder="" />';
    $return .= '</div>';
    $return .= '<div class="field-wrapper">';
    $return .= '<label for="password">'.__('Password','empfohlen').'</label>';
    $return .= '  <input id="password" name="password" required="" type="password" placeholder="'.__('Password','empfohlen').'" />';
    $return .= '</div>';
    // $return .= "  <p>
    //   <label for=\"empfohlen_username\">".__('Username','empfohlen_login')."</label>
    //   <input type=\"text\" id=\"empfohlen_username\" name=\"empfohlen_username\"/>
    // </p>\r\n";
    // $return .= "  <p>
    //   <label for=\"empfohlen_password\">".__('Password','empfohlen_login')."</label>
    //   <input type=\"password\" id=\"empfohlen_password\" name=\"empfohlen_password\"/>
    // </p>\r\n";
   
    if ($redirect)
      $return .= "  <input type=\"hidden\" name=\"redirect\" value=\"{$redirect}\">\r\n";
  
    $return .= "  <input type=\"hidden\" name=\"action\" value=\"emp_ajaxlogin\">\r\n";
    $return .= "  <input type=\"hidden\" name=\"empfohlen_form\" value=\"{$emp_member_dashboard}\">\r\n";
    $return .= '  <input type="hidden" name="emp_submit_login_nonce" value="'.wp_create_nonce('emp-submit-login-nonce').'"/>';
    $return .= '<div class="submit-wrapper">';
    // $return .= '<a class="password_reset">Forgot Password? </a>';
    $return .= '  <button type="submit">'.__('Login','empfohlen').'</button>';
    $return .= '</div>';
     $return .= '<div class="submit-wrapper"><a href="'.$register_url.'">'.__("Register new account","empfohlen").'</a></div>'
    // $return .= '  <div id="signinCaptcha">';
    // $return .= '</div>';
    // $return .= "  <button type=\"submit\">".__('Login','empfohlen_login')."</button>\r\n";
   ;
    $return .= "</form>\r\n";
  else : 
      
      $empfohlen_setting_options = get_option( 'emp_setting' );
      $emp_member_dashboard = (int) (isset($empfohlen_setting_options['emp_member_dashboard'])?($empfohlen_setting_options['emp_member_dashboard']):0); 

      $return =  '<div class="already_loggedin">';
      $return .= '<h4>'.__('You are already logged in.', 'empfohlen').'</h4>';
      if ( $emp_member_dashboard > 0 ){
       $return .= '<a href="'.get_permalink($emp_member_dashboard).'">'.__('Click here to redirect to dashboard', 'empfohlen').'</a>';
      }
      $return .= '</div>';
    // $return = __('User is logged in.','empfohlen_login');
  endif;

  return $return;
}
// print form #1
/* usage: <?php the_empfohlen_form_login(); ?> */
function the_empfohlen_form_login($redirect=false) {
  echo get_empfohlen_form_login($redirect);
}
// shortcode for form #1
// usage: [empfohlen_form_login] in post/page content
add_shortcode('empfohlen_form_login','empfohlen_form_login_shortcode');
function empfohlen_form_login_shortcode ($atts,$content=false) {
    $atts = shortcode_atts(array(
      'redirect' => false
    ), $atts);
    return get_empfohlen_form_login($atts['redirect']);
}
 
function emp_login_submit() {
  if( isset( $_POST['action'] ) && $_POST['action'] == 'emp_ajaxlogin'  && !is_user_logged_in() ){
    $is_submitted = (isset($_POST['emp_submit_login_nonce']) && wp_verify_nonce($_POST['emp_submit_login_nonce'], 'emp-submit-login-nonce')) ? true : false;
    if($is_submitted){
      // echo "<pre> is_submitted emp_ajaxlogin "; print_r( $_POST ); echo "</pre> ";  exit; 
        if ( ! session_id() ) { session_start(); }
        $_SESSION['login_error'] = array();
        $_SESSION['login_success'] = '';
        $postData = $_POST;
        if (isset($_POST['username']) && isset($_POST['password'])) {
          $creds = array();
          $creds['user_login'] = $_POST['username'];
          $creds['user_password'] = $_POST['password'];
          $user = wp_signon( $creds );
          if ( is_wp_error($user) ) {
            $_SESSION['login_error'] = $user->get_error_message();
          }else{
            $empfohlen_setting_options = get_option( 'emp_setting' );
            $emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
            $redirect_url = get_permalink($emp_member_dashboard);
            $_SESSION['login_success'] = 'Succesfully Login';
            wp_redirect($redirect_url);
            exit(); 
          }
        }else{
          $_SESSION['login_error'] = __('Please enter username or password ','empfohlen');
        }
        wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
        exit();
    }// is_submitted 
  }// action 
}
add_action('parse_request', 'emp_login_submit', 1); 
// add_action('wp_ajax_emp_ajaxlogin', 'emp_login');
// add_action('wp_ajax_nopriv_emp_register_user', 'emp_reg_new_user');
 
// <============== FORM LOGIN
 
// ============== Utilities ============== >>
// if (!function_exists('set_empfohlen_error')) {
//   function set_empfohlen_error($error,$id=0) {
//     $_SESSION['empfohlen_error_'.$id] = $error;
//   }
// }
// // shows error message
// if (!function_exists('the_empfohlen_error')) {
//   function the_empfohlen_error($id=0) {
//     echo get_empfohlen_error($id);
//   }
// }
 
// if (!function_exists('get_empfohlen_error')) {
//   function get_empfohlen_error($id=0) {
//     if ($_SESSION['empfohlen_error_'.$id]) {
//       $return = $_SESSION['empfohlen_error_'.$id];
//       unset($_SESSION['empfohlen_error_'.$id]);
//       return $return;
//     } else {
//       return false;
//     }
//   }
// }
// if (!function_exists('set_empfohlen_success')) {
//   function set_empfohlen_success($error,$id=0) {
//     $_SESSION['empfohlen_success_'.$id] = $error;
//   }
// }
// if (!function_exists('the_empfohlen_success')) {
//   function the_empfohlen_success($id=0) {
//     echo get_empfohlen_success($id);
//   }
// }
 
// if (!function_exists('get_empfohlen_success')) {
//   function get_empfohlen_success($id=0) {
//     if ($_SESSION['empfohlen_success_'.$id]) {
//       $return = $_SESSION['empfohlen_success_'.$id];
//       unset($_SESSION['empfohlen_success_'.$id]);
//       return $return;
//     } else {
//       return false;
//     }
//   }
// }
// <============== utility
 
 
// ======= FORM REGISTER =====>
 
// return form #2
// usage: $result = get_empfohlen_form_register();
function get_empfohlen_form_register($redirect=false) {
  wp_enqueue_script( 'dobPicker', EMPFOHLEN_URI.'public/js/dobPicker.js', array('jquery'),'',false);
  $empfohlen_setting_options = get_option( 'emp_setting' );
  if (!is_user_logged_in()){  
      
    // require_once EMPFOHLEN_DIR . 'public/partials/registeration/registeration.php';
    // echo "<pre> get_empfohlen_form_register ";  echo "</pre> ";   exit; 
    // ob_start(); 
    // require_once EMPFOHLEN_DIR . 'public/partials/registeration/registeration.php';
    // $return  = ob_get_clean();
    // ob_end_clean();
    $emp_login_page = (int) $empfohlen_setting_options['emp_login_page'];  
    $login_url = get_permalink($emp_login_page);
    global $_SESSION;
    // echo "<pre> _SESSION "; print_r(  $_SESSION ); echo "</pre> ";  
    $success = (isset($_SESSION['reg_success']) && !empty($_SESSION['reg_success']))?($_SESSION['reg_success']):array();
    $error = (isset($_SESSION['reg_error']) && !empty($_SESSION['reg_error']))?($_SESSION['reg_error']):array();
    unset($_SESSION['reg_success']);  
    unset($_SESSION['reg_error']);  
    
    // echo "<pre> success "; print_r(  $success ); echo "</pre> ";  
    // echo "<pre> error "; print_r(  $error ); echo "</pre> ";  
    $return = '<form id="emp_registration_form" class="emp_form" action="" method="post">';
    $return .= '<div class="emp_reg_message">'; 
    if ( !empty($success) ){
      if(is_array($success)){
        foreach ($success as $succ) {  $return .= EmpHelper::successBannerHtml( $succ );}
          //$return .= "<p class=\"success alert alert-success-custom\">{$succ}</p>\r\n"; }
      } else{
        //$return .= "<p class=\"success alert alert-success-custom\">{$success}</p>\r\n";
      $return .= EmpHelper::successBannerHtml( $success );
      }
    }
    if ( !empty($error) ){
      if(is_array($error)){
        foreach ($error as $err) {  
   $return .= EmpHelper::errorBannerHtml( $err ); }
//$return .= "<p class=\"error alert alert-danger-custom\">{$err}</p>\r\n";  }
      } else{
         $return .= EmpHelper::errorBannerHtml( $error );
        // $return .= "<p class=\"error alert alert-danger-custom\">{$error}</p>\r\n";
      }
    }
    $return .= '</div>';
    $return .= '<div class="field-wrapper two">';
    $return .= '    <div class="two-left">';
    $return .= '        <label for="emp_user_first">'.__('First Name', 'empfohlen').'</label><br>';
    $return .= '        <input id="emp_user_first" name="emp_user_first" required=""  type="text">';
    $return .= '    </div>';
    $return .= '    <div class="two-right">';
    $return .= '        <label for="emp_user_last">'.__('Surname', 'empfohlen').'</label><br>';
    $return .= '        <input name="emp_user_last" id="emp_user_last" type="text"/>';
    $return .= '    </div>';
    $return .= '</div>';
    $return .= '<div class="field-wrapper two">';
    $return .= '    <div class="two-left">';
    $return .= '        <label for="emp_user_Login">'.__('Username', 'empfohlen').'</label><br>';
    $return .= '        <input name="emp_user_login" id="emp_user_login" required="" type="text"/>';
    $return .= '    </div>';
    $return .= '    <div class="two-right">';
    $return .= '        <label for="emp_user_email">'.__('Email', 'empfohlen').'</label><br>';
    $return .= '        <input name="emp_user_email" id="emp_user_email" required="" type="email"/>';
    $return .= '    </div>';
    $return .= '</div>';
    $return .= '<div class="field-wrapper two">';
    $return .= '    <div class="two-left">';
    $return .= '        <label for="password">'.__('Password', 'empfohlen').'</label><br>';
    $return .= '        <input name="emp_user_pass" id="password" required="" type="password"/>';
    $return .= '    </div>';
    $return .= '    <div class="two-right">';
    $return .= '        <label for="password_again">'.__('Password Again', 'empfohlen').'</label><br>';
    $return .= '        <input name="emp_user_pass_confirm" id="password_again" required="" type="password"/>';
    $return .= '    </div>';
    $return .= '</div>';
  
    $currency_list =   EmpHelper::get_currency_list();
    $return .= '<div class="field-wrapper">';
    $return .= '<label for="currency">'.__('Currency', 'empfohlen').'</label><br>';
    $return .= '<div class="dtable w100 mt_20">';
    $return .= '<select class="reg_currency_dropdown" name="emp_user_currency">';
    if (!empty($currency_list)){
      foreach ($currency_list as $ckey => $currency) {
        $return .= '<option value="'.$ckey.'">'.$currency['title'].' ('.$currency['code'].')</option>';
      }
    }
    $return .= '</select>';
    $return .= '</div>';
    $return .= '</div>';
    $return .= '<div class="field-wrapper ">';
    $return .= '    <label for="emp_user_birthday">'.__('Birthday', 'empfohlen').'</label><br>';
    // $return .= '    <input name="emp_user_birthday" id="emp_user_birthday" type="text"/>';
     $return .= '<div class="dtable w100 mt_20">'; 
     $return .= '<div class="dob_cont">'; 
       $return .= '<div><select id="dobday" class="dob_select" name="dobday"></select></div>'; 
       $return .= '<div><select id="dobmonth" class="dob_select" name="dobmonth"></select></div>';
       $return .= '<div><select id="dobyear" class="dob_select" name="dobyear"></select></div>';
    $return .= '</div>';
    $return .= '</div>';
    $return .= '</div>';
    $return .= '<div class="field-wrapper">';
    $return .= '    <label for="emp_user_address">'.__('Address', 'empfohlen').'</label><br>';
    $return .= '<input name="emp_user_address" id="emp_user_address" type="text"/>';
    $return .= '</div>';
    $return .= '<div class="field-wrapper">';
    $return .= '    <label for="emp_user_city">'.__('City', 'empfohlen').'</label><br>';
    $return .= '<input name="emp_user_city" id="emp_user_city" type="text"/>';
    $return .= '</div>';
    $return .= '<div class="field-wrapper row">';
    $return .= '<div class="col-sm-6">';
    $return .= '<label for="emp_user_state">'.__('State', 'empfohlen').'</label><br>';
    $return .= '<input name="emp_user_state" id="emp_user_state" type="text"/>';
    $return .= '</div>';
    $return .= '<div class="col-sm-6">';
    $return .= '<label for="emp_user_zip">'.__('Zip', 'empfohlen').'</label><br>';
    $return .= '<input name="emp_user_zip" id="emp_user_zip" type="text"/>';
    $return .= '</div>';
    $return .= '</div>';
    // $return .= '<div class="user_skill_tax reg_tax_select">'; 
    //   $return .= '<div class="field-wrapper">';
    //   $return .= '<div class=""><label>Select Skills</label></div>';
    //   $return .= '</div>';
    //   $return .= '<div class="field-wrapper skill_select eug-radio">';
    //       $skill = get_terms( 
    //           'skill', 
    //           array(
    //             'hide_empty'     => false,
    //             'parent'            => 0,
    //           )
    //         );
    //     // echo "<pre> skill "; print_r( $skill ); echo "</pre> ";  
    //     if(!empty($skill)){
    //       foreach ($skill as $skey => $p_skill) {
   
    //           $c_skills = get_terms('skill', array('parent'   => $p_skill->term_id, 'hide_empty' => false));
    //            if(!empty($c_skills)){  
               
               
               
    //               $return .= '<div class="field-wrapper eug-radio eug-legal">';
    //                     $return .= '<div class="eug-radio-item">';
    //                   foreach ($c_skills as $cskey => $c_skill) {
    //                         $return .= '<input  value="'.$c_skill->term_id.'"  type="checkbox"  name="tax_skill['.$c_skill->term_id.']" id="in-skill-'.$c_skill->term_id.'">';
    //                         $return .= '<label class="selectit" for=in-skill-'.$c_skill->term_id.'>'.$c_skill->name.'</label>';
    //                         }
    //                     $return .= '</div>';
    //                 $return .= '</div>';
    //            }
    //       }
    //     }
    //   $return .= '</div>';
    // $return .= '</div> <!-- user_skill_tax--> ';
   // project group taxonomy 
   // $return .= '<div class="user_group_tax reg_tax_select">'; 
  
   // $return .= '<div class="field-wrapper">';
   //    $return .= '<div class=""><label>Select Project Group</label></div>';
   // $return .= '</div>';
   // $user_group = get_terms( 
   //    'user-group', 
   //    array(
   //      'hide_empty'     => false,
   //      'parent'            => 0,
   //    )
   //  );
   // if(!empty($user_group)){
   //    foreach ($user_group as $ug_key => $u_group) {
   //      $c_user_group = get_terms('user-group', array('parent'   => $u_group->term_id, 'hide_empty' => false));
   //       $return .= '<div class="ugroup_cont">';
   //         $return .= '<div class="eug-radio-item ug_'.$u_group->term_id.'">';
   //          $return .= '<label class="selectit">';
   //          $return .= '<input  value="'.$u_group->term_id.'"  type="checkbox"  name="user_group['.$u_group->term_id.']" id="rg_ug_'.$u_group->term_id.'">'.$u_group->name.'';
   //         $return .= '</label>';
   //         $return .= '</div>';
   //         $return .= '  <ul class="c_user_group_list">';
   //         if(!empty($c_user_group)){  
   //           foreach ($c_user_group as $cug_key => $cu_group) {  
   //                $return .= '<div class="eug-radio-item ug_'.$cu_group->term_id.'">';
   //                $return .= '<label class="selectit">';
   //                $return .= '<input  value="'.$cu_group->term_id.'"  type="checkbox"  name="user_group['.$cu_group->term_id.']" id="rg_ug_'.$cu_group->term_id.'">'.$cu_group->name.'';
   //                 $return .= '</label>';
   //                 $return .= '</div>';
   //            }
   //         }
   //         $return .= '  </ul>';
   //       $return .= '</div>';
   //       // $return .= '  <p>'.$u_group->name.'</p>';
   //    }
   //  }
   //  $return .= '</div> <!-- user_group_tax -->'; 
    $return .= '<p>';
     $return .= ' <input type="hidden" name="emp_register_nonce" value="'.wp_create_nonce('emp-register-nonce').'"/>';
     $return .= "  <input type=\"hidden\" name=\"action\" value=\"emp_registeration_submit\">\r\n";
      // $return .= '<input id="emp_register_btn" type="submit" value="'.__('Register Your Account', 'emp').'"/>';
    $return .= '</p>';
    $return .= '<div class="submit-wrapper"><button type="submit">'.__('Register', 'empfohlen').'</button></div>';
    $return .= '<div class="submit-wrapper"><a href="'.$login_url.'">'.__('Already have account Sign in', 'empfohlen').'</a></div>';
  $return .= '</form>';
     
  // echo "<pre> return "; print_r( $return ); echo "</pre> ";  exit; 
  return $return;
  }else{
     $emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
     $emp_member_page = get_permalink($emp_member_dashboard);
     $return = '<div class="already_register">';
      $return .= '<div class="submit-wrapper m_20"><button class="btn"><a class="color_white" href="'.$emp_member_page.'">'.__('Already login click here to go to dashboard page', 'empfohlen').'</a></button></div>';
      $return .= '<div class="submit-wrapper m_20"><button class="btn"><a class="color_white" href="'.wp_logout_url(home_url()).'">'.__('Logout','empfohlen').'</a></button></div>';
     $return .= '</div>';
     return $return; 
   
    // if( !empty($emp_member_page)){
    //    wp_redirect(esc_url_raw($emp_member_page));
    //    exit();
    // }else{
    //    wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
    //    exit();
    // }
    // echo "Already logged in "; exit; 
  }
}
// function get_empfohlen_form_login_register($redirect=false) {
//   if (!is_user_logged_in()){  
//     require_once EMPFOHLEN_DIR . 'public/partials/registeration/login_registeration.php';
//   }else{
//   }
    
// }
// print form #1
/* usage: <?php the_empfohlen_form_register(); ?> */
function the_empfohlen_form_register($redirect=false) {
  echo get_empfohlen_form_register($redirect);
}
// shortcode for form #1
// usage: [empfohlen_form_register] in post/page content
add_shortcode('empfohlen_form_register','empfohlen_form_register_shortcode');
function empfohlen_form_register_shortcode ($atts,$content=false) {
  $atts = shortcode_atts(array(
    'redirect' => false
  ), $atts);
  return get_empfohlen_form_register($atts['redirect']);
}
   
// // shortcode for form #1
// // usage: [empfohlen_form_login_register] in post/page content
// add_shortcode('empfohlen_form_login_register','empfohlen_form_login_register_shortcode');
// function empfohlen_form_login_register_shortcode ($atts,$content=false) {
//   $atts = shortcode_atts(array(
//     'redirect' => false
//   ), $atts);
//   return get_empfohlen_form_login_register($atts['redirect']);
// }
  
 
function emp_reg_new_user() {
  
   // echo "<pre> emp_reg_new_user  _POST = "; print_r( $_POST['data'] ); echo "</pre> ";  
   // echo "<pre>  default_role "; print_r( $default_role ); echo "</pre> ";  
  if( isset( $_POST['action'] ) && $_POST['action'] == 'emp_registeration_submit' ){
   $is_submitted = (isset($_POST['emp_register_nonce']) && wp_verify_nonce($_POST['emp_register_nonce'], 'emp-register-nonce')) ? true : false;
   if($is_submitted){
      if ( ! session_id() ) { session_start(); }
      $_SESSION['reg_error'] = array();
      $_SESSION['reg_success'] = '';
      $postData = $_POST;
      
      // add default skills
      $postData['tax_skill'] =  EmpHelper::getNewRegisterUserSkills(); //array(19);

      // echo "<pre> postData "; print_r( $postData ); echo "</pre> ";  exit; 
      // Post values
      $username       = $postData['emp_user_login'];
      $first_name     = $postData['emp_user_first'];
      $last_name      = $postData['emp_user_last'];
      $email          = $postData['emp_user_email'];
      $password       = $postData['emp_user_pass'];
      $password_c     = $postData['emp_user_pass_confirm'];
      $birthday       = $postData['dobday'].'-'.$postData['dobmonth'].'-'.$postData['dobyear'];
      $address        = $postData['emp_user_address'];
      $city           = $postData['emp_user_city'];
      $state          = $postData['emp_user_state'];
      $zip            = $postData['emp_user_zip'];
      $skills         = $postData['tax_skill'];
      $user_group     = $postData['user_group'];
      $user_currency     = $postData['emp_user_currency'];
        if ( empty($username) || empty($email)  || empty($password) || empty($password_c) )  {
           $_SESSION['reg_error']   =  __('please enter all required field', 'empfohlen');
           wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
           exit();
        }
        if( $password !== $password_c  ){
           $_SESSION['reg_error']   =  __('password do not match','empfohlen');
            wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
            exit();
        }
        $userdata = array(
          'user_login' => $username,
          'user_pass'  => $password,
          'user_email' => $email,
          'first_name' => $first_name,
          'nickname'   => $last_name,
          'role'       =>  get_option('default_role', 'member'),
        );
        $user_id = wp_insert_user( $userdata ) ;
 
        // Return
        if( !is_wp_error($user_id) ) {
            
            if ( ($user_id > 0) && !empty($skills) ) {
              $skills = array_map( 'intval', $skills );
              $skills = array_unique( $skills );
              add_user_meta( $user_id, 'user_skill', $skills );
            }
            // if ( ($user_id > 0) && !empty($user_group) ) {
            //   $user_group = array_map( 'intval', $user_group );
            //   $user_group = array_unique( $user_group );
            //   add_user_meta( $user_id, $user_group, 'user_group' );
            // }
            // add user meta 
            if ( $user_id > 0 ){
              add_user_meta( $user_id, 'birthday', $birthday);
              add_user_meta( $user_id, 'address', $address);
              add_user_meta( $user_id, 'city', $city);
              add_user_meta( $user_id, 'state', $state);
              add_user_meta( $user_id, 'zip', $zip); 
              add_user_meta( $user_id, 'user_currency', $user_currency); 
              // verification email and code 
              // activation_code
              // $user_info = get_userdata($user_id);
              
              // create md5 code to verify later
              $code = md5(time());
              // make it into a code to send it to user via email
              $string = array('id'=>$user_id, 'code'=>$code);
              // create the activation code and activation status
              add_user_meta($user_id, 'account_activated', 0);
              add_user_meta($user_id, 'activation_code', $code);
              $url = get_site_url(). '/my-account/?act=' .base64_encode( serialize($string));
              $html = 'Please click the following links <br/><br/> <a href="'.$url.'">'.$url.'</a>';
              $headers = array( 'Content-type: text/html' );
              // wp_mail( $to, $subject, $message, $headers, $attachments );
              $headers = array( 'Content-type: text/html' );
              $email_status =  wp_mail( $email, __('Account Verification','empfohlen') , $html, $headers);
                      
            }
            $_SESSION['reg_success'] = __('Your have Succesfully registered please login here', 'empfohlen');
            $empfohlen_setting_options = get_option( 'emp_setting' );
            $emp_login_page = (int) $empfohlen_setting_options['emp_login_page'];  
            $redirect_url = get_permalink($emp_login_page);
            wp_redirect($redirect_url);
            exit();
        } else {
            $_SESSION['reg_error'] = $user_id->get_error_message();
            wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
            exit();
        }
   }// is_submitted 
  }// action submit 
  // return tue; 
}
 
// add_action('wp_ajax_emp_register_user', 'emp_reg_new_user');
// add_action('wp_ajax_nopriv_emp_register_user', 'emp_reg_new_user');
add_action('parse_request', 'emp_reg_new_user', 1); 
  
// hoock to verifiy user account when they click on the link in their email. 
add_action( 'init', 'verify_user_code' );
function verify_user_code(){
    if(isset($_GET['act'])){
        $data = unserialize(base64_decode($_GET['act']));
        
        $code = get_user_meta($data['id'], 'activation_code', true);
        // verify whether the code given is the same as ours
        if($code == $data['code']){
            // update the user meta
            update_user_meta($data['id'], 'account_activated', 1);
            // echo __( '<strong>Success:</strong> Your account has been activated! ', 'text-domain' );
             if ( ! session_id() ) { session_start(); }
             $_SESSION['account_verified_success'] = __('Your e-mail has been verified!','empfohlen');

            $empfohlen_setting_options = get_option( 'emp_setting' );
            $emp_login_page = (int) (isset($empfohlen_setting_options['emp_login_page'])?($empfohlen_setting_options['emp_login_page']):0); 
            if ( $emp_login_page > 0 ){
              wp_redirect( get_permalink($emp_login_page)  ); 
            }else{
              wp_redirect(get_site_url());
            }
            exit; 
        }else{
          echo '<h3>'.__('Verification Code incorrect or expired','empfohlen').'</h3>';
          exit; 
        }
    }
}
 
// add short code to show on succesfully registeration.
// show popup modal to congratulation. 
add_shortcode('empfohlen_registeration_cong','empfohlen_registeration_cong_shortcode');
function empfohlen_registeration_cong_shortcode ($atts,$content=false) {
  $congHtml = '<div class="congrat_reg_modal">'; 
  $congHtml .= '<div id="myModal" class="reg_modal cmodal">';
  $congHtml .= '<div class="reg_modal-content">';
  $congHtml .= '  <span class="close close_reg" >&times;</span>';
  $congHtml .= '  <p>'.__('Congratulation for the registration please confirm you e-mail!','empfohlen').'</p>';
  $congHtml .= '</div>';
  $congHtml .= '</div>';
  $congHtml .= '</div>'; 
  echo $congHtml; 
}
 
// ajax call to resend verification code in email. 
add_action( 'wp_ajax_email_verification_resend_submit', 'email_verification_resend_submit_callback' );
add_action( 'wp_ajax_nopriv_email_verification_resend_submit', 'email_verification_resend_submit_callback' );
function email_verification_resend_submit_callback() {
  if(is_user_logged_in()){
    $current_user = wp_get_current_user();
    $userData = $current_user->data;
    $return['userData'] = $userData; 
    
     // echo "<pre> userData "; print_r( $userData ); echo "</pre> ";  exit; 
    check_ajax_referer( 'email-verification-nonce', 'security' );
    $account_activated =   get_user_meta($userData->ID, 'account_activated', true);
    if($account_activated){
      $return['status'] =  __('Success','empfohlen'); 
      $return['code'] =  'already_verified';
      $return['message'] =  'Already verified'; 
      wp_send_json( $return ); 
    }else{
      
      $user_id =  $userData->ID;
      
      $code = md5(time());
      $string = array('id'=>$user_id, 'code'=>$code);
      update_user_meta($user_id, 'account_activated', 0);
      update_user_meta($user_id, 'activation_code', $code);
      $url = get_site_url(). '/my-account/?act=' .base64_encode( serialize($string));
      $html = 'Please click the following links <br/><br/> <a href="'.$url.'">'.$url.'</a>';
      $headers = array( 'Content-type: text/html' );
      wp_mail( $userData->user_email, __('Account Verification','emp') , $html, $headers);
      $return['status'] =  'success'; 
      $return['code'] =  'email_send';
      $return['message'] =  'Verification email has been send to your email, please check inbox'; 
      wp_send_json( $return ); 
    }
  }else{
    $return['status'] =  'error'; 
    $return['message'] =  'please login first'; 
    wp_send_json( $return ); 
  }
  wp_send_json( $return ); 
}