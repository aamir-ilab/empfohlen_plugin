<?php if ( ! defined( 'ABSPATH' ) ) exit; 

$empfohlen_setting_options = get_option( 'emp_setting' );
$emp_currency = $empfohlen_setting_options['emp_currency']; // Currency
$current_user = wp_get_current_user();
$userData = $current_user->data;

// $test =  get_term_by('id', 8);
// echo "<pre> get_current_user_id "; print_r( get_current_user_id() ); echo "</pre> ";  
// echo "<pre> userData  "; print_r($userData  ); echo "</pre> ";  
// echo '<p> Overview </p>';
$user_groups = get_the_terms( (int) $userData->ID, 'user-group');
// echo "<pre> user_groups "; print_r( $user_groups ); echo "</pre> ";  


$args = array(
  'post_type'              => array( 'task' ),
  'meta_query'             => array(
    array(
      'key'     => 'member_id',
      'value'   => $userData->ID,
    ),
  ),
);
$my_tasks = new WP_Query( $args );

$task_project_ids = array(0);
if(!empty($my_tasks)){
    $task_ids = wp_list_pluck( $my_tasks->posts,'ID');
    if(!empty($task_ids)){
     $sql_query =  "SELECT meta_value FROM wp_postmeta WHERE wp_postmeta.meta_key = 'project_id' AND wp_postmeta.post_id IN (".implode(',',$task_ids).")";
     $results = $wpdb->get_results($sql_query);  
     $task_project_ids = wp_list_pluck( $results, 'meta_value' );
    }
   // echo "<pre>  task_project_ids "; print_r(  $task_project_ids ); echo "</pre> ";  
}

// $task_exist = $task_query->posts;
// echo "<pre> task_exist "; print_r( $task_exist ); echo "</pre> ";  exit;


$member_project = new WP_Query( 
      array( 
        'post_type' => 'project', 
        'posts_per_page' => 10 ,
        'meta_query' => array(
          array(
            'key' => 'members',  
            'value' => '"' . get_current_user_id() . '"',  
            'compare' => 'LIKE'
          ),
         'post__not_in' =>  $task_project_ids,
        ),
      ) 
);


// echo "<pre> user_groups "; print_r( $user_groups ); echo "</pre> ";  
$user_skill       =   get_user_meta($userData->ID, 'user_skill', true);
// echo "<pre> user_skill "; print_r( $user_skill ); echo "</pre> ";  



$project = null;
if(!empty($user_skill)){
  // WP_Query arguments
  $args = array(
    'post_type'              => 'project',
    'post__not_in'          =>  $task_project_ids,
    'tax_query'              => array(
      'relation' => 'OR',
    ),
    'meta_query'             => array(
      'relation' => 'OR',
      array(
        'key'     => 'members',
        'value'   => '"'.get_current_user_id().'"',
        'compare' => 'NOT LIKE',
      ),
    ),

    'orderby'   => 'meta_value',
    'meta_type'   => 'DATETIME',
    'meta_key'  => 'expiration_date',

    // 'orderby' =>  'expiration_date',
    'order'   => 'DESC',
  ); 
  // foreach ($user_groups as $ug_key => $user_group) {
  //    $args['tax_query'][] =  array(
  //       'taxonomy'         => 'user-group',
  //       'terms'            => $user_group->term_id,
  //       'operator'         => 'IN',
  //     );
  // }
  foreach ($user_skill as $us_key => $u_skill) {
     $args['tax_query'][] =  array(
        'taxonomy'         => 'skill',
        'terms'            => $u_skill,
        'operator'         => 'IN',
      );
  }

  // echo "<pre> args "; print_r( $args ); echo "</pre> "; 
  $project = new WP_Query( $args );
}


// SELECT SQL_CALC_FOUND_ROWS wp_posts.ID 
// FROM wp_posts 
// LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) 
// INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) 
// WHERE 1=1 
// AND wp_posts.ID NOT IN (462) 
// AND ( 
//     wp_term_relationships.term_taxonomy_id IN (27) OR 
//     wp_term_relationships.term_taxonomy_id IN (25) OR 
//     wp_term_relationships.term_taxonomy_id IN (28) OR 
//     wp_term_relationships.term_taxonomy_id IN (22) OR 
//     wp_term_relationships.term_taxonomy_id IN (26) OR 
//     wp_term_relationships.term_taxonomy_id IN (30) 
//   ) 
// AND ( 
//   ( wp_postmeta.meta_key = 'members' 
//     AND wp_postmeta.meta_value NOT LIKE '{13da253306bcb75251c19f411778febcaaef954696af04370509b59cd39a1cae}\"32\"{13da253306bcb75251c19f411778febcaaef954696af04370509b59cd39a1cae}' 
//     ) 
// ) 
// AND wp_posts.post_type = 'project' 
// AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'acf-disabled' OR wp_posts.post_author = 32 AND wp_posts.post_status = 'private') 

// GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 0, 10
 
// echo "Last SQL-Query: {$project->request}";




   // $the_query = new WP_Query( 
   //        array('posts_per_page'=>30,
   //          'post_type'=>'phcl',
   //          'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
   //      ); 
  
?>


<div class="projectList content_bg">
    <h3 class="dinline p_task_heading"><?php _e('Available tasks','emp');?></h3>
    <div class="task_list">  
      <?php
        while ( $member_project->have_posts() ) : $member_project->the_post();  
           include(EMPFOHLEN_DIR.'public/partials/member/project_row_new.php');
           // include(EMPFOHLEN_DIR.'public/partials/member/project_row.php');
        endwhile;    
        
        if( $project ){
          while ( $project->have_posts() ) : $project->the_post();  
            include(EMPFOHLEN_DIR.'public/partials/member/project_row_new.php');
          endwhile; 
        }
       
      ?>
    </div>
</div><!-- projectList -->







<div class="my_projectList content_bg">
    <h3 class="dinline p_task_heading"><?php _e('Task Already done');?></h3>
  <?php
    while ( $my_tasks->have_posts() ) : $my_tasks->the_post();  
       include(EMPFOHLEN_DIR.'public/partials/member/task_row_new.php');
    endwhile;         
  ?>

</div><!-- projectList -->

 


<style type="text/css">
  .project_item{
    margin: 10px 5px;
/*    border-bottom: 1px solid #9E9E9E; */
  }
  .project_item .p_content{ /*display: none;*/  
  transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.26s ease;
    height: 0;
    overflow: hidden;
    padding: 0;
  }

   .project_item.show_detail .p_content{ /*display: block !important;*/ 
  transform: scaleY(1);
    height: fit-content;
    padding: 40px 35px;

  } 

  .project_item .p_info { width: 100%; display:block;  }
  .project_item .p_pay { padding: 20px 10px;  min-width: 90px;  display: inline-block;  }
  .project_item .p_info_btn { /*width: 10%;*/ display: inline-block;  }
  .project_item .p_title {  width: 40%; display: inline-block; }
  .project_item .p_keyword {  width: 20%; display: inline-block; }
  .project_item .p_kwd {  border: 1px solid black; }
  .project_item .p_request  { width: 15%; display: inline-block;}
/*  .project_item  .btn.btn-sm {
    padding: 0.8em 0.5em;
    font-size: 1.4rem;
    border-radius: 0.4em; 
  }*/


  .p_entry{  
    background: rgba(0,0,0,.04);
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    margin-bottom: 10px;
 }

.col.p_infoButton {
    width: auto;
    display: inline-block;
    padding: 20px 10px;
}

.col.p_title {
    width: auto;
    display: inline-block;
    cursor: pointer;
}

.col.p_action {
   width: auto;
    display: inline-block;
    float: right;
    padding: 20px;
}

.col.p_pay span {
      padding: 0px 8px;
    display: inline-block;
    width: 100%;
    font-weight: 600;
    font-size: .875em;
    color: #fff;
    background: #302b63;
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    line-height: 25px;
    text-align: center;
}
 .p_title span.p_name {
    font-size: 1.065em;
    margin-right: 10px;
    font-weight: 500;
}

.p_title span.p_label {
    font-size: .75em;
    color: #dd1005;
    border: 1px solid #dd1005;
    padding: 0 3px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
}
.p_title .p_labels { margin: 0px 5px; }
.p_entry.project_item .jbody { display: none; }
.p_entry.project_item.show_detail .jbody {
    /*min-height: 1000px;*/
    display: block;
    color: #fff;
    background: rgba(0,0,0,.5);
    padding: 10px;
    font-size: .875em;
    text-align: center;
    margin: 0 5px;
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
.jbody .category{ color: white;  }



.benefits {
    width: auto;
    display: inline-block;
    min-width: 40%;
    background: url(<?php echo EMPFOHLEN_URI?>images/bg_effekt.png) no-repeat center;
    background-size: cover;
    -webkit-border-radius: 15px;
    -moz-border-radius: 15px;
    border-radius: 15px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    -webkit-box-shadow: 0 10px 150px 25px rgba(255,255,255,.5);
    -moz-box-shadow: 0 10px 150px 25px rgba(255,255,255,.5);
    box-shadow: 0 10px 150px 25px rgba(255,255,255,.5);
}

.benefit {
    text-align: left;
    margin: 5px 10px;
    padding: 5px 0;
    border-bottom: .5px solid rgba(255,255,255,.5);
}

.project_item .benefit * {
    color: white;
}
.benefitInner {
    display: inline-block;
    width: 100%;
}
 
 .benefits .benefit .benefitInner>div.label {
    float: left;
    width: calc(100% - 70px - 10px - 10px);
    height: 40px;
    color: #fff;
}
.benefits .benefit .benefitInner>div {
    line-height: 40px;
}
.benefits .benefit .benefitInner>div.value {
    height: 40px;
    text-align: center;
    font-size: .875em;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: auto 34px;
    float: right;
    width: 70px;
    height: 40px;
    color: #000;
}

.p_desc *,
.p_desc p{  color: white !important; }

.p_duration.color_white {
    font-size: 12px;
    font-weight: bold;
    line-height: 22px;
}


.benefits .benefit.praemien .value {
    background-image: url(<?php echo EMPFOHLEN_URI?>images/benefit_praemien.svg);
}
.benefits .benefit.reward .value {
    background-image: url(<?php echo EMPFOHLEN_URI?>images/benefit_reward.svg);
}
.benefits .benefit.premium .value {
    background-image: url(<?php echo EMPFOHLEN_URI?>images/benefit_premium.svg);
}
.benefits .benefit.moreJobs .value {
    background-image: url(<?php echo EMPFOHLEN_URI?>images/benefit_more_jobs.svg);
}
.benefits .benefit.higherRewards .value {
    background-image: url(<?php echo EMPFOHLEN_URI?>images/benefit_higher_rewards.svg);
}

.timer_icon  {
    background: url(<?php echo EMPFOHLEN_URI?>images/dauer_icon.svg) left center no-repeat;
    background-size: 12px;
    padding-left: 18px;
    line-height: 22px;
    display: inline-block;
}
.is_typing{ margin: 0px !important;   }
button.btn.p_start_project,
button.btn.p_continue_project {
    padding: 5px 10px;
    width: auto;
    border: none;
}
</style>