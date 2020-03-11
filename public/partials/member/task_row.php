<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );

// echo "<pre> post "; print_r( $post ); echo "</pre> ";  
// echo "<pre>  "; print_r( $post ); echo "</pre> ";  

$current_user = wp_get_current_user();
$userData = $current_user->data;

$task_status 					= get_field( "task_status", $post->ID  );


$request_id  	= (int)  get_field( "request_id", $post->ID );
$project_id 	= (int)  get_field( "select_project_id", $request_id );
$project 				= get_post( $project_id );
// echo "<pre> request_id "; print_r( $request_id  ); echo "</pre> ";  
// echo "<pre> project_id "; print_r( $project_id  ); echo "</pre> ";  



$pay 									=   get_field( "pay", $project_id );
$keyword 							=   get_field( "keyword",$project_id );
$select_currency 			=   get_field( "select_currency", $project_id );
$timer_enable 				=   get_field( "timer_enable", $project_id );
$duration 						=   get_field( "duration", $project_id );
$expiration_date 			=   get_field( "expiration_date", $project_id );










// // get request for this project 
// $user_request = array();
// $args = array(
// 	'post_type'              => array( 'request' ),
// 	'meta_query'             => array(
// 		array(
// 			'key'     => 'select_project_id',
// 			'value'   => $post->ID,
// 		),
// 		array(
// 			'key'     => 'member_id',
// 			'value'   => $userData->ID,
// 		),
// 	),
// );
// $req_query = new WP_Query( $args );
// $request_exist = $req_query->posts;
// if(!empty($request_exist)){
// 	 $user_request = $request_exist[0];  
// 	 $user_request->request_status =  get_field( "request_status", $user_request->ID );  //'initial';



// 	 	// get task for this request
// 		$user_task = array();
// 		if(!empty($user_request)){
// 			$args = array(
// 				'post_type'              => array( 'task' ),
// 				'meta_query'             => array(
// 					array(
// 						'key'     => 'request_id',
// 						'value'   => $user_request->ID,
// 					),
// 				),
// 			);
// 			$task_query = new WP_Query( $args );
// 			$task_exist = $task_query->posts;
// 			if(!empty($task_exist)){
// 				 $user_task = $task_exist[0];  
// 				 $user_task->task_status =  get_field( "task_status", $user_task->ID );  //'initial';
// 				 $user_request->task = $user_task; 
// 			}
// 		}


// }









// echo "<pre> members "; print_r( $members ); echo "</pre> ";  
// echo "<pre>  request_exist "; print_r( $request_exist ); echo "</pre> ";  
// echo "<pre>  user_request "; print_r( $user_request ); echo "</pre> ";  
// echo "<pre>  args "; print_r( $args ); echo "</pre> ";  

?>

<div class="row project_item project_<?php echo $post->ID;?>">
		<div class="p_info">
			<div class="p_pay"><?php echo $select_currency.' '. $pay; ?></div>
			<div class="p_title"><?php echo $project->post_title;?></div>

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

			<div class="p_my_task_status">
				<a class="btn btn-sm p_task_btn capitalize" href="<?php echo get_the_permalink($user_request->task->ID); ?>"><?php echo $task_status;?></a>
			</div>

		</div> 				
</div>
<?php

 