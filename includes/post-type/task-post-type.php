<?php
/**
 * Custom Post Type Task
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_task_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_task',
        'edit_post'              => 'edit_task',
        'read_post'              => 'read_task',
        'delete_post'            => 'delete_task',
        // primitive/meta caps
        'create_posts'           => 'create_tasks',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_tasks',
       'publish_posts'          => 'publish_tasks',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_tasks',
    );
    return apply_filters( 'empfohlen_get_task_capabilities', $caps );
}

if( !function_exists( 'empfohlen_task_post_type' ) ){
    function empfohlen_task_post_type(){
         $labels = array(
		'name'                  => _x( 'Tasks', 'Post Type General Name', 'emp' ),
		'singular_name'         => _x( 'Task', 'Post Type Singular Name', 'emp' ),
		'menu_name'             => __( 'Task', 'emp' ),
		'name_admin_bar'        => __( 'Task', 'emp' ),
		'archives'              => __( 'Task Archives', 'emp' ),
		'attributes'            => __( 'Task Attributes', 'emp' ),
		'parent_item_colon'     => __( 'Parent Task:', 'emp' ),
		'all_items'             => __( 'All Tasks', 'emp' ),
		'add_new_item'          => __( 'Add New Task', 'emp' ),
		'add_new'               => __( 'Add New Task', 'emp' ),
		'new_item'              => __( 'New Task', 'emp' ),
		'edit_item'             => __( 'Edit Task', 'emp' ),
		'update_item'           => __( 'Update Task', 'emp' ),
		'view_item'             => __( 'View Task', 'emp' ),
		'view_items'            => __( 'View Tasks', 'emp' ),
		'search_items'          => __( 'Search Tasks', 'emp' ),
		'not_found'             => __( 'Task Not found', 'emp' ),
		'not_found_in_trash'    => __( 'Task Not found in Trash', 'emp' ),
		'featured_image'        => __( 'Featured Image', 'emp' ),
		'set_featured_image'    => __( 'Set featured image', 'emp' ),
		'remove_featured_image' => __( 'Remove featured image', 'emp' ),
		'use_featured_image'    => __( 'Use as featured image', 'emp' ),
		'insert_into_item'      => __( 'Insert into item', 'emp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'emp' ),
		'items_list'            => __( 'tasks list', 'emp' ),
		'items_list_navigation' => __( 'tasks list navigation', 'emp' ),
		'filter_items_list'     => __( 'Filter tasks list', 'emp' ),
	);
	 
	 $args = array(
            'label' => __( 'Task', 'emp' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_task_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title','comments' ),
        );


	register_post_type( 'task', $args );

    }
}
add_action( 'init', 'empfohlen_task_post_type' );



 
 


function add_task_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_task' ); 
    $admins->add_cap( 'read_task' ); 
    $admins->add_cap( 'delete_task' ); 
    $admins->add_cap( 'create_tasks' ); 
    $admins->add_cap( 'edit_tasks' ); 
    $admins->add_cap( 'publish_tasks' ); 
    $admins->add_cap( 'delete_tasks' ); 
    $admins->add_cap( 'publish_tasks' ); 

}
add_action( 'admin_init', 'add_task_caps');





 
/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_task_template', 99 );
// add_filter('single_task', 'my_custom_task_template');
function my_custom_task_template($single) {
    global $post;
     // echo "<pre> my_custom_task_template post "; print_r( $post ); echo "</pre> ";  
    /* Checks for single template by post type */
    if ( $post->post_type == 'task' ) {
        // if ( file_exists( EMPFOHLEN_DIR . 'public/partials/task.php' ) ) {
            return EMPFOHLEN_DIR . 'public/partials/task/task.php';
        // }
    }
    return $single;
}






///////////////////////////////////////////////////////////////////////////////////

add_action('parse_request', 'emp_submit_task_post', 1);
// add_action('submit_task_port', 'emp_submit_task_post', 1);
// add_action( 'init', 'emp_submit_task_post' );
function emp_submit_task_post(){
  if( isset( $_POST['action'] ) && $_POST['action'] == 'submit_task' ){

    // Verify nonce
    $is_submitted = (isset($_POST['emp_submit_task_nonce']) && wp_verify_nonce($_POST['emp_submit_task_nonce'], 'emp-submit-task-nonce')) ? true : false;
    if($is_submitted){

 


        $allowed_files_type = array(
            'text/plain',           'application/json',             'application/xml',  'application/javascript',
            'image/png',            'image/jpeg',                   'image/jpeg',       'image/gif',                'image/bmp',
            'application/zip',      'application/x-rar-compressed',
            'audio/mpeg',           'video/quicktime',              'video/quicktime',
            'application/pdf',      'image/vnd.adobe.photoshop',
            'application/msword',   'application/vnd.ms-powerpoint', 'application/vnd.ms-excel',
        );




        if ( ! session_id() ) { session_start(); }
        $_SESSION['task_error'] = array();
        $_SESSION['task_success'] = '';
        
        $postData = $_POST;
        $task = get_post( (int) $postData['task_id']);
       
        $current_user = wp_get_current_user();
        $userData = $current_user->data;
        $user_id = (int) $userData->ID;
       
        // check if task exist and not empty 
        if(empty($task)){
            $_SESSION['task_error'][] =  'Task does not exist';
            wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
            return false; 
        }

        // check if this task is assign to this user. 
        $task_request_id = (int) get_field('request_id', $task->ID);
        $request = get_post($task_request_id);
        $req_member_id = (int) get_field('member_id', $request->ID);
        
        if ( $user_id !== $req_member_id ){
            $_SESSION['task_error'][] =  'You are not allowed to submit this task';
            wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
            return false; 
        }

        // update acf meta data task_content
        if (isset($postData['p_t_content_editor'])) {
            $p_t_content_editor = wp_kses_post($postData['p_t_content_editor']);
            update_post_meta( $task->ID, 'task_content', $p_t_content_editor );
        }

        // update acf meta data task_content
        if (isset($postData['p_t_additional_info_editor'])) {
            $p_t_additional_info_editor = wp_kses_post($postData['p_t_additional_info_editor']);
            update_post_meta( $task->ID, 'task_additional_info', $p_t_additional_info_editor );
        }

        update_post_meta( $task->ID, 'task_status', 'submitted');


        // echo "<pre> _FILES "; print_r( $_FILES ); echo "</pre> ";  
        $task_files = $_FILES['task_files'];
        if(!empty($task_files)){

            $upload_dir = wp_get_upload_dir(); 
            $dest_dir = $upload_dir['basedir'].'/userdata/'.$user_id.'/task/'.$task->ID; 

            // echo "<pre> des_dir "; print_r( $dest_dir ); echo "</pre> ";  
            if(!is_dir($dest_dir)) {  mkdir($dest_dir, 0777, true); }
            foreach ($task_files['size'] as $f_key => $fs_value) {

              if ( !empty($task_files['tmp_name'][$f_key]) ){

                   $file_mime = mime_content_type( $task_files['tmp_name'][$f_key] );  
                     // echo "<pre> file_mime  "; print_r( $file_mime  ); echo "</pre> ";  
                     if( !in_array($file_mime, $allowed_files_type) ){
                          $_SESSION['task_error'][] =  $file_mime.' files not allowed to upload';
                          continue; 
                     }

                      $tmp_name = $task_files["tmp_name"][$f_key];
                      $name = basename($task_files["name"][$f_key]);
                      move_uploaded_file($tmp_name, $dest_dir.'/'.$name);

                    }

            }
        }
       //  exit; 




        $_SESSION['task_success'] = 'Task succesfully saved';
        wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
        exit(); 

       }else{

         if ( ! session_id() ) { session_start(); }
         $_SESSION['task_error'][] = 'Token Expired';
         wp_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
         exit(); 
       }

        
     } // is_submitted
  }// function end 








 
// custom meta boxes 
add_action( 'add_meta_boxes',  'add_meta_boxes_task_document' );
 function add_meta_boxes_task_document() {
     
      add_meta_box('advanced-options',
            __( 'Upload Documents', 'emp_task' ),
            'add_meta_boxes_task_document_callback',
            'task',
            'normal',
            'low'
        );


      add_meta_box('advanced-options_2',
            __( 'Task Invoice', 'emp_task' ),
            'add_meta_boxes_task_invoice_callback',
            'task',
            'side',
            'low'
        );


       add_meta_box('advanced-options_3',
            __( 'Task Project Info', 'emp_task' ),
            'add_meta_boxes_task_project_info_callback',
            'task',
            'normal',
            'high'
        );

}
 
 function add_meta_boxes_task_document_callback( $post, $metabox ) {
    // wp_nonce_field( 'advanced_options_data', 'advanced_options_nonce' );

     // echo "<pre> metabox "; print_r( $metabox ); echo "</pre> ";  
     // echo "<pre> post "; print_r( $post ); echo "</pre> ";  

     $task_request_id = (int) get_post_meta( $post->ID, 'request_id', true );
     $output = '<div class="task_documents">';
     if(!empty($task_request_id) && $task_request_id > 0) {
        $task_member_id = (int) get_post_meta($task_request_id,'member_id', true);
        // echo "<pre> task_member_id "; print_r( $task_member_id ); echo "</pre> ";  
         $upload_dir = wp_get_upload_dir(); 
         $dest_dir   = $upload_dir['basedir'].'/userdata/'.$task_member_id.'/task/'.$post->ID; 
         if(!is_dir($dest_dir)) {  mkdir($dest_dir, 0777, true); }
         $task_files = scandir($dest_dir);

         $ex_folders = array('..', '.');
         $task_files = array_diff($task_files, $ex_folders);
         $task_files = $task_files;

         $download_dir = $upload_dir['baseurl'].'/userdata/'.$task_member_id.'/task/'.$post->ID;
         $download_dir = $download_dir;
        
         if(!empty($task_files)){
            foreach ($task_files as $tf_key => $tf_v) {
                // $output .= '<div class="task_file_'.$tf_key.'">'.$tf_v.'</div>';
                $output .= '<div class="task_file tf_'.$tf_key.'">';
                $output .= '<div class="file-icon">';
                $output .= '<img src="'.get_site_url().'/wp-includes/images/media/default.png">';
                $output .= '</div>';
                $output .= '<span class="file_title">'.$tf_v.'</span>';
                $output .= '<span class="file_button"><a href="'.$download_dir.'/'.$tf_v.'">Download</a></span></div>';
            }
         }
     }
    
    $output .= '</div>';
    echo $output;
}

 function add_meta_boxes_task_invoice_callback( $post ){
     $task_request_id = (int) get_post_meta( $post->ID, 'request_id', true );
     // echo "<pre> post  "; print_r( $post ); echo "</pre> ";  
     $args = array(
            'post_type'              => array( 'invoice' ),
            'meta_query'             => array(
                array(
                    'key'     => 'invoice_task_id',
                    'value'   => $post->ID,
                ),
            ),
     );
     $inv_query = new WP_Query( $args );
     $invoice_exist = $inv_query->posts;
     // echo "<pre> invoice_exist "; print_r( $invoice_exist ); echo "</pre> ";  
     if (!empty($invoice_exist)){
        $invoice = $invoice_exist[0];
        $inv =  '<div class="invoice">';
        $inv .= '<a href="'.get_edit_post_link($invoice->ID).'">Invoice Detail</div>';
     } else {
        $inv =  '<div class="generate_invoice">';
        $inv .= '<a class="button button-success button-large generate_task_invoice" data-tid="'.$post->ID.'">Task Complete(Generate Invoice)</a>';
        $inv .= '</div>';
     }
     echo $inv;
 }


function add_meta_boxes_task_project_info_callback( $post ){

    $task_request_id = (int) get_post_meta( $post->ID, 'request_id', true );
    $project_id = (int) get_post_meta( $task_request_id, 'select_project_id', true );
    $project = get_post($project_id);

    $member_id = (int) get_post_meta( $task_request_id, 'member_id', true );
    $member = get_userdata( $member_id );

    // echo "<pre> project "; print_r( $project ); echo "</pre> ";  
    // echo "<pre> member "; print_r( $member ); echo "</pre> ";  

    $project_timer_enable =  get_post_meta( $project_id, 'timer_enable', true );
    $project_duration =  get_post_meta( $project_id, 'duration', true );
    $project_pay =  get_post_meta( $project_id, 'pay', true );
    $project_select_currency =  get_post_meta( $project_id, 'select_currency', true );
    $project_expiration_date =  get_post_meta( $project_id, 'expiration_date', true );

    $out = '<div class="task_info">';
        $out .= '<div class="task_p_info">';
            $out .= '<div> Project Title: <a href="'.get_edit_post_link($project_id).'">'.$project->post_title.'</a></div>';
            $out .= '<div> Project Timer: '.$project_timer_enable.'</div>';
            $out .= '<div> Project Duration: '.$project_duration.'</div>';
            $out .= '<div> Project Currency: '.$project_select_currency.'</div>';
            $out .= '<div> Project Pay: '.$project_pay.'</div>';
            $out .= '<div> Project Expiration Date: '.$project_expiration_date.'</div>';
        $out .= '</div>';
        $out .= '<div class="task_m_info">';
            $out .= '<div> Member Name: <a href="">'.$member->data->display_name.'</a></div>';
        $out .= '</div>';
    $out .= '</div>';
    echo $out;
}





// add script for confirm script. 
function jquery_confirm_load_scripts($hook) {  
    wp_enqueue_script( 'jquery_confirm', EMPFOHLEN_URI.'public/js/jquery-confirm.min.js', array('jquery'));
    wp_register_style( 'jquery_confirm',  EMPFOHLEN_URI.'public/css/jquery-confirm.min.css'  , false);
    wp_enqueue_style ( 'jquery_confirm' );
}
add_action('admin_enqueue_scripts', 'jquery_confirm_load_scripts');

 







// complete task and generate invoice. 
add_action( 'wp_ajax_nopriv_task_complete_generate_invoice', 'task_complete_generate_invoice' );
add_action( 'wp_ajax_task_complete_generate_invoice', 'task_complete_generate_invoice' );
function task_complete_generate_invoice() {
    
    // wp_send_json( $return); 
    if (!is_admin() || !defined( 'DOING_AJAX' ) || !DOING_AJAX ){
        // wp_send_json_success( 'admin is login and its ajax' ); 
        die();
    }// is_admin
    

   
    $task_id = (int) $_POST['tid'];
    if ( $task_id ){
        $task = get_post($task_id);
         
         // check if task not exist already
         $args = array(
            'post_type'              => array( 'invoice' ),
            'meta_query'             => array(
                array(
                    'key'     => 'task_id',
                    'value'   => $task_id,
                ),
            ),
        );
         
         $invoice_query = new WP_Query( $args );
         $invoice_exist = $invoice_query->posts;

         // check if invoice already exist for this task 
         if($invoice_exist){
            $return['status'] = 'error';
            $return['message'] = 'Invoice already exist';
             // echo "<pre>  "; print_r( $task_exist ); echo "</pre> ";  
            $invoice = $invoice_exist[0];
            $return['data'] = '<a class="btn" data-tid="'.$task_id.'" href="'.get_edit_post_link($invoice->ID).'">Invoice Detail</a>';
            wp_send_json($return); 

         }else{ 
          // create new invoice for this task 
            $invoice_id = wp_insert_post(array(
               'post_type'   => 'invoice',
               'post_title'  => 'Invoice for Task '.$task_id,
               'post_status' => 'publish',
            ));

            $invoice_id_code = 'INV'.$invoice_id.'_T'.$task_id;

            if ($invoice_id) {
               update_post_meta($invoice_id, 'invoice_code', $invoice_id_code);
               update_post_meta($invoice_id, 'invoice_task_id', $task_id);
            }

            // get request of this task 
            $request_id = (int) get_field('request_id', $task_id);
            if (!empty($request_id)){

              // add request id to invoice 
              update_post_meta($invoice_id, 'invoice_request_id', $request_id);

              // if request exist then get project of this request 
              $project_id = (int) get_field('select_project_id', $request_id);

              // get project amount and currency. 
              if (!empty($project_id)){
                $project_id = (int) get_field('select_project_id', $request_id);
                if ( $project_id > 0 ){
                  
                  $project_pay =   get_field('pay', $project_id);
                  $project_currency =   get_field('select_currency', $project_id);

                  update_post_meta($invoice_id, 'invoice_amount', $project_pay);
                  update_post_meta($invoice_id, 'invoice_currency', $project_currency);
                  update_post_meta($invoice_id, 'invoice_project_id', $project_id); 
                 

                  // get task member_id  
                  $member_id = (int) get_field('member_id', $request_id);
                  update_post_meta($invoice_id, 'invoice_member_id', $member_id); 


                  // $user_currency  = EmpHelper::getUserCurrency($member_id);
                  // $price          = get_post_meta($post->ID, 'price', true); //get_post_meta($post->ID, 'price', true);
                  // $user_price     =  EmpHelper::cc_base_to_currency($user_currency,$price);


                   // get member balance
                   $member_balance = get_field('balance', 'user_'.$member_id );

                    // echo "<pre> member_id "; print_r( $member_id ); echo "</pre> ";  
                    // echo "<pre> member_balance "; print_r( $member_balance ); echo "</pre> ";  

                   $balance_currency_exist = false;
                   if (!empty($member_balance)){
                    foreach ($member_balance as $mb_key => $mb_value) {
                        if ( $mb_value['balance_currency'] ==  $project_currency){
                          $member_balance[$mb_key]['balance_value'] = ((int) $mb_value['balance_value']) + ((int) $project_pay);
                          $balance_currency_exist = true;
                        }
                    }

                    if (!$balance_currency_exist){
                      $new_row = array(
                        'balance_currency' => $project_currency,
                        'balance_value' => (int) $project_pay
                      );
                      array_unshift($member_balance, $new_row);
                    }

                    update_field('balance', $member_balance,'user_'.$member_id );
                   
                   // not empty member_balance
                   }else{
                      $member_balance = array();
                      $new_row = array(
                          'balance_currency' => $project_currency ,
                          'balance_value' => (int) $project_pay
                        );
                      array_unshift($member_balance, $new_row);
                      update_field('balance', $member_balance,'user_'.$member_id );

                      // echo "<pre> member_balance after else "; print_r( $member_balance ); echo "</pre> ";  
                      // $member_balance2 = get_field('balance', 'user_'.$member_id );
                      // echo "<pre> member_balance2 "; print_r( $member_balance2 ); echo "</pre> ";  
                      // exit; 

                   }
                }
              }
            }
            
            // make the invoice status complete. 
            update_post_meta($task_id, 'task_status', 'completed');

            $return['status'] = 'success';
            $return['message'] = 'Invoice Created succesfully';
            $return['data'] = '<a class="button button-success button-large" data-tid="'.$task_id.'" href="'.get_edit_post_link($invoice_id).'">Invoice Detail</a>';
            



            // send email to user that his task has been completed and invoice has been created.  
            // $url = get_site_url(). '/my-account/?act=' .base64_encode( serialize($string));
            $user_info = get_userdata($member_id);
            $task_id_code = get_field( "task_id", $task->ID);
            $ehtml  = 'Your <a href="'.get_permalink($task->ID).'"> Task('.$task->post_title.')</a> has been completed and a new '; 
            $ehtml .= 'Invoice ('. $invoice_id_code.') has been created';
            $headers = array( 'Content-type: text/html' );
            wp_mail( $user_info->user_email, __('Task ('.$task_id_code.') Completed','emp') , $ehtml, $headers);



            wp_send_json($return); 
         }

    }
}
