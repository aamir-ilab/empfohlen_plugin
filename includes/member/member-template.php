<?php
/*
 * Template Name: MemberDasboard
 * Description: A Page Template with empfohlen member dashboard.
 */

get_header();

global $wp;
$current_url = add_query_arg($wp->query_string, '', home_url($wp->request));

$tmpl = get_query_var('tmpl');
$tmpl =  isset($_GET['tmpl'])?($_GET['tmpl']):'overview';
$sef_current_url = home_url(add_query_arg(array(),$wp->request));
// echo "<pre>  sef_current_url "; print_r( $sef_current_url ); echo "</pre> "; 

$current_user = wp_get_current_user();
$userData = $current_user->data;

// echo "<pre> userData "; print_r( $userData ); echo "</pre> ";  
$account_activated =   get_user_meta($userData->ID, 'account_activated', true);


if ( ! session_id() ) { session_start(); }

if( $account_activated ):


// account_verified_success
	// $_SESSION['account_verified_success'] = 'Thank you for verification of email'; 
if(isset($_SESSION['account_verified_success']) && !empty($_SESSION['account_verified_success']) ): ?>
<div class="cmodal relative alert alert-success alert-dismissible mt_20" style="background-color: #8bc34ad4;color: white;font-weight: bold;">
  <div class="close_nofi">
  <a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
  </div>
  <strong><?php echo __('Success!','empfohlen')?></strong> <?php echo $_SESSION['account_verified_success'];?>
</div>
<?php
 unset($_SESSION['account_verified_success']); 
 endif;  
?>
	
<div class="fusion-column-wrapper" style="width:100%;">
<div class="content-area memberDashboard">
<!-- h4 style="text-align: center">Member Dashboard </h4> -->
		 <div class="tabs">
		 		<ul class="tabBar">
		 			<li class="<?= $tmpl == 'overview'? 'active': ''?>" name="overview">
		 				<a href="<?php echo get_permalink() ?>?tmpl=overview"><?php echo __('Overview','empfohlen')?></a>
		 			</li>
		 			<li class="<?= $tmpl == 'jobs'? 'active': ''?>" name="jobs">
		 				<a href="<?php echo get_permalink() ?>?tmpl=jobs"><?php echo __('Jobs','empfohlen')?></a></li>
		 			<li class="<?= $tmpl == 'setting'? 'active': ''?>" name="settings">
		 				<a href="<?php echo get_permalink() ?>?tmpl=setting"><?php echo __('Settings','empfohlen')?></a></li>
		 			<li class="<?= $tmpl == 'pay'? 'active': ''?>" name="transactions">
		 				<a href="<?php echo get_permalink() ?>?tmpl=pay"><?php echo __('Paying out','empfohlen')?></a></li>
		 			<li class="<?= $tmpl == 'tickets'? 'active': ''?>" name="tickets">
		 				<a href="<?php echo get_permalink() ?>?tmpl=tickets"><?php echo __('Tickets','empfohlen')?></a></li>
		 			<li class="<?= $tmpl == 'reviews'? 'active': ''?>" name="reviews">
		 				<a href="<?php echo get_permalink() ?>?tmpl=reviews"><?php echo __('Review','empfohlen')?></a></li>
		 		</ul>
		 </div>

		 <?php
		 if(isset($_SESSION['login_success']) && !empty($_SESSION['login_success']) ): ?>
		 <div class="welcome_box">
		 		<div class="nofi_bottom_pointer mb_20">	
		 		<div class="cmodal relative alert alert-success alert-dismissible bg_black_shad m_0 border_radius_25" style="color: white;font-weight: bold;">
				  <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
				  <strong><?php echo __('Welcome Message!','empfohlen')?></strong><?php echo __('Unlock new orders by completing the available tasks.','empfohlen')?>
				</div>
				</div>
		 </div>
		 <?php
		 	unset($_SESSION['login_success']); 
			endif;  

			// show general success info bars. 
		  if(isset($_SESSION['success']) && !empty($_SESSION['success'])):
		 	if(is_array($_SESSION['success'])){ 
				foreach ($_SESSION['success'] as $success_message) { ?>
					<div class="welcome_box">
					 	<div class="nofi_bottom_pointer mb_20">	
					 		<div class="cmodal relative alert alert-success alert-dismissible bg_black_shad m_0 border_radius_25" style="color: white;font-weight: bold;">
							  <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
							   <?php echo $success_message; ?>
							</div>
						</div>
		 			</div>
		 		<?php	
				}
			}else{ ?>
				<div class="welcome_box">
					 	<div class="nofi_bottom_pointer mb_20">	
					 		<div class="cmodal relative alert alert-success alert-dismissible bg_black_shad m_0 border_radius_25" style="color: white;font-weight: bold;">
							  <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
							   <?php echo $_SESSION['success']; ?>
							</div>
						</div>
		 			</div>
		 	<?php		
			}
		 	unset($_SESSION['success']); 
			endif; 



			// show general error infos 
			if(isset($_SESSION['error']) && !empty($_SESSION['error'])):
		 	if(is_array($_SESSION['error'])){ 
				foreach ($_SESSION['error'] as $error_message) { ?>
					<div class="welcome_box">
					 	<div class="nofi_bottom_pointer mb_20">	
					 		<div class="cmodal relative alert alert-error alert-dismissible bg_black_shad m_0 border_radius_25" style="color: white;font-weight: bold;">
							  <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
							   <?php echo $error_message; ?>
							</div>
						</div>
		 			</div>
		 		<?php	
				}
			}else{ ?>
				<div class="welcome_box">
					 	<div class="nofi_bottom_pointer mb_20">	
					 		<div class="cmodal relative alert alert-error alert-dismissible bg_black_shad m_0 border_radius_25" style="color: white;font-weight: bold;">
							  <div class="close_nofi"><a  href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>
							   <?php echo $_SESSION['error']; ?>
							</div>
						</div>
		 			</div>
		 	<?php		
			}
		 	unset($_SESSION['error']); 
			endif; 
		 ?>


		 <div class="content">
		 	<?php
		 	  if( $tmpl == 'overview' ){

		 	  	  load_template(EMPFOHLEN_DIR.'includes/member/templates/overview.php');

		 	  }elseif ($tmpl == 'jobs') {
		 	  	
		 	  	load_template(EMPFOHLEN_DIR.'includes/member/templates/jobs.php');
		 	  
		 	  }elseif ($tmpl == 'setting') {
		 	  
		 	  	load_template(EMPFOHLEN_DIR.'includes/member/templates/setting.php');
		 	  
		 	  }elseif ($tmpl == 'pay') {

		 	  	// load_template(EMPFOHLEN_DIR.'includes/member/templates/pay.php');
		 	  	load_template(EMPFOHLEN_DIR.'includes/member/templates/pay_new.php');
		 	  
		 	  }elseif ($tmpl == 'tickets') {

		 	  	load_template(EMPFOHLEN_DIR.'includes/member/templates/tickets.php');
		 	  
		 	  }elseif ($tmpl == 'reviews') {

		 	  	load_template(EMPFOHLEN_DIR.'includes/member/templates/reviews.php');
		 	  
		 	  }
		 	?>
		 </div>

	</div> 
 </div>

<?php

else: 
?>
	
<div class="fusion-column-wrapper" style="width:100%;">
	<div class="content-area memberDashboard text_center">
		<h3 class="color_white"><?php _e('Please Verifiy your email address', 'emp');?></h3>
		<div class="resend_vemail ">
			<button class="btn btn-sm resend_v_email_btn wauto"><?php _e('Resend Verification Email', 'emp');?></button>
			<div class="resend_v_email_message"></div>
			<input type="hidden" id="email_verification_nonce" name="email_verification_nonce" value="<?= wp_create_nonce('email-verification-nonce'); ?>"/>
		</div>
	</div>
</div>

<?php
endif; 


echo do_shortcode('[chat_box]');
get_footer();