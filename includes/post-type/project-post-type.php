<?php
/**
 * Custom Post Type Project
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_project_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_project',
        'edit_post'              => 'edit_project',
        'read_post'              => 'read_project',
        'delete_post'            => 'delete_project',
        // primitive/meta caps
        'create_posts'           => 'create_projects',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_projects',
       'publish_posts'          => 'publish_projects',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_projects',
    );
    return apply_filters( 'empfohlen_get_project_capabilities', $caps );
}

if( !function_exists( 'empfohlen_project_post_type' ) ){
    function empfohlen_project_post_type(){
         $labels = array(
		'name'                  => _x( 'Projects', 'Post Type General Name', 'emp' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'emp' ),
		'menu_name'             => __( 'Project', 'emp' ),
		'name_admin_bar'        => __( 'Project', 'emp' ),
		'archives'              => __( 'Project Archives', 'emp' ),
		'attributes'            => __( 'Project Attributes', 'emp' ),
		'parent_item_colon'     => __( 'Parent Project:', 'emp' ),
		'all_items'             => __( 'All Projects', 'emp' ),
		'add_new_item'          => __( 'Add New Project', 'emp' ),
		'add_new'               => __( 'Add New Project', 'emp' ),
		'new_item'              => __( 'New Project', 'emp' ),
		'edit_item'             => __( 'Edit Project', 'emp' ),
		'update_item'           => __( 'Update Project', 'emp' ),
		'view_item'             => __( 'View Project', 'emp' ),
		'view_items'            => __( 'View Projects', 'emp' ),
		'search_items'          => __( 'Search Projects', 'emp' ),
		'not_found'             => __( 'Project Not found', 'emp' ),
		'not_found_in_trash'    => __( 'Project Not found in Trash', 'emp' ),
		'featured_image'        => __( 'Featured Image', 'emp' ),
		'set_featured_image'    => __( 'Set featured image', 'emp' ),
		'remove_featured_image' => __( 'Remove featured image', 'emp' ),
		'use_featured_image'    => __( 'Use as featured image', 'emp' ),
		'insert_into_item'      => __( 'Insert into item', 'emp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'emp' ),
		'items_list'            => __( 'projects list', 'emp' ),
		'items_list_navigation' => __( 'projects list navigation', 'emp' ),
		'filter_items_list'     => __( 'Filter projects list', 'emp' ),
	);
	 
	 $args = array(
            'label'                 => __( 'Project', 'emp' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_project_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title', 'thumbnail' ),
        );


	register_post_type( 'project', $args );

    }
}
add_action( 'init', 'empfohlen_project_post_type' );




// add_action('admin_menu', 'my_admin_menu'); 
// function my_admin_menu() { 
//     add_submenu_page(
//     		'empfohlen', 
//     		'Projects', 'EMP Projects', 
//     		'manage_options', 
//     		'edit.php?post_type=project'); 
// }





function add_project_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_project' ); 
    $admins->add_cap( 'read_project' ); 
    $admins->add_cap( 'delete_project' ); 
    $admins->add_cap( 'create_projects' ); 
    $admins->add_cap( 'edit_projects' ); 
    $admins->add_cap( 'publish_projects' ); 
    $admins->add_cap( 'delete_projects' ); 
    $admins->add_cap( 'publish_projects' ); 

}
add_action( 'admin_init', 'add_project_caps');











// custom meta boxes 
add_action( 'add_meta_boxes',  'add_meta_boxes_project_requet_list' );
 function add_meta_boxes_project_requet_list() {
     
      add_meta_box('meta_boxes_project_requet_list',
            __( 'Request List', 'emp_task' ),
            'add_meta_boxes_project_reques_callback',
            'project',
            'normal',
            'low'
        );

      add_meta_box('meta_boxes_project_requet_list_2',
            __( 'Payment in USD', 'emp_task' ),
            'add_meta_boxes_project_amount_callback',
            'project',
            'side',
            'low'
        );
}
 
 function add_meta_boxes_project_reques_callback( $post, $metabox ) {
    // wp_nonce_field( 'advanced_options_data', 'advanced_options_nonce' );

    // $task_request_id = (int) get_post_meta( $post->ID, 'request_id', true );
    $args = array(
        'post_type'              => array( 'request' ),
        'meta_query'             => array(
            array(
                'key'     => 'select_project_id',
                'value'   => $post->ID,
            ),
        ),
    );
     
     $request_query = new WP_Query( $args );
     $request_exist = $request_query->posts;
 

     $output = '<div class="project_requests">'; 
      $output .= '<div class="table w100">';
      $output .= '<div class="trow">';
        $output .= '<span class="th bold" style="text-align:left">#</span>';
        $output .= '<span class="th bold" style="text-align:left">Request Title</span>';
        $output .= '<span class="th bold" style="text-align:left">Request Code</span>';
        $output .= '<span class="th bold" style="text-align:left">Request Status</span>';
        $output .= '<span class="th bold" style="text-align:left">Request Member</span>';
      $output .= '</div>'; 


     if(!empty($request_exist)) {
        foreach ($request_exist as $pr_key => $request) {
            $request_status = get_post_meta($request->ID,'request_status', true);
            $request_id_code = get_post_meta($request->ID,'request_id', true);
            $request_member_id = (int) get_post_meta($request->ID,'member_id', true);
            
            $r_member = get_userdata( $request_member_id );
            $output .= '<div class="project_request trow pr_'.$pr_key.'">'; 
                $output .= '<span class="td">'.($pr_key+1).'</span>';
                $output .= '<span class="td"><a href="'.get_edit_post_link($request->ID).'">'.$request->post_title.'</a></span>';
                $output .= '<span class="td">'.$request_id_code.'</span>';
                $output .= '<span class="td">'.$request_status.'</span>';
                $output .= '<span class="td">'.$r_member->data->display_name.'</span>';
            $output .= '</div>';

            if($request_status == 'Accepted' || $request_status == 'accepted') {
              // get task detail for this request 
              // $task = get_field('task_',$request->ID);

              $args = array(
                'post_type'              => array( 'task' ),
                'meta_query'             => array(
                    array(
                        'key'     => 'request_id',
                        'value'   => $request->ID,
                    ),
                ),
             );
             
             $task_query = new WP_Query( $args );
             $task_exist = $task_query->posts;
             if(!empty($task_exist)){
                $task = $task_exist[0]; 
                $task_status = get_field('task_status',$task->ID, true);

                $invoice = null;
                // check if task completed then get invoice detail 
                if($task_status == 'Completed' || $task_status == 'completed'){
                  $args = array(
                        'post_type'              => array( 'invoice' ),
                        'meta_query'             => array(
                            array(
                                'key'     => 'invoice_task_id',
                                'value'   => $task->ID,
                            ),
                        ),
                     );     
                  $invoice_query = new WP_Query( $args );
                  $invoice_exist = $invoice_query->posts;
                  if(!empty($invoice_exist)){ $invoice = $invoice_exist[0]; }
                } 


                 $output .= '<div class="trow task_row head">';
                    $output .= '<span class="th bold" style="text-align:left">#</span>';
                    $output .= '<span class="th bold" style="text-align:left">Task Title</span>';
                    $output .= '<span class="th bold" style="text-align:left">Task Code</span>';
                    $output .= '<span class="th bold" style="text-align:left">Task Status</span>';
                    $output .= '<span class="th bold" style="text-align:left">Invoice</span>';
                  $output .= '</div>'; 


                  $output .= '<div class="trow task_row">';
                    $output .= '<span class="th" style="text-align:left">'.$task->ID.'</span>';
                    $output .= '<span class="th" style="text-align:left">'.$task->post_title.'</span>';
                    $output .= '<span class="th" style="text-align:left">Task Code</span>';
                    $output .= '<span class="th" style="text-align:left">'.$task_status.'</span>';
                    $output .= '<span class="th" style="text-align:left">'; 
                    if($invoice):
                        $output .= '<a href="'.get_edit_post_link($invoice->ID).'">'.$invoice->post_title.'</a>';
                    endif; 
                    $output .='</span>';

                  $output .= '</div>';


                 


             }// task_exist

            }// request_status accepted 



        }
     }
    $output .= '</div></div>';
    echo $output;
}

 

function add_meta_boxes_project_amount_callback( $post, $metabox ) {

    // wp_nonce_field( 'hellow_data', 'hellow_nonce' );
    wp_nonce_field( 'base_pay_nonce', 'base-pay-nonce' );
    $pay              = get_field('pay');
    $select_currency  = get_field('select_currency');
    $base_amount      = EmpHelper::cc_tobase($select_currency,$pay);

    $price_ = get_post_meta($post->ID, 'price', true);

    echo '<input type="text" name="price" value="'.$price_.'" />';
    echo $base_amount;
}





add_action( 'save_post', 'save_post_project_baseprice' );


function save_post_project_baseprice( $post_id ) {

   // echo "<pre> post_id  "; print_r( $post_id ); echo "</pre> ";  exit; 

    if ( ! isset( $_POST['base-pay-nonce'] ) )
      return $post_id;

    $nonce = $_POST['base-pay-nonce'];
    if ( !wp_verify_nonce( $nonce, 'base_pay_nonce' ) )
      return $post_id;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return $post_id;
     
    // if (isset($_POST['price'])){
    //     $_POST['price'] = sanitize_text_field($_POST['price']);
    //     update_post_meta( $post_id, 'price', $_POST['price']);
    // }

   
    $pay              = get_field('pay');
    $select_currency  = get_field('select_currency');
    $base_amount      = EmpHelper::cc_tobase($select_currency,$pay);
    update_post_meta( $post_id, 'price', $base_amount);
    

  }



 /*================  Add columns to project post list ================*/
 function add_project_acf_columns ( $columns ) {
   $ret['cb']               = $columns['cb'];
   $ret['title']            = $columns['title'];
   $ret['taxonomy-skill']   = $columns['taxonomy-skill'];
   $ret['keywords']         = __ ( 'Key words', 'emp' );
   $ret['pay']              = __ ( 'Pay', 'emp' );
   $ret['date']             = $columns['date'];
   return $ret;
 }

 add_filter ( 'manage_project_posts_columns', 'add_project_acf_columns' );

 function project_custom_column ( $column, $post_id ) {
   switch ( $column ) {
     case 'keywords':
      $keywords = get_post_meta($post_id, 'keyword', true);
      if(!empty($keywords)){
          foreach ($keywords as $keyword) {
            $keyword_term = get_term( (int) $keyword);
             // echo "<pre> keyword_term  "; print_r( $keyword_term ); echo "</pre> ";  
            echo  !empty($keyword_term)?('<span class="keyword_term">'.$keyword_term->name.'</span>'):''; 
          }
      }
      break;
     case 'pay':
       echo get_post_meta ( $post_id, 'select_currency', true ).'  '.get_post_meta ( $post_id, 'pay', true );
       break;
   }
 }
 add_action ( 'manage_project_posts_custom_column', 'project_custom_column', 10, 2 );






 /*================  Filter the single_template with our custom function ================*/
add_filter('single_template', 'my_project_template', 99 );
function my_project_template($single) {
  global $post;
  if ( $post->post_type == 'project' ) { return EMPFOHLEN_DIR . 'public/partials/project/project.php'; }
    return $single;
}








/*================  Create Project Task  ================*/
// complete task and generate invoice. 
add_action( 'wp_ajax_nopriv_project_start_create_task', 'project_start_create_task' );
add_action( 'wp_ajax_project_start_create_task', 'project_start_create_task' );
function project_start_create_task() {
    
    // wp_send_json( $return); 
    if (!is_admin() || !defined( 'DOING_AJAX' ) || !DOING_AJAX ){
        // wp_send_json_success( 'admin is login and its ajax' ); 
        die();
    }// is_admin
    

    check_ajax_referer( 'project-start-nonce', 'security' );
    $return = array();
    $project_id = (int) $_POST['pid'];

    $current_user = wp_get_current_user();
    $userData = $current_user->data;
    $user_id = (int) $userData->ID;


    if ( $project_id ){
        $project = get_post($project_id);

        // check if project exist.
        if(empty($project)){
           $return['status']   = __('Error','emp');
           $return['message']  = __('Error creating task for this project','emp');
           wp_send_json( $return ); 
        }

        // check if project already expired or not. 
        $expiration_date      =   get_field( "expiration_date", $project_id );
        $isExpired            = EmpHelper::isExpired($expiration_date);
        if($isExpired ){
            $return['status']   = __('Error','emp');
            $return['message']  = __('Project Expired Date passed away.','emp');
            wp_send_json( $return ); 
        }


        // check if project has request disabled. 
        $request_enable = get_field( "request_enable", $project_id);
        if($request_enable){
            $return['status']   = __('Error','emp');
            $return['message']  = __('Need to submit a request to start work on this project.','emp');
            wp_send_json( $return ); 
        }


        // check if task already exist for this project 
        $args = array(
            'post_type'       => array( 'task' ),
            'meta_query'      => array(
                array(
                    'key'     => 'project_id',
                    'value'   => $project_id,
                ),
                array(
                    'key'     => 'member_id',
                    'value'   => $user_id,
                ),
                array(
                    'key'     => 'task_type_request',
                    'value'   => false,
                ),
            ),
        );

        $task_query = new WP_Query( $args );
        $task_exist = $task_query->posts;

         // echo "<pre> task_exist "; print_r(  $task_exist  ); echo "</pre> ";  

        if($task_exist){
            $return['status']   = __('Error','emp');
            $return['message']  = __('Task Already Exist.','emp');
            $task = $task_exist[0];
            $task_status = get_post_meta($task->ID,'task_status',true); 
            $return['data'] = '<a class="button button-success button-large  task_detail_btn" href="'.get_permalink($task->ID).'">'.__('Task Detail','emp').' ('.$task_status.')</a>';
            wp_send_json($return); 
         }else{

           // create task for this request 
            $task_id = wp_insert_post(array(
               'post_type'      => 'task',
               'post_title'     => 'Task for Project '.$project_id,
               'post_status'    => 'publish',
            ));


            $task_id_code = 'T'.$task_id.'_P'.$project_id;
            if ($task_id) {
               update_post_meta($task_id, 'task_status', 'pending');
               update_post_meta($task_id, 'task_id', $task_id_code);
               update_post_meta($task_id, 'member_id', $user_id);
               update_post_meta($task_id, 'task_type_request', false);
               update_post_meta($task_id, 'project_id', $project_id);
              
              $return['status']  = __('success');
              $return['message'] = __('Task Created succesfully.','emp');
              $task_status       = get_post_meta($task_id,'task_status',true); 
              $return['data'] = '<a class="button button-success button-large" href="'.get_permalink($task_id).'">'.__('Task Detail','emp').' ('.$task_status.')</a>';
              wp_send_json($return); 

            }
         }
    } 
    $return['status']   = 'error';
    $return['message']  = 'Error creating task for this project.';
    wp_send_json( $return ); 
   
}
