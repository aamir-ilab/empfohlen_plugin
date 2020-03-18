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

        
    // $currency = isset($_POST['currency'])?(sanitize_text_field($_POST['currency'])):'';
    // if(empty($currency)) { 
    //     $return['status'] =  'error'; 
    //     $return['message'] =  'Currency can not be empty'; 
    //     wp_send_json( $return ); 
    // }     

    $balances = get_field('balance_amount',  'user_'.$userData->ID );
    if(empty($balances)){
        $return['status'] =  'error'; 
        $return['message'] =  'You have zero balance to payout'; 
        wp_send_json( $return ); 
    }



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
            )
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

    $payout_amount    = (int) $_POST['payout_amount'];
    $user_currency    = EmpHelper::getUserCurrency($userData->ID);
    $withdrawl_info   = wp_kses_post($_POST['payout_description']);

    if($payout_amount < 1){
        $return['status'] =   'error'; 
        $return['message'] =  'Incorrect withdrawl amount'; 
        wp_send_json( $return ); 
    }

    // create withdrawl post type post. 
    $withdrawl_id = (int) wp_insert_post(array(
       'post_type'   => 'withdrawl',
       'post_title'  => 'Withdrawl request by User: '.$userData->ID.' amount: '.$payout_amount,
       'post_status' => 'publish',
    ));


    if($withdrawl_id > 0){
      $withdrawl_id_code = 'WD'.$userData->ID; 
      update_post_meta($withdrawl_id, 'withdrawl_id', $withdrawl_id_code );
      update_post_meta($withdrawl_id, 'withdrawl_member_id', $userData->ID );
      update_post_meta($withdrawl_id, 'withdrawl_amount', $payout_amount );
      update_post_meta($withdrawl_id, 'withdrawl_currency',  $user_currency );
      update_post_meta($withdrawl_id, 'withdrawl_status',  'pending');
      update_post_meta($withdrawl_id, 'withdrawl_info',  $withdrawl_info);
    }


      $return['status'] =  'success'; 
      $return['message'] =  'Your withdrawl request has been submited succesfully'; 
      wp_send_json( $return ); 
      exit; 


    // $currency_found = false; 
    // foreach ($balances as $bk => $bv) {
    //   if ($bv['balance_currency'] == $currency) {
    //      $currency_found = true; 
         // check if member has enough balance for payout. 
         // if ( (int) $bv['balance_value'] >=  )

        // // check if already have a pending withdrawl request. 
        // // check if task not exist already
        // $args = array(
        //     'post_type'              => array( 'withdrawl' ),
        //     'meta_query'             => array(
        //         array(
        //             'key'     => 'withdrawl_member_id',
        //             'value'   => $userData->ID,
        //         ),
        //         array(
        //             'key'     => 'withdrawl_status',
        //             'value'   => 'pending',
        //         ),
        //         array(
        //             'key'     => 'withdrawl_currency',
        //             'value'   => $bv['balance_currency'],
        //         ),
        //     ),
        // ); 
        // $withdrawl_query = new WP_Query( $args );
        // $withdrawl_exist = $withdrawl_query->posts;

        //  // echo "<pre> withdrawl_exist "; print_r( $withdrawl_exist ); echo "</pre> ";  

        // if(!empty($withdrawl_exist)){
        //     $return['status'] =   'error'; 
        //     $return['message'] =  'You Already have a pending withdrawl payment request'; 
        //     wp_send_json( $return ); 
        // }




        // // create withdrawl post type post. 
        // $withdrawl_id = (int) wp_insert_post(array(
        //    'post_type'   => 'withdrawl',
        //    'post_title'  => 'Withdrawl request by User: '.$userData->ID.' amount: '. $bv['balance_value'],
        //    'post_status' => 'publish',
        // ));

         // echo "<pre> withdrawl_id "; print_r( $withdrawl_id ); echo "</pre> ";  

        // if($withdrawl_id > 0){
        //     $withdrawl_id_code = 'WD'.$userData->ID; 
        //     update_post_meta($withdrawl_id, 'withdrawl_id', $withdrawl_id_code );
        //     update_post_meta($withdrawl_id, 'withdrawl_member_id', $userData->ID );
        //     update_post_meta($withdrawl_id, 'withdrawl_amount', (int) $bv['balance_value'] );
        //     update_post_meta($withdrawl_id, 'withdrawl_currency',  $bv['balance_currency'] );
        //     update_post_meta($withdrawl_id, 'withdrawl_status',  'pending');
        // }


        // $return['status'] =  'success'; 
        // $return['message'] =  'Your withdrawl request has been submited succesfully'; 
        // wp_send_json( $return ); 
        // exit; 

    //   }
    // } // foreach end here


 
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






// custom meta boxes 
add_action( 'add_meta_boxes',  'add_meta_boxes_withdrawl_completed' );
 function add_meta_boxes_withdrawl_completed() {

      add_meta_box('advanced-options_withdrawl_comp',
            __( 'Withdrawl Action', 'emp_task' ),
            'add_meta_boxes_withdrawl_complete_callback',
            'withdrawl',
            'side',
            'low'
        );
  
}



 function add_meta_boxes_withdrawl_complete_callback( $post ){

     // $task_request_id = (int) get_post_meta( $post->ID, 'request_id', true );
     // $args = array(
     //        'post_type'              => array( 'invoice' ),
     //        'meta_query'             => array(
     //            array(
     //                'key'     => 'invoice_task_id',
     //                'value'   => $post->ID,
     //            ),
     //        ),
     // );
     // $inv_query = new WP_Query( $args );
     // $invoice_exist = $inv_query->posts;
     // // echo "<pre> invoice_exist "; print_r( $invoice_exist ); echo "</pre> ";  
     // if (!empty($invoice_exist)){
     //    $invoice = $invoice_exist[0];
     //    $inv =  '<div class="invoice">';
     //    $inv .= '<a href="'.get_edit_post_link($invoice->ID).'">Invoice Detail</div>';
     // } else {
     //    $inv =  '<div class="generate_invoice">';
     //    $inv .= '<a class="button button-success button-large generate_task_invoice" data-tid="'.$post->ID.'">Task Complete(Generate Invoice)</a>';
     //    $inv .= '</div>';
     // }
     // echo $inv;


   // withdrawl_status
   $withdrawl_status =  get_post_meta( $post->ID, 'withdrawl_status', true );

   // echo "<pre> withdrawl_status "; print_r( $withdrawl_status ); echo "</pre> ";  

    $t_comp  = '<div>';
    $t_comp .= '<p>Once the withdrawl is completed and user has been paid then click this button so the amount can be deducted from user balance.</p>';
    $t_comp .= '<div class="withdrawl_message" style="color:red;"></div>'; 

    if( $withdrawl_status == 'pending' ){
       $t_comp .= '<input type="hidden" id="withdrawl_complete_nonce" name="withdrawl_complete_nonce" value="'.wp_create_nonce('withdrawl-complete-nonce').'"/>';
       $t_comp .= '<a class="button buttons-uccess button-large withdrawl_action_btn withdrawl_complete '.$withdrawl_status.'" data-wid="'.$post->ID.'">Withdrawl Complete (Deduct Amount)</a>';
    }else if($withdrawl_status == 'completed'){
       $t_comp .= '<a class="button buttons-uccess button-large withdrawl_action_btn '.$withdrawl_status.'" disabled>Withdrawl Completed Succesfully</a>';
    }
    $t_comp .= '</div>';
    echo $t_comp;
 }






// complete task and generate invoice. 
add_action( 'wp_ajax_nopriv_withdrawl_complete_deduct_amount', 'withdrawl_complete_deduct_amount' );
add_action( 'wp_ajax_withdrawl_complete_deduct_amount', 'withdrawl_complete_deduct_amount' );
function withdrawl_complete_deduct_amount() {
    
    // wp_send_json( $return); 
    if (!is_admin() || !defined( 'DOING_AJAX' ) || !DOING_AJAX ){
        // wp_send_json_success( 'admin is login and its ajax' ); 
        die();
    }// is_admin
    
    check_ajax_referer( 'withdrawl-complete-nonce', 'security' );
    
    $return = array();
    $withdrawl_id = (int) $_POST['wid'];
    if ( $withdrawl_id ){
        $withdrawl = get_post($withdrawl_id);
         // echo "<pre> withdrawl "; print_r(  $withdrawl ); echo "</pre> ";  exit; 
        if(!empty($withdrawl)){
          // withdrawl_status
          $withdrawl_status = get_post_meta( $withdrawl->ID, 'withdrawl_status', true );
          $withdrawl_amount = (int) get_post_meta( $withdrawl->ID, 'withdrawl_amount', true );
          if( $withdrawl_status == 'pending' ){
            if( $withdrawl_amount > 0 ){
              // get task member_id
              $withdrawl_member_id = (int) get_post_meta( $withdrawl->ID, 'withdrawl_member_id', true );
              if( $withdrawl_member_id > 0){
                 $member_balance = get_field('balance_amount', 'user_'.$withdrawl_member_id );
                 if($member_balance > 0){

                   $member_balance = $member_balance -  $withdrawl_amount; 
                   update_field('balance_amount', $member_balance,'user_'.$withdrawl_member_id );
                   update_post_meta($withdrawl_id, 'withdrawl_status', 'completed' );

                    // send email to user that his withdrawl has been completed and amount has been paid to him.  
                    $user_info = get_userdata($withdrawl_member_id);
                    $withdrawl_id_code = get_field( "withdrawl_id", $withdrawl->ID);
                    $w_permalink = get_permalink($withdrawl->ID);

                    $ehtml = sprintf(__('Your <a href="%s"> Withdrawl Request(%s)</a> has been completed and amount has been transfered to your account', 'emp'),$w_permalink,$withdrawl_id_code);                    
                  
                    $headers = array( 'Content-type: text/html' );
                    wp_mail( $user_info->user_email, __('Withdrawl ('.$withdrawl_id_code.') Completed','emp') , $ehtml, $headers);
                    
                    $return['status']   = 'success';
                    $return['message']  = 'Withdrawl Completed Succesfully';
                    $return['data'] = '<a class="button button-success button-large">Withdrawl completed and payment has been paid</a>';
                    wp_send_json( $return ); 

                 }else{
                    $return['status']   = 'error';
                    $return['message']  = 'User Earning amount should be grater than zero. User has not earning to deduct from';
                    $return['data'] = '<a class="button button-success button-large">Error</a>';
                    wp_send_json( $return ); 
                 }
              }else{
                $return['status']   = 'error';
                $return['message']  = 'Withdrawl Member(User) doest not exist.';
                $return['data'] = '<a class="button button-success button-large">Error</a>';
                wp_send_json( $return ); 
              }
            }else{
              $return['status']   = 'error';
              $return['message']  = 'Withdrawl amount must be grater than zero.';
              $return['data'] = '<a class="button button-success button-large">Error</a>';
              wp_send_json( $return ); 
            }
          }else{
            $return['status']   = 'error';
            $return['message']  = 'Only Withdrawl with status pending can be processed';
            $return['data'] = '<a class="button button-success button-large">Error</a>';
            wp_send_json( $return ); 
          }
        }
    }

    $return['status']   = 'error';
    $return['message']  = 'Error processing your request.';
    wp_send_json( $return ); 
}
