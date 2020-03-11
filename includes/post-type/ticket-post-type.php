<?php
/**
 * Custom Post Type Ticket
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_ticket_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_ticket',
        'edit_post'              => 'edit_ticket',
        'read_post'              => 'read_ticket',
        'delete_post'            => 'delete_ticket',
        // primitive/meta caps
        'create_posts'           => 'create_tickets',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_tickets',
       'publish_posts'          => 'publish_tickets',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_tickets',
    );
    return apply_filters( 'empfohlen_get_ticket_capabilities', $caps );
}

if( !function_exists( 'empfohlen_ticket_post_type' ) ){
    function empfohlen_ticket_post_type(){
         $labels = array(
		'name'                  => _x( 'Tickets', 'Post Type General Name', 'emp' ),
		'singular_name'         => _x( 'Ticket', 'Post Type Singular Name', 'emp' ),
		'menu_name'             => __( 'Ticket', 'emp' ),
		'name_admin_bar'        => __( 'Ticket', 'emp' ),
		'archives'              => __( 'Ticket Archives', 'emp' ),
		'attributes'            => __( 'Ticket Attributes', 'emp' ),
		'parent_item_colon'     => __( 'Parent Ticket:', 'emp' ),
		'all_items'             => __( 'All Tickets', 'emp' ),
		'add_new_item'          => __( 'Add New Ticket', 'emp' ),
		'add_new'               => __( 'Add New Ticket', 'emp' ),
		'new_item'              => __( 'New Ticket', 'emp' ),
		'edit_item'             => __( 'Edit Ticket', 'emp' ),
		'update_item'           => __( 'Update Ticket', 'emp' ),
		'view_item'             => __( 'View Ticket', 'emp' ),
		'view_items'            => __( 'View Tickets', 'emp' ),
		'search_items'          => __( 'Search Tickets', 'emp' ),
		'not_found'             => __( 'Ticket Not found', 'emp' ),
		'not_found_in_trash'    => __( 'Ticket Not found in Trash', 'emp' ),
		'featured_image'        => __( 'Featured Image', 'emp' ),
		'set_featured_image'    => __( 'Set featured image', 'emp' ),
		'remove_featured_image' => __( 'Remove featured image', 'emp' ),
		'use_featured_image'    => __( 'Use as featured image', 'emp' ),
		'insert_into_item'      => __( 'Insert into item', 'emp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'emp' ),
		'items_list'            => __( 'tickets list', 'emp' ),
		'items_list_navigation' => __( 'tickets list navigation', 'emp' ),
		'filter_items_list'     => __( 'Filter tickets list', 'emp' ),
	);
	 
	 $args = array(
            'label'                 => __( 'Ticket', 'emp' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_ticket_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title', 'comments'),
        );


	register_post_type( 'ticket', $args );

    }
}
add_action( 'init', 'empfohlen_ticket_post_type' );




// add_action('admin_menu', 'ticket_admin_menu'); 
// function ticket_admin_menu() { 
//     add_submenu_page(
//     		'empfohlen', 
//     		'Tickets', 'EMP Tickets', 
//     		'manage_options', 
//     		'edit.php?post_type=ticket'); 
// }





function add_ticket_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_ticket' ); 
    $admins->add_cap( 'read_ticket' ); 
    $admins->add_cap( 'delete_ticket' ); 
    $admins->add_cap( 'create_tickets' ); 
    $admins->add_cap( 'edit_tickets' ); 
    $admins->add_cap( 'publish_tickets' ); 
    $admins->add_cap( 'delete_tickets' ); 
    $admins->add_cap( 'publish_tickets' ); 

}
add_action( 'admin_init', 'add_ticket_caps');










add_action('parse_request', 'emp_submit_ticket_post', 1);
function emp_submit_ticket_post(){
  if( isset( $_POST['action'] ) && $_POST['action'] == 'submit_ticket' && is_user_logged_in()  ){

    // Verify nonce
    $is_submitted = (isset($_POST['emp_submit_ticket_nonce']) && wp_verify_nonce($_POST['emp_submit_ticket_nonce'], 'emp-submit-ticket-nonce')) ? true : false;
    if($is_submitted){

        if ( ! session_id() ) { session_start(); }
        $_SESSION['error'] = array();
        $_SESSION['success'] = '';
        
        $postData = $_POST;
        
        $current_user = wp_get_current_user();
        $userData = $current_user->data;
        $user_id = (int) $userData->ID;


        $tf_title = sanitize_text_field($_POST['tf_title']);
        $tf_content = wp_kses_post($_POST['tf_content']);


        if ( empty($tf_title) || empty($tf_content) ){
            if ( empty($tf_title) ){ $_SESSION['error'][] = 'Title can not be empty'; }
            if ( empty($tf_content) ){ $_SESSION['error'][] = 'Content can not be empty'; }    
        }else{
          // create ticket post type post. 
          $ticket_id = (int) wp_insert_post(array(
           'post_type'   => 'ticket',
           'post_title'  => $tf_title,
           'post_status' => 'publish',
         )); 

         if ($ticket_id > 0){ 
            $ticket_id_code = 'TKD'.$ticket_id;
            update_post_meta( $ticket_id, 'ticket_id', $ticket_id_code ); 
            update_post_meta( $ticket_id, 'description', $tf_content ); 
            update_post_meta( $ticket_id, 'member_id', $user_id ); 
            update_post_meta( $ticket_id, 'ticket_status', 'pending' ); 
            $_SESSION['success'] = 'Ticket succesfully saved';
         } else {
             $_SESSION['error'][] = 'Error Adding Ticket';
         }
        } // else create ticket 
       

        wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
        exit();
       }
        
     } // is_submitted
  }// function end 






add_action('save_post','save_post_callback');
function save_post_callback($post_id){
  global $post; 
  if ($post->post_type == 'ticket'){
      // return;
     // echo "<pre> post "; print_r( $post ); echo "</pre> ";  
     // echo "<pre> _POST "; print_r( $_POST ); echo "</pre> ";  

      $ticket_id =  get_field('ticket_id',  $post->ID );
      $member_id = (int) get_field('member_id',  $post->ID );
      $ticket_status =  get_field('ticket_status',  $post->ID );
      $ticket_description =  get_field('description',  $post->ID );
      $ticket_response =  get_field('ticket_response',  $post->ID );
      

      if($member_id > 0){
          $user_info = get_userdata($member_id);
          if( !empty($user_info->user_email) ){
            $empfohlen_setting_options = get_option( 'emp_setting' );
            $emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
            $member_dashboard_url = get_permalink($emp_member_dashboard).'?tmpl=tickets';
            
            $ehtml = '<h3>Your Ticket ( '.$post->post_title.' ) has been updated'; 
            $ehtml .= '<br /><br /><a href="'.$member_dashboard_url.'">Click here to check the details</a></h3>';

            $ehtml .= '</hr>';

            $ehtml .= '<div>'; 
              $ehtml .= '<p>Ticket Info</p>';
              $ehtml .= '<p>Ticket ID: '.$ticket_id.'</p>';
              $ehtml .= '<p>Ticket Title: '.$post->post_title.'</p>';
              $ehtml .= '<p>Ticket Status: '.$ticket_status.'</p>';
            $ehtml .= '</div>';

            $ehtml .= '</hr>';

            $ehtml .= '<div>'; 
            $ehtml .= '<p>Ticket Description:</p>';
            $ehtml .= $ticket_description;
            $ehtml .= '</div>';

            $ehtml .= '</hr>';

            $ehtml .= '<div>'; 
            $ehtml .= '<p>Ticket Response:</p>';
            $ehtml .= $ticket_response;
            $ehtml .= '</div>';

            $headers = array( 'Content-type: text/html' );
            $emil_status = wp_mail( $user_info->user_email, __('Ticket ('.$ticket_id .') Updated','emp') , $ehtml, $headers);


          }
      } 
  }
}



