<?php
/**
 * Custom Post Type Withdrawl
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_withdrawl_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_withdrawl',
        'edit_post'              => 'edit_withdrawl',
        'read_post'              => 'read_withdrawl',
        'delete_post'            => 'delete_withdrawl',
        // primitive/meta caps
        'create_posts'           => 'create_withdrawls',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_withdrawls',
       'publish_posts'          => 'publish_withdrawls',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_withdrawls',
    );
    return apply_filters( 'empfohlen_get_withdrawl_capabilities', $caps );
}

if( !function_exists( 'empfohlen_withdrawl_post_type' ) ){
    function empfohlen_withdrawl_post_type(){
         $labels = array(
		'name'                  => _x( 'Withdrawls', 'Post Type General Name', 'emp' ),
		'singular_name'         => _x( 'Withdrawl', 'Post Type Singular Name', 'emp' ),
		'menu_name'             => __( 'Withdrawl', 'emp' ),
		'name_admin_bar'        => __( 'Withdrawl', 'emp' ),
		'archives'              => __( 'Withdrawl Archives', 'emp' ),
		'attributes'            => __( 'Withdrawl Attributes', 'emp' ),
		'parent_item_colon'     => __( 'Parent Withdrawl:', 'emp' ),
		'all_items'             => __( 'All Withdrawls', 'emp' ),
		'add_new_item'          => __( 'Add New Withdrawl', 'emp' ),
		'add_new'               => __( 'Add New Withdrawl', 'emp' ),
		'new_item'              => __( 'New Withdrawl', 'emp' ),
		'edit_item'             => __( 'Edit Withdrawl', 'emp' ),
		'update_item'           => __( 'Update Withdrawl', 'emp' ),
		'view_item'             => __( 'View Withdrawl', 'emp' ),
		'view_items'            => __( 'View Withdrawls', 'emp' ),
		'search_items'          => __( 'Search Withdrawls', 'emp' ),
		'not_found'             => __( 'Withdrawl Not found', 'emp' ),
		'not_found_in_trash'    => __( 'Withdrawl Not found in Trash', 'emp' ),
		'featured_image'        => __( 'Featured Image', 'emp' ),
		'set_featured_image'    => __( 'Set featured image', 'emp' ),
		'remove_featured_image' => __( 'Remove featured image', 'emp' ),
		'use_featured_image'    => __( 'Use as featured image', 'emp' ),
		'insert_into_item'      => __( 'Insert into item', 'emp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'emp' ),
		'items_list'            => __( 'withdrawls list', 'emp' ),
		'items_list_navigation' => __( 'withdrawls list navigation', 'emp' ),
		'filter_items_list'     => __( 'Filter withdrawls list', 'emp' ),
	);
	 
	 $args = array(
            'label'                 => __( 'Withdrawl', 'emp' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_withdrawl_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title', 'comments' ),
        );


	register_post_type( 'withdrawl', $args );

    }
}
add_action( 'init', 'empfohlen_withdrawl_post_type' );




// add_action('admin_menu', 'dmin_menu_withdrawl'); 
// function dmin_menu_withdrawl() { 
//     add_submenu_page(
//     		'empfohlen', 
//     		'Withdrawls', 'EMP Withdrawls', 
//     		'manage_options', 
//     		'edit.php?post_type=withdrawl'); 
// }





function add_withdrawl_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_withdrawl' ); 
    $admins->add_cap( 'read_withdrawl' ); 
    $admins->add_cap( 'delete_withdrawl' ); 
    $admins->add_cap( 'create_withdrawls' ); 
    $admins->add_cap( 'edit_withdrawls' ); 
    $admins->add_cap( 'publish_withdrawls' ); 
    $admins->add_cap( 'delete_withdrawls' ); 
    $admins->add_cap( 'publish_withdrawls' ); 

}
add_action( 'admin_init', 'add_withdrawl_caps');







 












add_action( 'wp_ajax_payout_withdrawl_submit', 'payout_withdrawl_submit_callback' );
add_action( 'wp_ajax_nopriv_payout_withdrawl_submit', 'payout_withdrawl_submit_callback' );
function payout_withdrawl_submit_callback() {
  // check_ajax_referer( 'my-special-string', 'security' );
  // echo 'It worked!';
  // die();
  // $return['login'] =  is_user_logged_in()?'yes':'no';
  if(is_user_logged_in()){

    $current_user = wp_get_current_user();
    $userData = $current_user->data;
    $return['userData'] = $userData; 
    $user_role = $current_user->roles; // $userData;
    // $user_groups = get_the_terms( (int) $userData->ID, 'user-group');

    // if(!empty($user_groups)){
    //     $user_groups = wp_list_pluck( $user_groups, 'term_id' );
    // }

    check_ajax_referer( 'withdrawl-payout-nonce', 'security' );
        
        
    // check if user has role member
    $is_member = false; 
    if(is_array($user_role)){
        $is_member = (in_array('member', $user_role)) ? true : false; 
    }else{
        $is_member = ($user_role == 'member') ? true : false; 
    }

    if(!$is_member){
        $return['status'] =  'error'; 
        $return['message'] =  'Only members can submit a payout request'; 
        wp_send_json( $return ); 
    }

        
    $currency = isset($_POST['currency'])?(sanitize_text_field($_POST['currency'])):'';
    if(empty($currency)) { 
        $return['status'] =  'error'; 
        $return['message'] =  'Currency can not be empty'; 
        wp_send_json( $return ); 
    }     

    $balances = get_field('balance',  'user_'.$userData->ID );
    if(empty($balances)){
        $return['status'] =  'error'; 
        $return['message'] =  'You have zero balance to payout'; 
        wp_send_json( $return ); 
    }

    $currency_found = false; 
    foreach ($balances as $bk => $bv) {
      if ($bv['balance_currency'] == $currency) {
         $currency_found = true; 
         // check if member has enough balance for payout. 
         // if ( (int) $bv['balance_value'] >=  )

        // check if already have a pending withdrawl request. 
        // check if task not exist already
        $args = array(
            'post_type'              => array( 'withdrawl' ),
            'meta_query'             => array(
                array(
                    'key'     => 'withdrawl_member_id',
                    'value'   => $userData->ID,
                ),
                array(
                    'key'     => 'withdrawl_status',
                    'value'   => 'pending',
                ),
                array(
                    'key'     => 'withdrawl_currency',
                    'value'   => $bv['balance_currency'],
                ),
            ),
        ); 
        $withdrawl_query = new WP_Query( $args );
        $withdrawl_exist = $withdrawl_query->posts;

         // echo "<pre> withdrawl_exist "; print_r( $withdrawl_exist ); echo "</pre> ";  

        if(!empty($withdrawl_exist)){
            $return['status'] =   'error'; 
            $return['message'] =  'You Already have a pending withdrawl payment request'; 
            wp_send_json( $return ); 
        }




        // create withdrawl post type post. 
        $withdrawl_id = (int) wp_insert_post(array(
           'post_type'   => 'withdrawl',
           'post_title'  => 'Withdrawl request by User: '.$userData->ID.' amount: '. $bv['balance_value'],
           'post_status' => 'publish',
        ));

         // echo "<pre> withdrawl_id "; print_r( $withdrawl_id ); echo "</pre> ";  

        if($withdrawl_id > 0){
            $withdrawl_id_code = 'WD'.$userData->ID; 
            update_post_meta($withdrawl_id, 'withdrawl_id', $withdrawl_id_code );
            update_post_meta($withdrawl_id, 'withdrawl_member_id', $userData->ID );
            update_post_meta($withdrawl_id, 'withdrawl_amount', (int) $bv['balance_value'] );
            update_post_meta($withdrawl_id, 'withdrawl_currency',  $bv['balance_currency'] );
            update_post_meta($withdrawl_id, 'withdrawl_status',  'pending');
        }


        $return['status'] =  'success'; 
        $return['message'] =  'Your withdrawl request has been submited succesfully'; 
        wp_send_json( $return ); 
        exit; 

      }
    } // foreach end here


    $return['status'] =  'error'; 
    $return['message'] =  'Error Submitting withdrawl request'; 
    wp_send_json( $return ); 
   

  }else{
    $return['status'] =  'error'; 
    $return['message'] =  'please login to submit request'; 
    wp_send_json( $return ); 
  }
  wp_send_json( $return ); 
}










add_action('save_post','save_widthdrawl_post_callback');
function save_widthdrawl_post_callback($post_id){
  global $post; 
  if ($post->post_type == 'withdrawl'){
      
      // return;
      // echo "<pre> post "; print_r( $post ); echo "</pre> ";  
      // echo "<pre> _POST "; print_r( $_POST ); echo "</pre> ";  

      $withdrawl_id         =  get_field('withdrawl_id',  $post->ID );
      $member_id            =  (int) get_field('withdrawl_member_id',  $post->ID );
      $withdrawl_currency   =  get_field('withdrawl_currency',  $post->ID );
      $withdrawl_amount     =  get_field('withdrawl_amount',  $post->ID );
      $withdrawl_status     =  get_field('withdrawl_status',  $post->ID );
      $withdrawl_info       =  get_field('withdrawl_info',  $post->ID );
      

      if($member_id > 0){
          $user_info = get_userdata($member_id);
          if( !empty($user_info->user_email) ){
            
            $empfohlen_setting_options = get_option( 'emp_setting' );
            $emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
            $member_dashboard_url = get_permalink($emp_member_dashboard).'?tmpl=pay';
            
            $ehtml = '<h3>Your Withdrawl Request ( '.$post->post_title.' ) has been updated'; 
            $ehtml .= '<br /><br /><a href="'.$member_dashboard_url.'">Click here to check the details</a></h3>';

            $ehtml .= '</hr>';

            $ehtml .= '<div>'; 
              $ehtml .= '<p>Withdrawl Info</p>';
              $ehtml .= '<p>Withdrawl ID: '.$withdrawl_id.'</p>';
              $ehtml .= '<p>Withdrawl Title: '.$post->post_title.'</p>';
              $ehtml .= '<p>Withdrawl Status: '.$withdrawl_status.'</p>';
              $ehtml .= '<p>Withdrawl Amount: '.$withdrawl_currency.' '.$withdrawl_amount.'</p>';
            $ehtml .= '</div>';

            $ehtml .= '</hr>';

            $ehtml .= '<div>'; 
            $ehtml .= '<p>Withdrawl Description:</p>';
            $ehtml .= $withdrawl_info;
            $ehtml .= '</div>';

            $headers = array( 'Content-type: text/html' );
            $emil_status = wp_mail( $user_info->user_email, __('Withdrawl ('.$withdrawl_id .') Updated','emp') , $ehtml, $headers);


          }
      } 
  }
}

