<?php if ( ! defined( 'ABSPATH' ) ) exit; 

//Exit if accessed directly 
// wp_register_script(	'emp_reg_script', EMPFOHLEN_URI. 'public/js/ajax-registration.js', array('jquery'), null, false);
// wp_enqueue_script(	'emp_reg_script');
// wp_localize_script( 'emp_reg_script', 'emp_reg_vars', array('emp_ajax_url' => admin_url( 'admin-ajax.php' ),));

// var ajax_url = emp_reg_vars.emp_ajax_url;


$current_user = wp_get_current_user();
// echo "<pre>  current_user "; print_r( $current_user ); echo "</pre> ";  
$userData = $current_user->data;


$emp_user_birthday = get_user_meta( $current_user->ID, 'emp_user_birthday' , true );
$emp_user_address = get_user_meta( $current_user->ID, 'emp_user_address' , true );
// $user_meta = get_user_meta($userData->ID);
// echo "<pre> user_meta "; print_r( $user_meta ); echo "</pre> ";  
// $emp_user_birthday = $userData->


$profile_img	= get_user_meta($current_user->ID, 'profile_image', true);

$profile_img  = !$profile_img ? '' : $profile_img;

$tax_skill	= get_user_meta($current_user->ID, 'tax_skill', true);

$tax_skill = empty($tax_skill)?(array()):($tax_skill);
// echo "<pre> tax_skill "; print_r($tax_skill ); echo "</pre> ";  
// echo "<pre> profile_img  "; print_r( $profile_img ); echo "</pre> ";  
// global $error;
// if(isset($error['emp_profile'])){
//  echo "<pre> emp_profile "; print_r( $error['emp_profile'] ); echo "</pre> ";  
// }

?>


<!-- <link rel="stylesheet" href="http://localhost/empfohlen/wp-content/plugins/empfohlen/public/css/croppie.css" />
<script src="http://localhost/empfohlen/wp-content/plugins/empfohlen/public/js/croppie.js"></script>
 -->

<div class="content_bg p_20">
    


 <div class="fusion-builder-row fusion-row ">
    <form id="emp_profile_form" class="emp_form_profile" method="post" enctype="multipart/form-data">
    <!--left-column-->
    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_3 fusion-builder-column-3 fusion-one-half fusion-column-first 2_3 setting-left">
        <div class="fusion-column-wrapper">

    <fieldset>

        <div class="emp_profile_message"></div>

        <p>
            <label for="emp_user_id"><?php _e('User ID', 'emp'); ?></label>
            <input name="emp_user_id" id="emp_user_id" class="required" type="text" value="<?php echo  $userData->ID; ?>" disabled />
        </p>

        <p>
            <label for="emp_user_Login"><?php _e('Username', 'emp'); ?></label>
            <input name="emp_user_login" id="emp_user_login" class="required" type="text" value="<?php echo  $userData->user_login; ?>" disabled />
        </p>

        <p>
            <label for="emp_user_first"><?php _e('First Name', 'emp'); ?></label>
            <input name="emp_user_first" id="emp_user_first" type="text" value="<?php echo  $userData->display_name; ?>" />
        </p>

        <p>
            <label for="emp_user_last"><?php _e('Surname', 'emp'); ?></label>
            <input name="emp_user_last" id="emp_user_last" type="text" value="<?php echo  $userData->user_nicename; ?>"/>
        </p>

        <p>
            <label for="emp_user_email"><?php _e('Email', 'emp'); ?></label>
            <input name="emp_user_email" id="emp_user_email" class="required" type="email" value="<?php echo  $userData->user_email; ?>"/>
        </p>


        <p>
            <label for="emp_user_birthday"><?php _e('Birthday', 'emp'); ?></label>
            <input name="emp_user_birthday" id="emp_user_birthday" type="text" value="<?php echo $emp_user_birthday; ?>" />
        </p>


        <p>
            <label for="emp_user_address"><?php _e('Address', 'emp'); ?></label>
            <textarea rows="4" cols="50" name="emp_user_address" id="emp_user_address"><?php echo $emp_user_address;?></textarea>
        </p>

        
        </div>
    </div>
    <!--//left-column-->
    <!--right-column-->
    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2 fusion-builder-column-3 fusion-one-half fusion-column-last 1_2 setting-right">

        <div class="profile_pic">
            <p>Profile Picture</p>
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

        <!-- <script type="text/javascript">
            // jQuery(document).ready(function(){

            // 	jQuery('.emp_profile_thumb').croppie({
            // 		// url: 'demo/demo-1.jpg',
            // 	});

            // });
        </script> -->


        <div class="other-info">
            <h4>Other info</h4>
            <?php

            $skill = get_terms(
                'skill',
                array(
                    'hide_empty' 		=> false,
                    'parent'            => 0,
                )
            );

            // echo "<pre>  skill "; print_r( $skill ); echo "</pre> ";

            if(!empty($skill)){
                foreach ($skill as $skey => $p_skill) {

                    // echo "<pre> p_skill "; print_r(  $p_skill); echo "</pre> ";
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
										<label class="selectit">
                                            <?php echo $c_skill->name; ?>
                                        </label>
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


        <div class="password_reset">
            <p>Click here to reset your password </p>
        </div>





        <p>
            <input type="hidden" name="emp_profile_nonce" value="<?php echo wp_create_nonce('emp-profile-nonce'); ?>"/>
            <input type="hidden" name="action" value="edit_profile" />
            <input id="emp_profile_btn" class="up-pro" type="submit" value="<?php _e('Upate Your Profile', 'emp'); ?>"/>
        </p>
        </fieldset>
        
    </div>
    <!--//right-column-->
    </form>
</div>




</div>