<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );
$emp_currency = $empfohlen_setting_options['emp_currency']; // Currency
$current_user = wp_get_current_user();
$userData = $current_user->data;
// $user_groups = get_the_terms( (int) $userData->ID, 'user-group');

 $reviews = new WP_Query( 
 			array( 
 				'post_type' => 'review', 
 				'posts_per_page' => 10 ,
 				'meta_query' => array(
					array(
						'key'     => 'member_id',
            'value'   => $userData->ID,
					),
				),
 			) 
 	);
  



// global $_SESSION;
// $success   =  isset($_SESSION['success'])?$_SESSION['success']:'';
// $error     =  isset($_SESSION['error'])?($_SESSION['error']):'';

// if(isset($_SESSION['success'])){ unset($_SESSION['success']); } 
// if(isset($_SESSION['error'])){ unset($_SESSION['error']); } 

 
  
?>
 




<div class="reviewList content_bg">
  <h3 class="dinline p_task_heading p_review_heading"><?php _e('My Reviews','empfohlen');?></h3>
   <div class="review_list">
       <?php
        while ( $reviews->have_posts() ) : $reviews->the_post();     
          include(EMPFOHLEN_DIR.'public/partials/member/review_row_new.php');
        endwhile;    
      ?>
   </div>
</div>




 


<style type="text/css">
.col.review_infoButton {
    width: auto;
    display: inline-block; 
    padding: 20px 10px;
 }

.review_item.t_item.show_description .review_body{ 
   display: block !important;
    color: #fff;
    background: rgba(0,0,0,.5);
    padding: 10px;
    font-size: .875em;
    margin: 0 5px;
    margin-bottom: 10px;
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    -moz-transition: max-height .3s ease-in-out;
    -webkit-transition: max-height .3s ease-in-out;
    -o-transition: max-height .3s ease-in-out;
    -ms-transition: max-height .3s ease-in-out;
    transition: max-height .3s ease-in-out;
}
.review_item.t_item {
    background: rgba(0,0,0,.04);
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    margin-bottom: 10px;
    margin: 10px 5px;
}
 .t_content.color_white {
    padding: 10px 20px;
}
.col.review_id_code {
    padding: 20px 10px;
    min-width: 90px;
    display: inline-block;
}
.col.review_title,
.col.review_project {
    display: inline-block;
    cursor: pointer;
    width: auto;
    font-size: 1.065em;
    margin-right: 10px;
    font-weight: 500;
}
.col.tick_date {
    width: auto;
    display: inline-block;
    float: right;
    padding: 20px;
}

.review_item.show_description .review_body{ display: block !important;  }
.review_body { display: none; }

.col.review_id_code span {
    padding: 0px 8px;
    display: inline-block;
    width: 100%;
    font-weight: 600;
    font-size: .875em;
    color: #fff;
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    line-height: 25px;
    text-align: center;
        color: #fff;
    background: #509753;
}
.t_item.review_item.status_resolved { background: #9e9e9e80; }
.review_infoButton span.expand {
    background-image: url(<?php echo EMPFOHLEN_URI; ?>images/icon-expand_job.svg);
    background-position: right 2px center;
    background-repeat: no-repeat;
    background-size: 21px;
    background-color: #fff;
    border-radius: 20px;
    padding: 5px 40px 5px 8px;
    font-size: 14px;
} 
.review_infoButton span.collapse {
    display: none;
    background-image: url(<?php echo EMPFOHLEN_URI; ?>images/icon-collapse_job.svg);
    background-position: right 2px center;
    background-repeat: no-repeat;
    background-size: 21px;
    border-radius: 20px;
    padding: 5px 40px 5px 8px;
    font-size: 14px;
    background-color: rgba(0,0,0,.1);
}

.review_item.t_item.show_description .review_infoButton span.expand{ display: none; }
.review_item.t_item.show_description .review_infoButton span.collapse { display: block; }



</style>