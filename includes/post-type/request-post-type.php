<?php
/**
 * Custom Post Type Request
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_request_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_request',
        'edit_post'              => 'edit_request',
        'read_post'              => 'read_request',
        'delete_post'            => 'delete_request',
        // primitive/meta caps
        'create_posts'           => 'create_requests',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_requests',
       'publish_posts'          => 'publish_requests',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_requests',
    );
    return apply_filters( 'empfohlen_get_request_capabilities', $caps );
}

if( !function_exists( 'empfohlen_request_post_type' ) ){
    function empfohlen_request_post_type(){
         $labels = array(
		'name'                  => _x( 'Requests', 'Post Type General Name', 'empfohlen' ),
		'singular_name'         => _x( 'Request', 'Post Type Singular Name', 'empfohlen' ),
		'menu_name'             => __( 'Request', 'empfohlen' ),
		'name_admin_bar'        => __( 'Request', 'empfohlen' ),
		'archives'              => __( 'Request Archives', 'empfohlen' ),
		'attributes'            => __( 'Request Attributes', 'empfohlen' ),
		'parent_item_colon'     => __( 'Parent Request:', 'empfohlen' ),
		'all_items'             => __( 'All Requests', 'empfohlen' ),
		'add_new_item'          => __( 'Add New Request', 'empfohlen' ),
		'add_new'               => __( 'Add New Request', 'empfohlen' ),
		'new_item'              => __( 'New Request', 'empfohlen' ),
		'edit_item'             => __( 'Edit Request', 'empfohlen' ),
		'update_item'           => __( 'Update Request', 'empfohlen' ),
		'view_item'             => __( 'View Request', 'empfohlen' ),
		'view_items'            => __( 'View Requests', 'empfohlen' ),
		'search_items'          => __( 'Search Requests', 'empfohlen' ),
		'not_found'             => __( 'Request Not found', 'empfohlen' ),
		'not_found_in_trash'    => __( 'Request Not found in Trash', 'empfohlen' ),
		'featured_image'        => __( 'Featured Image', 'empfohlen' ),
		'set_featured_image'    => __( 'Set featured image', 'empfohlen' ),
		'remove_featured_image' => __( 'Remove featured image', 'empfohlen' ),
		'use_featured_image'    => __( 'Use as featured image', 'empfohlen' ),
		'insert_into_item'      => __( 'Insert into item', 'empfohlen' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'empfohlen' ),
		'items_list'            => __( 'requests list', 'empfohlen' ),
		'items_list_navigation' => __( 'requests list navigation', 'empfohlen' ),
		'filter_items_list'     => __( 'Filter requests list', 'empfohlen' ),
	);
	 
	 $args = array(
            'label' => __( 'Request', 'empfohlen' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_request_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title' ),
        );


	register_post_type( 'request', $args );

    }
}
add_action( 'init', 'empfohlen_request_post_type' );




// add_action('admin_menu', 'admin_menu_request'); 
// function admin_menu_request() { 
//     add_submenu_page(
//     		'empfohlen', 
//     		'Requests', 'EMP Requests', 
//     		'manage_options', 
//     		'edit.php?post_type=request'); 
// }


 


function add_request_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );
    $admins->add_cap( 'edit_request' ); 
    $admins->add_cap( 'read_request' ); 
    $admins->add_cap( 'delete_request' ); 
    $admins->add_cap( 'create_requests' ); 
    $admins->add_cap( 'edit_requests' ); 
    $admins->add_cap( 'publish_requests' ); 
    $admins->add_cap( 'delete_requests' ); 
    $admins->add_cap( 'publish_requests' ); 
}
add_action( 'admin_init', 'add_request_caps');










// add_action('acf/save_post', 'my_acf_save_post', 5);
// function my_acf_save_post( $post_id ) {
//     $save_post = get_post( $post_id );
//     // echo "<pre> save_post "; print_r( $save_post ); echo "</pre> "; exit;  
//     // Get previous values.
//     $prev_values = get_fields( $post_id );
//     if( $save_post->post_type == 'request' ){
//         echo "<pre> my_acf_save_post post_id "; print_r( $post_id ); echo "</pre> ";  
//         echo "<pre> prev_values "; print_r( $prev_values ); echo "</pre> ";  
//         exit; 
//     }
//     // Get submitted values.
//     $values = $_POST['acf'];
//     // // Check if a specific value was updated.
//     // if( isset($_POST['acf']['field_abc123']) ) {
//     //     // Do something.
//     // }
// }








add_action( 'wp_ajax_nopriv_generate_task', 'generate_task' );
add_action( 'wp_ajax_generate_task', 'generate_task' );
function generate_task() {
    
    // wp_send_json( $return); 
    if (!is_admin() || !defined( 'DOING_AJAX' ) || !DOING_AJAX ){
        // wp_send_json_success( 'admin is login and its ajax' ); 
        die();
    }// is_admin

    
    $request_id = (int) $_POST['rid'];
    if ( $request_id ){
        $request = get_post($request_id);
         // echo "<pre> request "; print_r( $request ); echo "</pre> ";  
         // check if task not exist already
         $args = array(
            'post_type'              => array( 'task' ),
            'meta_query'             => array(
                array(
                    'key'     => 'request_id',
                    'value'   => $request_id,
                ),
            ),
        );
         
         $task_query = new WP_Query( $args );
         $task_exist = $task_query->posts;
         
         if($task_exist){
            $return['status'] = 'error';
            $return['message'] = 'Task already exist';
             // echo "<pre>  "; print_r( $task_exist ); echo "</pre> ";  
            $task = $task_exist[0];
            $task_status = get_post_meta($task->ID,'task_status',true); 
            $return['data'] = '<a class="button button-success button-large  task_detail_btn" data-rid="'.$request_id.'" href="'.get_edit_post_link($task->ID).'">Task Detail ('.$task_status.')</a>';
            wp_send_json($return); 
         }else{
            // create task for this request 
            $task_id = wp_insert_post(array(
               'post_type'      => 'task',
               'post_title'     => 'Task for Request '.$request_id,
               'post_status'    => 'publish',
            ));

            $member_id = (int) get_field('member_id', $request_id);
            $select_project_id = (int) get_field('select_project_id', $request_id);

            $task_id_code = 'T'.$task_id.'_R'.$request_id;
            if ($task_id) {
               update_post_meta($task_id, 'request_id', $request_id);
               update_post_meta($task_id, 'task_status', 'pending');
               update_post_meta($task_id, 'task_id', $task_id_code);
               update_post_meta($task_id, 'member_id', $member_id);
               update_post_meta($task_id, 'task_type_request', true);
               update_post_meta($task_id, 'project_id', $select_project_id);
            }

            $return['status'] = 'success';
            $return['message'] = 'Task Created succesfully';
            $task_status = get_post_meta($task_id,'task_status',true); 
            $return['data'] = '<a class="button button-success button-large  task_detail_btn" data-rid="'.$request_id.'" href="'.get_edit_post_link($task_id).'">Task Detail ('.$task_status.')</a>';
            


            // make the request accepted 
            update_post_meta($request_id, 'request_status', 'accepted');



            // send email to user that his request has been accepted and a new task has been crated.  
            $project = (int) get_post_meta($request_id,'select_project_id',true); 
            $projectData = get_post($project);
            $taskData = get_post($task_id);
            $user_info = get_userdata($member_id);
          
            $url = get_site_url(). '/my-account/?act=' .base64_encode( serialize($string));
            $ehtml = 'Your Request againt Project : ('.$projectData->post_title.') has been approved and a '; 
            $ehtml .= '<a href="'.get_the_permalink($task_id).'">Task('.$task_id_code.')</a> has been created';
            $headers = array( 'Content-type: text/html' );
            wp_mail( $user_info->user_email, __('Request Accepted and Task Created','empfohlen') , $ehtml, $headers);

            // add_action( 'admin_notices', 'my_test_notice' );
            wp_send_json($return); 
         }

    }
}



 



/**
 * Generated by the WordPress Meta Box Generator at http://goo.gl/8nwllb
 */
class Rational_Meta_Box_Request {
    private $screens = array(
        'request',
    );
    private $fields = array(
        array(
            'id' => 'test',
            'label' => 'test',
            'type' => 'text',
        ),
    );

    /**
     * Class construct method. Adds actions to their respective WordPress hooks.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        // add_action( 'save_post', array( $this, 'save_post' ) );
    }

    /**
     * Hooks into WordPress' add_meta_boxes function.
     * Goes through screens (post types) and adds the meta box.
     */
    public function add_meta_boxes() {
        foreach ( $this->screens as $screen ) {
            add_meta_box(
                'advanced-options',
                __( 'Advanced Options', 'emp_task_generate' ),
                array( $this, 'add_meta_box_callback' ),
                $screen,
                'side',
                'high'
            );
        }
    }

    /**
     * Generates the HTML for the meta box
     * 
     * @param object $post WordPress post object
     */
    public function add_meta_box_callback( $post ) {

        $args = array(
            'post_type'              => 'task',
            'meta_query'             => array(
                array(
                    'key'     => 'request_id',
                    'value'   => $post->ID,
                ),
            ),
        );

        $task_query = new WP_Query( $args );
        $task_exist = $task_query->posts;

        if(!empty($task_exist)){
             $task = $task_exist[0];  
             $task_status =  get_field( "task_status", $task->ID ); 
             $output = '<a class="button button-success button-large  task_detail_btn" data-rid="'.$post->ID.'" href="'.get_edit_post_link($task->ID).'">Task Detail ('.$task_status.')</a>';
             echo $output; 
        }else{
            wp_nonce_field( 'advanced_options_data', 'advanced_options_nonce' );
            $output = '<a class="btn generate_task" data-rid="'.$post->ID.'" >Generate Trask for this Request</a>';
            echo $output;
        }
    }

    

    
}
new Rational_Meta_Box_Request;