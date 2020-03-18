<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$current_user = wp_get_current_user();
// $userData = $current_user->data;
// echo "<pre> current_user "; print_r( $current_user->data->user_login ); echo "</pre> ";  

$profile_img  = get_user_meta($current_user->ID, 'profile_image', true);
$profile_img  = !$profile_img ? (EMPFOHLEN_URI.'/images/avatar.svg') : $profile_img;

// $profile_img = EMPFOHLEN_URI.'/images/avatar.svg';

 // echo "<pre> my_tasks "; print_r( $my_tasks->found_posts ); echo "</pre> ";  

 $available   = isset($member_project->found_posts)?($member_project->found_posts):0;
 $done        = isset($my_tasks->found_posts)?($my_tasks->found_posts):0;
 // $pending = $my_tasks->found_posts;
 $display_name = $current_user->data->display_name;

?>



 

<div class="uinfo_box_cont p_10 content_bg">
  <div class="uinfo_box">
    <div class="u_avatar dinline">
      <?php
      if(!empty($profile_img)){ ?>
        <img class="avatar" src="<?php echo $profile_img; ?>" class="emp_profile_thumb">
        <?php
      }
      ?>
    </div>
    <div class="uinfo_text">
        <h4 class="m_0">Hello <?php echo $display_name; ?>!</h4>
        <p class="italic m_0">Welcom to empfohlen</p>
        <p class="italic m_0">Available task <?php echo $available;?></p>
        <!-- <p class="italic m_0">Pending task 0</p> -->
        <p class="italic m_0">Task done <?php echo $done;?></p>
    </div>
  </div>
</div>






<style type="text/css">
.uinfo_text {
    float: right;
    width: calc(100% - 100px);
    height: 85px;
    cursor: pointer;
}
.u_avatar{   }
.u_avatar img {
    float: left;
    width: 90px;
    min-width: 90px;
    max-width: 90px;
    height: 90px;
    min-height: 90px;
    max-height: 90px;
    border-radius: 90px;
}
</style>

