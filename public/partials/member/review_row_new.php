<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );

$current_user = wp_get_current_user();
$userData = $current_user->data;

 
$task_id 					  =  (int) get_field( "task_id", $post->ID );


// echo "<pre> task_id "; print_r( $task_id ); echo "</pre> ";  
// $task = get_post($task_id);
// echo "<pre> task "; print_r( $task ); echo "</pre> ";  

$member_id 					=  (int) get_field( "member_id", $post->ID );

$description 				=  $post->post_content; 			// get_field( "description", $post->ID );
$title 							=  $post->post_title; 	// get_field( "ticket_response", $post->ID );


if ($task_id > 0) {	

	$request_id 					= (int) get_field('request_id', $task_id);
	$post->task_id_code   = (int) get_field('task_id', $task_id);	 
	$project_id 					= (int) get_field('select_project_id', $request_id);	

	$project  						= get_post($project_id);	

	// echo "<pre>  "; print_r( $project  	 ); echo "</pre> ";  

	$post->project_title 	= $project->post_title;
}


// $keyword 			=   get_field( "keyword",$post->ID );
// $description 	=   get_field( "description", $post->ID );
// $members 			=   get_field( "members", $post->ID );
// $staff 				=   get_field( "project_staff_id", $post->ID );
// if(!empty($staff) && !is_array($staff)){
// 	$staff = array($staff);
// }
// $select_currency 			=   get_field( "select_currency", $post->ID );
// $timer_enable 				=   get_field( "timer_enable", $post->ID );
// $duration 						=   get_field( "duration", $post->ID );
// $expiration_date 			=   get_field( "expiration_date", $post->ID );
// $isExpired 						= EmpHelper::isExpired($expiration_date );

 

 

?>

<div class="row review_item t_item ticket_<?php echo $post->ID;?>">
		
		 <div class="tick_head">

		 	<div class="col review_id_code"><span>ID : <?php echo $post->ID;?></span></div>
		 	
		 	
		 	<div class="col review_infoButton">
      	<span class="expand" data-pid="<?php echo $post->ID; ?>">Info</span>
      	<span class="collapse" data-pid="<?php echo $post->ID; ?>">Info</span>
      </div> 

      <div class="col review_title"><a><?php echo $title;?></a></div>
    
		 	<div class="col review_project"><?php echo isset($post->project_title)?('Project : '.$post->project_title):'';?></div>
		 	<div class="col tick_date"><?php echo $post->post_date;?></div>

		 </div>
		 

		 <div class="review_body">
		 		 <div class="t_content color_white">
				 	<h4 class="color_white t_content_title">Review Description : </h4>
				 	<div class="t_content_description"><?php echo $description;?></div>
				 </div> 
		 </div>

			 				
</div>
 

<?php

 