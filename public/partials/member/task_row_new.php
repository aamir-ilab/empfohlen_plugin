<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );
$current_user = wp_get_current_user();
$userData = $current_user->data;
$task_status		= get_field( "task_status", $post->ID  );

$request_id  	= (int)  get_field( "request_id", $post->ID );
$project_id 	= (int)  get_field( "project_id",  $post->ID );
$project 				= get_post( $project_id );
 
$pay 									=   get_field( "pay", $project_id );
$keyword 							=   get_field( "keyword",$project_id );
$select_currency 			=   get_field( "select_currency", $project_id );
$timer_enable 				=   get_field( "timer_enable", $project_id );
$duration 						=   get_field( "duration", $project_id );
$expiration_date 			=   get_field( "expiration_date", $project_id );
 
// echo "<pre> members "; print_r( $members ); echo "</pre> ";  
// echo "<pre>  request_exist "; print_r( $request_exist ); echo "</pre> ";  
// echo "<pre>  user_request "; print_r( $user_request ); echo "</pre> ";  
// echo "<pre>  args "; print_r( $args ); echo "</pre> ";  


$user_currency 	= EmpHelper::getUserCurrency($current_user->ID);
$price 					= get_post_meta($project_id, 'price', true);  
$user_price   	= EmpHelper::cc_base_to_currency($user_currency,$price);



$is_premium = false; 
$can_review = false;
$review = null;

if ($task_status == 'completed'){
	// check if review already submitted
	$args = array(
		'post_type'              => array( 'review' ),
		'meta_query'             => array(
			array(
				'key'     => 'member_id',
				'value'   => $userData->ID,
			),
			array(
				'key'     => 'task_id',
				'value'   => $post->ID,
			),
		),
	);
	$review_query = new WP_Query( $args );
	$review_exist = $review_query->posts;
	if(!empty($review_exist)){ 
		$review = $review_exist[0];  
	}else{
		$can_review = true;
	}
}


?>



 <div class="p_entry row project_item project_<?php echo $post->ID;?>">
 		
 		<div class="jhead">
 			
 			<div class="col p_pay">
 				<!-- <span><?php echo EmpHelper::currency_to_code($select_currency).' '. number_format($pay,2); ?></span> -->
 				<span><?php echo EmpHelper::currency_to_code($user_currency).' '. number_format($user_price,2); ?></span>
 			</div>

 			<div class="col p_infoButton">
      	<span class="expand" data-pid="<?php echo $post->ID; ?>">Info</span>
      	<span class="collapse" data-pid="<?php echo $post->ID; ?>">Info</span>
       </div>


 			<div class="col p_title p_title_expandable" data-pid="<?php echo $post->ID; ?>">
 				<span class="p_name"><?php echo $project->post_title; ?></span>
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
 				</span>
 			</div>

 			<div class="col p_action">
 					<?php if($can_review){ ?>
 						<a class="btn btn-sm p_add_review add_review_btn capitalize"><?php _e('Add Review', 'emp');?></a>
 					<?php
 					}else{ ?>
 						<a class="btn btn-sm p_task_btn capitalize"><?php echo $task_status;?></a>
 					<?php
 					}?>
 					
 			</div>

 		</div>
 		<!-- jhead -->


 		<?php if($can_review): ?>
 			<div class="review_submit hidden">
 				<div class="review_form">
				  <div class="col-md-12">
				    <form action="" method="post" class="empfohlen_form empfohlen_form_review" id="empfohlen_form_review" enctype="multipart/form-data">
						   <div class="field-wrapper">
						      <label for="rf_title"><?php _e("Title","emp"); ?></label>
						      <input id="rf_title" name="review_title" type="text"/>
						   </div>
						   <div class="field-wrapper">
						      <label for="review_content"><?php _e("Description","emp"); ?></label>
						      <div class="content_edit">
						        <?php
						        $content = ''; //This content gets loaded first.';
						        $editor_id = 'review_content';
						        wp_editor( $content, $editor_id );
						        ?>
						      </div>
						   </div>

						   <div class="field-wrapper">
						   	<div class="review_files_upload_cont">
						   		<a class="add_review_file">+ Add File</a>
                </div>

						   </div>


						   <div class="field-wrapper text_right">
						     <button class="btn btn-sm btn_small ticket_form_submit wauto capitalize">Submit</button>
						   </div>
						   <input type="hidden" name="emp_submit_review_nonce" value="<?php echo wp_create_nonce('emp-submit-review-nonce'); ?>"/>
						   <input type="hidden" name="task_id" value="<?php echo $post->ID;?>"/>
						   <input type="hidden" name="action" value="submit_review" />
				   </form>
				  </div>
			</div>
		</div>
		<?php endif; ?>


 		<div class="jbody">
 			<div class="jbody_info">
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

					 

 			</div> <!-- jbody_info end -->
 				

 				
 		</div>

 		<div class="jfooter"></div>

 </div>


<?php

 