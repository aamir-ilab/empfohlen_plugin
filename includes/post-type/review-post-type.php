<?php
/**
 * Custom Post Type Review
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_review_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_review',
        'edit_post'              => 'edit_review',
        'read_post'              => 'read_review',
        'delete_post'            => 'delete_review',
        // primitive/meta caps
        'create_posts'           => 'create_reviews',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_reviews',
       'publish_posts'          => 'publish_reviews',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_reviews',
    );
    return apply_filters( 'empfohlen_get_review_capabilities', $caps );
}

if( !function_exists( 'empfohlen_review_post_type' ) ){
    function empfohlen_review_post_type(){
         $labels = array(
        'name'                  => _x( 'Reviews', 'Post Type General Name', 'empfohlen' ),
        'singular_name'         => _x( 'Review', 'Post Type Singular Name', 'empfohlen' ),
        'menu_name'             => __( 'Review', 'empfohlen' ),
        'name_admin_bar'        => __( 'Review', 'empfohlen' ),
        'archives'              => __( 'Review Archives', 'empfohlen' ),
        'attributes'            => __( 'Review Attributes', 'empfohlen' ),
        'parent_item_colon'     => __( 'Parent Review:', 'empfohlen' ),
        'all_items'             => __( 'All Reviews', 'empfohlen' ),
        'add_new_item'          => __( 'Add New Review', 'empfohlen' ),
        'add_new'               => __( 'Add New Review', 'empfohlen' ),
        'new_item'              => __( 'New Review', 'empfohlen' ),
        'edit_item'             => __( 'Edit Review', 'empfohlen' ),
        'update_item'           => __( 'Update Review', 'empfohlen' ),
        'view_item'             => __( 'View Review', 'empfohlen' ),
        'view_items'            => __( 'View Reviews', 'empfohlen' ),
        'search_items'          => __( 'Search Reviews', 'empfohlen' ),
        'not_found'             => __( 'Review Not found', 'empfohlen' ),
        'not_found_in_trash'    => __( 'Review Not found in Trash', 'empfohlen' ),
        'featured_image'        => __( 'Featured Image', 'empfohlen' ),
        'set_featured_image'    => __( 'Set featured image', 'empfohlen' ),
        'remove_featured_image' => __( 'Remove featured image', 'empfohlen' ),
        'use_featured_image'    => __( 'Use as featured image', 'empfohlen' ),
        'insert_into_item'      => __( 'Insert into item', 'empfohlen' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'empfohlen' ),
        'items_list'            => __( 'reviews list', 'empfohlen' ),
        'items_list_navigation' => __( 'reviews list navigation', 'empfohlen' ),
        'filter_items_list'     => __( 'Filter reviews list', 'empfohlen' ),
    );
     
     $args = array(
            'label'                 => __( 'Review', 'empfohlen' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_review_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title', 'editor'),
        );


    register_post_type( 'review', $args );

    }
}
add_action( 'init', 'empfohlen_review_post_type' );




// add_action('admin_menu', 'review_admin_menu'); 
// function review_admin_menu() { 
//     add_submenu_page(
//          'empfohlen', 
//          'Reviews', 'EMP Reviews', 
//          'manage_options', 
//          'edit.php?post_type=review'); 
// }





function add_review_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_review' ); 
    $admins->add_cap( 'read_review' ); 
    $admins->add_cap( 'delete_review' ); 
    $admins->add_cap( 'create_reviews' ); 
    $admins->add_cap( 'edit_reviews' ); 
    $admins->add_cap( 'publish_reviews' ); 
    $admins->add_cap( 'delete_reviews' ); 
    $admins->add_cap( 'publish_reviews' ); 

}
add_action( 'admin_init', 'add_review_caps');










add_action('parse_request', 'emp_submit_review_post', 1);
function emp_submit_review_post(){
  if( isset( $_POST['action'] ) && $_POST['action'] == 'submit_review' && is_user_logged_in()  ){

    // Verify nonce
    $is_submitted = (isset($_POST['emp_submit_review_nonce']) && wp_verify_nonce($_POST['emp_submit_review_nonce'], 'emp-submit-review-nonce')) ? true : false;
    if($is_submitted){

        if (!session_id()) { session_start(); }
        $_SESSION['error']    = array();
        $_SESSION['success']  = '';
        
        $postData = $_POST;
        
        $current_user = wp_get_current_user();
        $userData = $current_user->data;
        $user_id = (int) $userData->ID;

         // echo "<pre>  "; print_r(  ); echo "</pre> ";  

        $review_title = sanitize_text_field($_POST['review_title']);
        $review_content = wp_kses_post($_POST['review_content']);
        $task_id      = (int) $_POST['task_id'];

        if ( empty($review_title) || empty($review_content) ){
            if ( empty($review_title) ){ $_SESSION['error'][] = __('Review title can not be empty','empfohlen'); }
            if ( empty($review_content) ){ $_SESSION['error'][] = __('Review content can not be empty','empfohlen'); }    
        }else{
          // create review post type post. 
          $review_id = (int) wp_insert_post(array(
           'post_type'   => 'review',
           'post_title'  => $review_title,
           'post_content'=> $review_content,
           'post_status' => 'publish',
         )); 

         if ($review_id > 0){ 
            update_post_meta( $review_id, 'member_id', $userData->ID); 
            update_post_meta( $review_id, 'task_id', $task_id ); 

             // echo "<pre> _FILES "; print_r( $_FILES ); echo "</pre> ";  exit;  

              $review_files = $_FILES['review_files'];
              if(!empty($review_files)){

                $allowed_files_type = array(
                    'text/plain',           'application/json',             'application/xml',  'application/javascript',
                    'image/png',            'image/jpeg',                   'image/jpeg',       'image/gif',                'image/bmp',
                    'application/zip',      'application/x-rar-compressed',
                    'audio/mpeg',           'video/quicktime',              'video/quicktime',
                    'application/pdf',      'image/vnd.adobe.photoshop',
                    'application/msword',   'application/vnd.ms-powerpoint', 'application/vnd.ms-excel',
                );

                  $upload_dir = wp_get_upload_dir(); 
                  $dest_dir   = $upload_dir['basedir'].'/userdata/'.$user_id.'/task/'.$task_id.'/review/'.$review_id; 

                  // echo "<pre> des_dir "; print_r( $dest_dir ); echo "</pre> ";  
                  if(!is_dir($dest_dir)) {  mkdir($dest_dir, 0777, true); }
                  foreach ($review_files['size'] as $f_key => $fs_value) {
                    $file_mime = mime_content_type( $review_files['tmp_name'][$f_key] );  
                     if( !in_array($file_mime, $allowed_files_type) ){
                          $_SESSION['error'][] =  $file_mime.' '.__('files not allowed to upload','empfohlen');
                          continue; 
                     }
                      $tmp_name = $review_files["tmp_name"][$f_key];
                      $name = basename($review_files["name"][$f_key]);
                      move_uploaded_file($tmp_name, $dest_dir.'/'.$name);
                  }
              }





             $_SESSION['success'] = __('Review succesfully Added','empfohlen');
         } else {
             $_SESSION['error'][] = __('Error Adding Review','empfohlen');
         }
        } // else create review 
        wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
        exit();
       }
        
     } // is_submitted
  }// function end 






// add_action('save_post','save_post_callback');
// function save_post_callback($post_id){
//   global $post; 
//   if ($post->post_type == 'review'){
//       // return;
//      // echo "<pre> post "; print_r( $post ); echo "</pre> ";  
//      // echo "<pre> _POST "; print_r( $_POST ); echo "</pre> ";  

//       $review_id =  get_field('review_id',  $post->ID );
//       $member_id = (int) get_field('member_id',  $post->ID );
//       $review_status =  get_field('review_status',  $post->ID );
//       $review_description =  get_field('description',  $post->ID );
//       $review_response =  get_field('review_response',  $post->ID );
      

//       if($member_id > 0){
//           $user_info = get_userdata($member_id);
//           if( !empty($user_info->user_email) ){
//             $empfohlen_setting_options = get_option( 'emp_setting' );
//             $emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
//             $member_dashboard_url = get_permalink($emp_member_dashboard).'?tmpl=reviews';
            
//             $ehtml = '<h3>Your Review ( '.$post->post_title.' ) has been updated'; 
//             $ehtml .= '<br /><br /><a href="'.$member_dashboard_url.'">Click here to check the details</a></h3>';

//             $ehtml .= '</hr>';

//             $ehtml .= '<div>'; 
//               $ehtml .= '<p>Review Info</p>';
//               $ehtml .= '<p>Review ID: '.$review_id.'</p>';
//               $ehtml .= '<p>Review Title: '.$post->post_title.'</p>';
//               $ehtml .= '<p>Review Status: '.$review_status.'</p>';
//             $ehtml .= '</div>';

//             $ehtml .= '</hr>';

//             $ehtml .= '<div>'; 
//             $ehtml .= '<p>Review Description:</p>';
//             $ehtml .= $review_description;
//             $ehtml .= '</div>';

//             $ehtml .= '</hr>';

//             $ehtml .= '<div>'; 
//             $ehtml .= '<p>Review Response:</p>';
//             $ehtml .= $review_response;
//             $ehtml .= '</div>';

//             $headers = array( 'Content-type: text/html' );
//             $emil_status = wp_mail( $user_info->user_email, __('Review ('.$review_id .') Updated','empfohlen') , $ehtml, $headers);


//           }
//       } 
//   }
// }



