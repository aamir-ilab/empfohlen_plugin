<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );
$current_user = wp_get_current_user();
$userData = $current_user->data;

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
$project_intro 			=   get_field( "project_intro", $post->ID );


$user_currency = EmpHelper::getUserCurrency($current_user->ID);
$price 				= 	 get_post_meta($post->ID, 'price', true); //get_post_meta($post->ID, 'price', true);
$user_price   	=  EmpHelper::cc_base_to_currency($user_currency,$price);



$isExpired 						= EmpHelper::isExpired($expiration_date );

// echo "<pre> timer_enable "; print_r( $timer_enable ); echo "</pre> "; 
// echo "<pre> duration "; print_r( $duration ); echo "</pre> "; 






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


 
$is_premium = false; 


// echo "<pre> members "; print_r( $members ); echo "</pre> ";  
// echo "<pre>  request_exist "; print_r( $request_exist ); echo "</pre> ";  
// echo "<pre>  user_request "; print_r( $user_request ); echo "</pre> ";  
// echo "<pre>  args "; print_r( $args ); echo "</pre> ";  

?>

 
 <div class="p_entry row project_item project_<?php echo $post->ID;?>">
 		
 		<div class="jhead">
 			
 			<div class="col p_pay">
 				<span><?php echo EmpHelper::currency_to_code($user_currency).' '. number_format($user_price,2); ?></span>
 				<!-- 
 				<span>price : <?php echo $price ?></span>
 				<span><?php // echo EmpHelper::currency_to_code($select_currency).' '. number_format($pay,2); ?></span> 
 				-->
 			</div>

 			<div class="col p_infoButton">
      	<span class="expand" data-pid="<?php echo $post->ID; ?>">Info</span>
      	<span class="collapse" data-pid="<?php echo $post->ID; ?>">Info</span>
       </div>


 			<div class="col p_title p_title_expandable" data-pid="<?php echo $post->ID; ?>">
 				<span class="p_name"><?php echo $post->post_title; ?></span>
 				<span class="p_labels">
 					<?php 
					if(!empty($keyword)){
						foreach ($keyword as $kwd_key => $kwd_v) { ?>
								<span class="p_label"><?php echo $kwd_v->name; ?></span>	
								<?php if( $kwd_v->name == 'Premium' ){ $is_premium = true;  } ?>
						<?php
						}
					}		 
					?>
 					<!-- <span class="p_label">Premium</span>
 					<span class="p_label">promotion</span> -->
 				</span>
 				<!-- <span class="p_duration">Duration: 5 minutes</span> -->
 			</div>

 			<div class="col p_action">
 					
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
 		<!-- jhead -->

 		<div class="jbody">

 			<div class="category"><?php _e('This is an order of the category', 'emp');?><br><br>
        <img style="width: 60px;" src="<?php echo EMPFOHLEN_URI; ?>images/p_standard_order.svg" alt="Standard orders"><br>
        <strong>Standard orders</strong><br>
        <br>
    	</div>

    	<div class="benefits m_20">
				<div class="benefit reward">
				<div class="benefitInner">
				    <div class="label"><?php _e('earnings','emp'); ?></div>
				    <div class="value">+ <?php echo EmpHelper::currency_to_code($select_currency).' '. $pay; ?></div>
				    <div class="clearFix"></div>
				</div>
				</div>
				
				
				<div class="benefit praemien">
				<div class="benefitInner">
				    <div class="label">premiums</div>
				    <div class="value"></div>
				    <div class="clearFix"></div>
				</div>
				</div>
			
				
				<div class="benefit moreJobs">
				<div class="benefitInner">
				    <div class="label">Get more orders</div>
				    <div class="value"></div>
				    <div class="clearFix"></div>
				</div>
				</div>
				
				<div class="benefit higherRewards">
				<div class="benefitInner">
				    <div class="label">Better paid orders</div>
				    <div class="value"></div>
				    <div class="clearFix"></div>
				</div>
				</div>
				
				<?php if($is_premium): ?>
				<div class="benefit premium ">
				<div class="benefitInner">
				    <div class="label">Premium job</div>
				    <div class="value"></div>
				    <div class="clearFix"></div>
				</div>
				</div>
				<?php endif; ?>

			</div>




		<div class="row p_desc">
			<div class="description"><?php echo $project_intro;?></div>
    </div>


    <div class="p_duration color_white">
  		<?php if($timer_enable){
				$duration_text = EmpHelper::duration_to_readable($duration);
				echo '<span class="timer_icon dblock">Duration: '.$duration_text.'</span>';
			}?>
    </div>

 		<?php  // echo $description; ?>
 				
 		</div>

 		<div class="jfooter"></div>

 </div>
 

<?php

 