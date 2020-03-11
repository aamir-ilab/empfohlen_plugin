<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );

// echo "<pre> post "; print_r( $post ); echo "</pre> ";  
// echo "<pre>  "; print_r( $post ); echo "</pre> ";  
$pay 					=   get_field( "pay", $post->ID );
$keyword 			=   get_field( "keyword",$post->ID );
$description 	=   get_field( "description", $post->ID );
$members 			=   get_field( "members", $post->ID );
$staff 				=   get_field( "project_staff_id", $post->ID );
if(!empty($staff) && !is_array($staff)){
	$staff = array($staff);
}
$select_currency 			=   get_field( "select_currency", $post->ID );
$timer_enable 				=   get_field( "timer_enable", $post->ID );
$duration 						=   get_field( "duration", $post->ID );
$expiration_date 			=   get_field( "expiration_date", $post->ID );



$isExpired 						= EmpHelper::isExpired($expiration_date );

// echo "<pre> timer_enable "; print_r( $timer_enable ); echo "</pre> "; 
// echo "<pre> duration "; print_r( $duration ); echo "</pre> "; 


$current_user = wp_get_current_user();
$userData = $current_user->data;

// get request for this project 
$user_request = array();
$args = array(
	'post_type'              => array( 'request' ),
	'meta_query'             => array(
		array(
			'key'     => 'select_project_id',
			'value'   => $post->ID,
		),
		array(
			'key'     => 'member_id',
			'value'   => $userData->ID,
		),
	),
);
$req_query = new WP_Query( $args );
$request_exist = $req_query->posts;
if(!empty($request_exist)){
	 $user_request = $request_exist[0];  
	 $user_request->request_status =  get_field( "request_status", $user_request->ID );  //'initial';



	 	// get task for this request
		$user_task = array();
		if(!empty($user_request)){
			$args = array(
				'post_type'              => array( 'task' ),
				'meta_query'             => array(
					// array(
					// 	'key'     => 'project_id',
					// 	'value'   => $post->ID,
					// ),
					// array(
					// 	'key'     => 'member_id',
					// 	'value'   => $userData->ID,
					// ),
					array(
						'key'     => 'request_id',
						'value'   => $user_request->ID,
					),
				),
			);
			$task_query = new WP_Query( $args );
			$task_exist = $task_query->posts;
			if(!empty($task_exist)){
				 $user_task = $task_exist[0];  
				 $user_task->task_status =  get_field( "task_status", $user_task->ID );  //'initial';
				 $user_request->task = $user_task; 
			}
		}


}


 



// echo "<pre> members "; print_r( $members ); echo "</pre> ";  
// echo "<pre>  request_exist "; print_r( $request_exist ); echo "</pre> ";  
// echo "<pre>  user_request "; print_r( $user_request ); echo "</pre> ";  
// echo "<pre>  args "; print_r( $args ); echo "</pre> ";  

?>

<div class="row project_item project_<?php echo $post->ID;?>">
		
		<div class="p_info">
			<div class="p_pay"><?php echo $select_currency.' '. $pay; ?></div>
			<!-- <div class="p_info_btn"><button class="btn btn-sm" data-pid="<?php echo $post->ID; ?>" >info</button></div> -->
			<div class="p_info_btn">
      	<span class="expand" data-pid="<?php echo $post->ID; ?>">Info</span>
      	<span class="collapse" data-pid="<?php echo $post->ID; ?>">Info</span>
       </div>
			
			<div class="p_title">
				<?php echo $post->post_title; ?>


				<?php if($timer_enable){
					$duration_text = EmpHelper::duration_to_readable($duration);
					echo '<span class="timer_icon dblock">Duration: '.$duration_text.'</span>';
				}?>


				<?php 
					$exp_date = EmpHelper::project_expiration_date_label($expiration_date);
					echo $exp_date;
				?>

			</div>

			<div class="p_keyword">
				<?php 
					if(!empty($keyword)){
						foreach ($keyword as $kwd_key => $kwd_v) { ?>
								<span class="p_kwd"><?php echo $kwd_v->name; ?></span>	
						<?php
						}
					}		 
				?>
			</div>
			
			<div class="p_request">
				<?php if(!$isExpired ){ ?>
					<?php if(empty($user_request)): ?>
						<button class="btn btn-sm p_request_btn" data-pid="<?php echo $post->ID; ?>">Send Request</button>
					<?php else: ?>
					<?php if (!empty($staff)): ?>
							<button class="btn btn-sm p_request_chat_btn" data-pid="<?php echo $post->ID; ?>" data-staff="<?=  implode($staff,',') ?>" data-user="<?= $userData->ID ?>" data-room="">Chat</button>
							<input type="hidden" value="<?php echo implode($staff,',')?>" name="project_staff">
						<?php endif; ?>
					
						<?php if(isset($user_request->task) && !empty($user_request->task)): ?>
							<a class="btn btn-sm p_task_btn dinline" href="<?php echo get_the_permalink($user_request->task->ID); ?>">Task: (<?php echo get_field( "task_status",$user_request->task->ID);?>)</a>
						<?php endif; // user_request->task end here  ?>

						<p class="counter_messages hidden">0</p>
						<p class="is_typing"></p>
				<?php endif; // user_request end  ?>
				<?php
				}else{ ?>
					<p>Expired</p>
				<?php
				}
				?>
			</div>



			 

		</div>
		
		<div class="p_content"><?php  echo $description; ?></div>  

			 				
</div>
 

<?php

 