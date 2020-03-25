<?php if ( ! defined( 'ABSPATH' ) ) exit; 

class MemberPageTemplater {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new MemberPageTemplater();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		$this->templates = array();


		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}

		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);


		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);



		


		// Add your templates to this array.
		$this->templates = array('member-template.php' => 'MemberDashboard',);

	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		// Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		// Allows filtering of file path
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );

		$file =  $filepath . get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

}


add_action( 'init', array( 'MemberPageTemplater', 'get_instance' ) );
// add_action( 'plugins_loaded', array( 'MemberPageTemplater', 'get_instance' ) );






function template_login_permit() {

  if (is_page_template('member-template.php')) {
    // if user is not logged in then redirect him to login/registeration page.
		if (!is_admin()){
			if (!is_user_logged_in()){
				$empfohlen_setting_options = get_option( 'emp_setting' );
				$emp_login_page = (int) (isset($empfohlen_setting_options['emp_login_page'])?($empfohlen_setting_options['emp_login_page']):0); // Currency
				if ( $emp_login_page > 0 ){

					 $login_page = get_page($emp_login_page);
		       if ( ! session_id() ) { session_start(); }
		       $_SESSION['login_error'] = array('Please login First');

					 wp_redirect( get_permalink($emp_login_page)  );	
					 exit;
				}else{
					wp_redirect(home_url());	
					exit;
				}
			}else{
				// check if user has not member role then redirect hime to home page. 
				$user = wp_get_current_user();
				if (!in_array('member', (array)$user->roles)){
					wp_redirect(home_url());
					exit;
				}
			}
		}
    	 // echo "<pre> page is "; print_r( 'member-template.php' ); echo "</pre> ";  
  }

 


 
	$queried_post_type = get_query_var('post_type');
  if ( is_single() && $queried_post_type == 'task'  ) {

  	if (!is_admin()){
  		if (!is_user_logged_in()){
				$empfohlen_setting_options = get_option( 'emp_setting' );
				$emp_login_page = (int) (isset($empfohlen_setting_options['emp_login_page'])?($empfohlen_setting_options['emp_login_page']):0); // Currency
				if ( $emp_login_page > 0 ){
					 $login_page = get_page($emp_login_page);
		       if ( ! session_id() ) { session_start(); }
		       $_SESSION['login_error'] = array('Please login First');
					 wp_redirect( get_permalink($emp_login_page)  );	
					 exit;
				}else{
					wp_redirect(home_url());	
					exit;
				}
  		}else{

  			$user = wp_get_current_user();
				global $post;
				 
				 $member_id = (int) get_field('member_id', $post->ID);

				 if( $member_id !=  $user->ID ){
				 	wp_redirect(home_url());
				 }

				 // echo "<pre>  user"; print_r( $user  ); echo "</pre> ";   
				 // echo "<pre>  post"; print_r( $post  ); echo "</pre> ";  
				 // echo "<pre> member_id "; print_r(  $member_id ); echo "</pre> ";  
				 // exit; 
				
			}
  	}

  }

  // exit; 


}
add_action( 'template_redirect', 'template_login_permit' );







// add_action( 'wp_enqueue_scripts', 'my_scripts' );
// function my_scripts() {
//   wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array('jquery'), '1.0.0', true );
//   wp_localize_script( 'script-name', 'MyAjax', array(
//     'ajaxurl' => admin_url( 'admin-ajax.php' ),
//     'security' => wp_create_nonce( 'my-special-string' )
//   ));
// }
 
add_action( 'wp_ajax_project_submit_request', 'project_submit_request_callback' );
add_action( 'wp_ajax_nopriv_project_submit_request', 'project_submit_request_callback' );
function project_submit_request_callback() {
  // check_ajax_referer( 'my-special-string', 'security' );
  // echo 'It worked!';
  // die();
  // $return['login'] =  is_user_logged_in()?'yes':'no';
  if(is_user_logged_in()){

  	$current_user = wp_get_current_user();
		$userData = $current_user->data;
		$return['userData'] = $userData; 
		$user_role = $current_user->roles; // $userData;
		
		$user_groups = get_the_terms( (int) $userData->ID, 'user-group');
		$user_skill       =   get_user_meta($userData->ID, 'user_skill', true);

		if(!empty($user_groups)){
			$user_groups = wp_list_pluck( $user_groups, 'term_id' );
		}
		
		
		// check if user has role member
		$is_member = false; 
		if(is_array($user_role)){
			$is_member = (in_array('member', $user_role)) ? true : false; 
		}else{
			$is_member = ($user_role == 'member') ? true : false; 
		}

		if(!$is_member){
				$return['status'] =  'error'; 
      	$return['message'] =  __('Only members can submit a request ','empfohlen'); 
      	wp_send_json( $return ); 
		}

		$project_id = isset($_POST['pid'])?((int)$_POST['pid']):0;
		$project    = get_post( $project_id );

		// if project does not exist 
		if(is_null($project) || empty($project)){ 
			$return['status'] =  'error'; 
      $return['message'] =  'Project with id :'.$project_id.' does not exist'; 
      wp_send_json( $return ); 
		} 

		if( $project->post_type !== 'project' ){
			$return['status'] 	=  'error'; 
      $return['message'] 	=  'Project with id :'.$project_id.' does not exist.'; 
      wp_send_json( $return );
		}

		 // check if member is allowed to submit request for this project. 
		 $p_members_ids = get_field( "members", $project->ID );
		 $is_member_can_request = false; 
		 if(is_array($p_members_ids)){
			$is_member_can_request = (in_array($userData->ID, $p_members_ids)) ? true : false; 
		 }else{
			$is_member_can_request = ($p_members_ids == $userData->ID) ? true : false; 
		 }


		
		$p_skills = get_the_terms( (int) $project->ID, 'skill');
		if(!empty($p_skills)){
			$p_skills = wp_list_pluck( $p_skills, 'term_id' );
		}

		$p_group = get_field( "project_member_group", $project->ID );
		// $is_group_can_request = false; 
		$is_skill_can_request = false; 



	 // if (is_array($p_group) && !empty($p_group)){
	 // 	$is_group_can_request = (count(array_intersect($user_groups, $p_group)) > 0) ? true : false;
	 // }

		 // echo "<pre> p_group "; print_r( $p_skills ); echo "</pre> ";  
		 // echo "<pre> user_skill "; print_r( $user_skill ); echo "</pre> ";  

		 $p_skills = array_map( 'intval', $p_skills );
		 $user_skill = array_map( 'intval', $user_skill );
        

		if (is_array($p_skills) && !empty($p_skills)){
				$is_skill_can_request = (count(array_intersect($user_skill, $p_skills)) > 0) ? true : false;
		}

		 
		 if(!$is_member_can_request && !$is_skill_can_request ){
		 	$return['status'] 	=  'error'; 
      $return['message'] 	=  __('You are not allowed to submit request on this Project.','empfohlen'); 
      $return['debug']['is_member_can_request'] 	=  $is_member_can_request;
      $return['debug']['is_group_can_request'] 		=  $is_group_can_request;
      wp_send_json( $return );
		 }

		 // check if user already have request for this post 

		  // WP_Query arguments
			$args = array(
				'post_type'              => array( 'request' ),
				'meta_query'             => array(
					array(
						'key'     => 'select_project_id',
						'value'   => $project->ID,
					),
					array(
						'key'     => 'member_id',
						'value'   => $userData->ID,
					),
				),
			);

			// The Query
			$req_query = new WP_Query( $args );
			$request_exist = $req_query->posts;
			// wp_send_json($request_exist);

			if(!empty($request_exist)){
					$return['status'] =  'error'; 
		      $return['message'] =  __('You already submit request to this project','empfohlen'); 
		      wp_send_json( $return ); 
			}


		 // create a request post type. 
		 $request_id = wp_insert_post(array(
		   'post_type' => 'request',
		   'post_title' => 'Request from '.$userData->ID.' for Project: '.$project->ID,
		   'post_status' => 'publish',
		));


		 $request_id_code = 'R'.$request_id.'_U'.$userData->ID;

		if ($request_id) {
		   // insert post meta
		   update_post_meta($request_id, 'select_project_id', $project->ID);
		   update_post_meta($request_id, 'member_id', $userData->ID);
		   update_post_meta($request_id, 'request_status', 'submitted');
		   update_post_meta($request_id, 'request_id', $request_id_code);
		}


		ob_start(); 
			$post = $project;
			include(EMPFOHLEN_DIR.'public/partials/member/project_row_new.php');
			$return['row_html']  = ob_get_clean();
		ob_end_clean();


		 
		 $return['status'] =  'success'; 
		 $return['message'] =  __('Your request has been submited succesfully','empfohlen'); 
		 wp_send_json( $return ); 



  }else{

  	 	$return['status'] =  'error'; 
      $return['message'] =  __('please login to submit request','empfohlen'); 
      wp_send_json( $return ); 
  }


  wp_send_json( $return ); 
}