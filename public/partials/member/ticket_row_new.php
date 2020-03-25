<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );

$current_user = wp_get_current_user();
$userData = $current_user->data;


// echo "<pre> post "; print_r( $post ); echo "</pre> ";  
// echo "<pre>  "; print_r( $post ); echo "</pre> ";  

$ticket_id 					=   get_field( "ticket_id", $post->ID );
$ticket_status 			=   get_field( "ticket_status", $post->ID );
$description 				=   get_field( "description", $post->ID );
$ticket_response 		=   get_field( "ticket_response", $post->ID );

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

<div class="row ticket_item t_item ticket_<?php echo $post->ID;?> status_<?php echo $ticket_status; ?>">
		
		 <div class="tick_head">
		 	
		 	<div class="col tick_id"><span><?php echo $ticket_id; ?></span></div>
		 	<div class="col tick_status"><span><?php echo $ticket_status;?></span></div>

		 	<div class="col tick_infoButton">
      	<span class="expand" data-pid="<?php echo $post->ID; ?>"><?php echo __('Info','empfohlen')?></span>
      	<span class="collapse" data-pid="<?php echo $post->ID; ?>"><?php echo __('Info','empfohlen')?></span>
      </div>


		 	<div class="col tick_title"><a class="ticket_info"><?php echo $post->post_title;?></a></div>
		 	<div class="col tick_date"><?php echo $post->post_date;?></div>

		 </div>
		 

		 <div class="tic_body">
		 		 <div class="t_content color_white">
				 	<h4 class="color_white t_content_title"><?php echo __('Ticket Description :','empfohlen')?></h4>
				 	<div class="t_content_description"><?php echo $description;?></div>
				 </div> 
		 </div>

			 				
</div>
 

<?php

 