<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );
$emp_currency = $empfohlen_setting_options['emp_currency']; // Currency
$current_user = wp_get_current_user();
$userData = $current_user->data;
 

 $project = new WP_Query( 
 			array( 
 				'post_type' => 'project', 
 				'posts_per_page' => 10 ,
 				'meta_query' => array(
					array(
						'key' => 'members',  
						'value' => '"' . get_current_user_id() . '"',  
						'compare' => 'LIKE'
					),
				),

 
 			) 
 	);


 

  
  ?>
 