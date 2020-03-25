<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>
 


 <form id="emp_registration_form" class="emp_form" action="" method="post">
	 
	
		<div class="emp_reg_message">
			<?php 

			global $_SESSION;
    	$success   =  isset($_SESSION['reg_success'])?$_SESSION['reg_success']:'';
    	$error     =  isset($_SESSION['reg_error'])?($_SESSION['reg_error']):'';
    	
    	if(isset($_SESSION['reg_success'])){ unset($_SESSION['reg_success']); } 
    	if(isset($_SESSION['reg_error'])){ unset($_SESSION['reg_error']); } 

    	// echo "<pre> success "; print_r( $success ); echo "</pre> ";  
    	// echo "<pre> error "; print_r( $error ); echo "</pre> ";  

		?>
		</div>

		<div class="field-wrapper two">
        <div class="two-left">
            <label for="emp_user_first"><?php _e('First Name', 'empfohlen'); ?></label><br>
            <input id="emp_user_first" name="emp_user_first"   type="text" placeholder="First name">
        </div>
        <div class="two-right">
            <label for="emp_user_last"><?php _e('Surname', 'empfohlen'); ?></label><br>
            <input name="emp_user_last" id="emp_user_last" type="text"/>
        </div>
    </div>

    <div class="field-wrapper two">
        <div class="two-left">
            <label for="emp_user_Login"><?php _e('Username', 'empfohlen'); ?></label><br>
            <input name="emp_user_login" id="emp_user_login" class="required" type="text"/>
        </div>
        <div class="two-right">
            <label for="emp_user_email"><?php _e('Email', 'empfohlen'); ?></label><br>
            <input name="emp_user_email" id="emp_user_email" class="required" type="email"/>
        </div>
    </div>



    <div class="field-wrapper two">
        <div class="two-left">
            <label for="password"><?php _e('Password', 'empfohlen'); ?></label><br>
            <input name="emp_user_pass" id="password" class="required" type="password"/>
        </div>
        <div class="two-right">
            <label for="password_again"><?php _e('Password Again', 'empfohlen'); ?></label><br>
            <input name="emp_user_pass_confirm" id="password_again" class="required" type="password"/>
        </div>
    </div>



    <div class="field-wrapper">
        <label for="emp_user_birthday"><?php _e('Birthday', 'empfohlen'); ?></label><br>
        <input name="emp_user_birthday" id="emp_user_birthday" type="text"/>
    </div>

    <div class="field-wrapper">
        <label for="emp_user_address"><?php _e('Address', 'empfohlen'); ?></label><br>
        <textarea rows="4" cols="50" name="emp_user_address" id="emp_user_address"></textarea>
    </div>

		 


		<div class="field-wrapper eug-radio">
			<!-- <h4>Relevant information</h4> -->
			<?php
				$skill = get_terms( 
						'skill', 
						array(
							'hide_empty' 		=> false,
							'parent'            => 0,
						)
					);
			if(!empty($skill)){
				foreach ($skill as $skey => $p_skill) {
					if( $p_skill->count > 0 ){
						$c_skills = get_terms('skill', array('parent'   => $p_skill->term_id, 'hide_empty' => false));
						 // echo "<pre> c_skills "; print_r( $c_skills ); echo "</pre> ";  
						 if(!empty($c_skills)){ ?>

						 	<div class="skills_cont">
						 		<p><?php echo $p_skill->name; ?></p>
						 		<ul class="c_skills_list">
						 			<?php 
						 			foreach ($c_skills as $cskey => $c_skill) { ?>
						 					<div class="eug-radio-item cskill_<?php echo $c_skill->term_id ?>">
						 						<label class="selectit">
						 							<input 
						 								value="<?php echo $c_skill->term_id; ?>" 
						 								type="checkbox" 
						 								name="tax_skill[<?php echo $c_skill->term_id;?>]" 
						 								id="in-skill-<?php echo $c_skill->term_id ?>"> <?php echo $c_skill->name; ?>
						 						</label>
						 					</div>
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
			}
			?>
    </div>
	
		<input type="hidden" name="emp_register_nonce" value="<?php echo wp_create_nonce('emp-register-nonce'); ?>"/>	   
	  <input type="hidden" name="action" value="emp_registeration_submit"> 
		<div class="submit-wrapper"><button type="submit"><?php echo _e('Register Your Account', 'empfohlen');?></button></div>

</form>