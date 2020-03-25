<?php if ( ! defined( 'ABSPATH' ) ) exit; 
wp_enqueue_script( 'dobPicker', EMPFOHLEN_URI.'public/js/dobPicker.js', array('jquery'),'',false);
$current_user = wp_get_current_user();
$userData = $current_user->data;
// echo "<pre> userData "; print_r( $userData ); echo "</pre> ";  
$profile_img	= get_user_meta($current_user->ID, 'profile_image', true);
$profile_img  = !$profile_img ? '' : $profile_img;
$tax_skill	= get_user_meta($current_user->ID, 'user_skill', true);
$tax_skill  = empty($tax_skill)?(array()):($tax_skill);
 
$birthday = get_user_meta( $current_user->ID, 'birthday' , true );
// echo "<pre> birthday "; print_r( $birthday ); echo "</pre> ";  
$day = ''; 
$month = ''; 
$year = ''; 
if(!empty($birthday)){
    $birthday_array = explode('-', $birthday);
    $day = isset($birthday_array[0])?($birthday_array[0]):'';
    $month = isset($birthday_array[1])?($birthday_array[1]):'';
    $year = isset($birthday_array[2])?($birthday_array[2]):'';
     // echo "<pre> birthday_array "; print_r( $birthday_array ); echo "</pre> ";  
}
$address = get_user_meta( $current_user->ID, 'address' , true );
$city = get_user_meta( $current_user->ID, 'city' , true );
$state = get_user_meta( $current_user->ID, 'state' , true );
$zip = get_user_meta( $current_user->ID, 'zip' , true );
$contact = get_user_meta( $current_user->ID, 'contact' , true );
$currency_list =   EmpHelper::get_currency_list();
$user_currency = get_user_meta( $current_user->ID, 'user_currency' , true );
 // echo "<pre> user_currency "; print_r( $user_currency ); echo "</pre> ";  
if ( ! session_id() ) { session_start(); }
$success   =  ( isset($_SESSION['prof_success']) && !empty($_SESSION['prof_success']) )?( $_SESSION['prof_success']):'';
$error   =  ( isset($_SESSION['prof_error']) && !empty($_SESSION['prof_error']) )?( $_SESSION['prof_error']):'';
unset($_SESSION['prof_success']);  
unset($_SESSION['prof_error']);  
if( !empty($success) ){?>
   <div class="cmodal relative alert alert-success alert-dismissible mt_20" style="background-color: #8bc34ad4;color: white;font-weight: bold;">
      <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
      <strong><?php echo __('Success!','empfohlen')?></strong> 
      <?php echo $success;?>
   </div>
<?php
}
if( !empty($error) ){ ?>
   <div id="top-alert" class="alert alert-danger" role="alert">
    <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
    <strong><?php echo __('Error!','empfohlen')?></strong> 
    <?php echo $error;?>
   </div>
<?php  
}
?>
 
<div class="content_bg p_20 setting">
  
  <div class="setting_header">
        <div class="set_title boxTitle"><?php _e('Your settings','empfohlen'); ?></div>
        <p class="subTitle"><?php _e('Your data and profile settings are located here','empfohlen'); ?></p>
  </div>  
 <div class="fusion-builder-row fusion-row ">
    <form id="emp_profile_form" class="emp_form_profile" method="post" enctype="multipart/form-data">
        <div class="col-md-6 p_0">
            <div class="subBox">
                <div class="field_box">
                    <label for="emp_user_id"><?php _e('User ID', 'empfohlen'); ?></label>
                    <div class="notEditable"><span><?php echo  $userData->ID; ?></span></div>
                </div>
                <div class="field_box">
                     <label for="emp_user_Login"><?php _e('Username', 'empfohlen'); ?></label>
                     <div class="notEditable"><span><?php echo  $userData->user_login; ?></span></div>
                </div>
                <div class="field_box editableContent">
                      <label for="emp_user_first"><?php _e('First Name', 'empfohlen'); ?></label>
                      <div class="editable focus_close">
                          <span><?php echo  $userData->display_name; ?></span>
                          <input name="emp_user_first" id="emp_user_first" type="text" value="<?php echo  $userData->display_name; ?>" />
                      </div> 
                </div>
                <div class="field_box editableContent">
                      <label for="emp_user_last"><?php _e('Surname', 'empfohlen'); ?></label>
                      <div class="editable focus_close">
                          <span><?php echo  $userData->user_nicename; ?></span>
                          <input name="emp_user_last" id="emp_user_last" type="text" value="<?php echo  $userData->user_nicename; ?>"/>
                      </div> 
                </div>
                <div class="field_box editableContent">
                    <label for="emp_user_birthday"><?php _e('Birthday', 'empfohlen'); ?></label>
                    <div class="editable edit_dob">
                          <span><?php echo $birthday; ?></span>
                          <!-- <input name="emp_user_birthday" id="emp_user_birthday" type="text" value="<?php // echo $emp_user_birthday; ?>" /> -->
                            
                        <div class="dob_cont">
                            <div><select id="dobday" data-day="<?php echo $day; ?>" class="dob_select" name="dobday"></select></div>
                            <div><select id="dobmonth" data-month="<?php echo $month; ?>" class="dob_select" name="dobmonth"></select></div>
                            <div><select id="dobyear" data-year="<?php echo $year; ?>" class="dob_select" name="dobyear"></select></div>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="subBox addressbox">
                <div class="field_box editableContent">
                      <label for="emp_user_address"><?php _e('Address', 'empfohlen'); ?></label>
                      <div class="editable focus_close">
                          <span><?php echo  $address; ?></span>
                          <input name="emp_user_address" id="emp_user_address" type="text" value="<?php echo $address;?>" />
                      </div> 
                </div>
                <div class="field_box editableContent">
                      <label for="emp_user_city"><?php _e('City', 'empfohlen'); ?></label>
                      <div class="editable focus_close">
                          <span><?php echo  $city; ?></span>
                          <input name="emp_user_city" id="emp_user_city" type="text" value="<?php echo  $city; ?>"/>
                      </div> 
                </div>
                <div class="field_box editableContent">
                    <label for="emp_user_state"><?php _e('State', 'empfohlen'); ?></label>
                    <div class="editable focus_close">
                          <span><?php  echo $state; ?></span>
                          <input name="emp_user_state" id="emp_user_state" type="text" value="<?php  echo $state; ?>" />
                      </div> 
                </div>
                <div class="field_box editableContent">
                    <label for="emp_user_zip"><?php _e('Zip', 'empfohlen'); ?></label>
                    <div class="editable focus_close">
                          <span><?php echo $zip; ?></span>
                          <input name="emp_user_zip" id="emp_user_zip" type="text" value="<?php echo $zip; ?>" />
                      </div> 
                </div>
            </div>
            <div class="subBox addressbox">
                <div class="field_box editableContent">
                      <label for="emp_user_email"><?php _e('E-mail address', 'empfohlen'); ?></label>
                      <div class="editable focus_close">
                          <span><?php echo  $userData->user_email; ?></span>
                          <input name="emp_user_email" id="emp_user_email" type="emil" value="<?php echo $userData->user_email;?>" />
                      </div> 
                </div>
                <div class="field_box editableContent">
                      <label for="emp_user_contact"><?php _e('Contact number', 'empfohlen'); ?></label>
                      <div class="editable focus_close">
                          <span><?php echo  $contact; ?></span>
                          <input name="emp_user_contact" id="emp_user_contact" type="text" value="<?php echo $contact;?>" />
                      </div> 
                </div>
                 
            </div>
        </div>
        <!-- left col -->
        
        <div class="col-md-6">
           
           <div class="subBox addressbox">
                <div class="profile_pic">
                <p><strong>Profile Picture</strong></p>
                <div class="profile-picture">
                    <div class="upload-thumb profile_image">
                        <?php
                        if(!empty($profile_img)){ ?>
                            <img src="<?php echo $profile_img; ?>" class="emp_profile_thumb">
                            <?php
                        }
                        ?>
                    </div>
                    <div class="upload-outer">
                        <div class="upload-btn-wrapper">
                            <button class="btn">Upload a file</button>
                            <input data-type="image" type="file" name="profilepicture" class="upload" />
                        </div>
                    </div>
                </div>
                </div>
           </div>
        <div class="subBox addressbox">
            <div class="other-info">
            <h4 style="margin: 10px 0;"><strong><?php _e('Relevant information','empfohlen');?></strong></h4>
            <?php
            $skill = get_terms(
                'skill',
                array(
                    'hide_empty'        => false,
                    'parent'            => 0,
                )
            );
            if(!empty($skill)){
                foreach ($skill as $skey => $p_skill) {
                    $c_skills = get_terms('skill', array('parent'   => $p_skill->term_id, 'hide_empty' => false));
                    if(!empty($c_skills)){ ?>
                        <div class="skills_cont">
                            <p class="bold"><?php echo $p_skill->name; ?></p>
                            <ul class="c_skills_list">
                                <?php
                                foreach ($c_skills as $cskey => $c_skill) { ?>
                                    <li class="row cskill_<?php echo $c_skill->term_id ?>">
                                    <p class="skill-checkbox">
                                            <input
                                                    value="<?php echo $c_skill->term_id; ?>"
                                                    type="checkbox"
                                                    name="tax_skill[<?php echo $c_skill->term_id;?>]"
                                                    id="in-skill-<?php echo $c_skill->term_id ?>"
                                                <?php echo in_array($c_skill->term_id, $tax_skill)? 'checked':''; ?>
                                            >
                                        <label class="selectit"><?php echo $c_skill->name; ?></label>
                                    </p>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <!-- c_skills_list end -->
                        </div>
                        <?php
                    }
                }
            }
            ?>
            </div>
        </div>
        <div class="subBox password_reset_box">
            <div><a class="reset_password_link"><?php _e('Click here to reset your password','empfohlen'); ?></a></div>
            <div class="reset_password_form">
                <div class="field_box editableContent">
                      <label for="emp_user_current_password"><?php _e('Current Password', 'empfohlen'); ?></label>
                      <div class="input_bx">
                          <input name="emp_user_current_password" id="emp_user_current_password" type="password" value="" />
                      </div> 
                </div>
                <div class="field_box editableContent">
                      <label for="emp_user_new_password"><?php _e('New Password', 'empfohlen'); ?></label>
                      <div class="input_bx">
                          <input name="emp_user_new_password" id="emp_user_new_password" type="password" value="" />
                      </div> 
                </div>
                <div class="field_box editableContent">
                      <label for="emp_user_new_password_conf"><?php _e('New Password Confirmation', 'empfohlen'); ?></label>
                      <div class="input_bx">
                          <input name="emp_user_new_password_conf" id="emp_user_new_password_conf" type="password" value="" />
                      </div> 
                </div>
            </div>
        </div>
        <div class="subBox password_reset_box">
          <label for="emp_user_contact"><?php _e('Default Currency','empfohlen');?></label>
          <select class="prof_currency_dropdown" name="emp_user_currency">
          <?php
            if(!empty($currency_list)){
              foreach ($currency_list as $ckey => $currency) {
                 echo  '<option value="'.$ckey.'"  '.(($user_currency == $ckey)?'selected="selected"':'').' >'.$currency['title'].' ('.$currency['code'].')</option>'; 
              }
            }
          ?>
          </select>
        </div>
        <div class="subBox addressbox">
            <div><a href="<?php echo wp_logout_url(get_home_url());?>" class="logout_link" class="emp_logout"><?php _e('Click here to Logout','empfohlen'); ?></a></div>
        </div>
        <div class="subBox addressbox">
            <div>
            <input type="hidden" name="emp_profile_nonce" value="<?php echo wp_create_nonce('emp-profile-nonce'); ?>"/>
            <input type="hidden" name="action" value="edit_profile" />
            <input id="emp_profile_btn" class="up-pro" type="submit" value="<?php _e('Upate Your Profile', 'empfohlen'); ?>"/>
            </div>
        </div>
        </div>
        <!-- right col -->
    </form>
</div>
</div>
<style type="text/css">
 .reset_password_link,
 .logout_link{
    font-weight: 600;
    cursor: pointer;
    background: url(<?php echo EMPFOHLEN_URI?>images/link_button.svg) no-repeat center left;
    line-height: 20px;
    padding-left: 22px;
 }   
.upload-thumb.profile_image{
    display: inline-block;
    vertical-align: text-bottom;
    margin: 0px 10px;
}
.set_title.boxTitle{
    padding: 10px 0px;
    font-size: 1.2em;
    font-weight: bold;
}
.subBox{
        background: rgba(255,255,255,.8);
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    padding: 10px;
    margin-bottom: 10px;
}    
.field_box .editable input { display: none; }
.field_box .editable.edit span { display: none !important; }
.field_box .editable.edit input { display: block !important; }
.field_box {
    padding: 10px 0px;
    border-bottom: 1px solid #9e9e9e21;
}
.editableContent span{
    display: inline-block;
    background: url(<?php echo EMPFOHLEN_URI?>images/symbol_edit.svg) no-repeat center right;
    background-size: auto 20px;
    padding-right: 25px;
    cursor: pointer;
    min-width: 20px;
    min-height: 20px;
}
.reset_password_form,.dob_cont{  display: none; }
.password_reset_box.edit .reset_password_form,
.edit .dob_cont{  display: block !important; }
.dob_cont > div select,
.prof_currency_dropdown{  
        background: #fefdfb;
    border-color: #ed9720;
    color: black;
 }
</style>
