<?php
if (!defined('ABSPATH')) exit;

get_header();

// // do_action('submit_task_port');
// global $_SESSION;

// $success =  	$_SESSION['task_success'];
// $error 	= 	$_SESSION['task_error'];

// unset($_SESSION['task_success']);
// unset($_SESSION['task_error']);
 
$current_user = wp_get_current_user();
$userData = $current_user->data;
$user_id = (int) $userData->ID;

$project_intro = get_field('project_intro', $post->ID);
$description = get_field('description', $project->ID);
$additional_information = get_field('additional_information', $project->ID);
    
$is_allowed = EmpHelper::userCanAccessProject($user_id,$post->ID);

if( $is_allowed ){
    include(EMPFOHLEN_DIR.'public/partials/project/project_info.php');
}else{
    include(EMPFOHLEN_DIR.'public/partials/project/project_not_allowed.php');
}

get_footer();