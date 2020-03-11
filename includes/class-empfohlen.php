<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.creativetech-solutions.com
 * @since      1.0.0
 *
 * @package    Empfohlen
 * @subpackage Empfohlen/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Empfohlen
 * @subpackage Empfohlen/includes
 * @author     Creative Develope <arsalan7720@gmail.com>
 */
class Empfohlen {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Empfohlen_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EMPFOHLEN_VERSION' ) ) {
			$this->version = EMPFOHLEN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'empfohlen';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->load_empfohlen_files();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Empfohlen_Loader. Orchestrates the hooks of the plugin.
	 * - Empfohlen_i18n. Defines internationalization functionality.
	 * - Empfohlen_Admin. Defines all hooks for the admin area.
	 * - Empfohlen_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		// load helper file
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-empfohlen-helper.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-empfohlen-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-empfohlen-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-empfohlen-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-empfohlen-public.php';

		$this->loader = new Empfohlen_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Empfohlen_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Empfohlen_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Empfohlen_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Empfohlen_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Empfohlen_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}


	// include all required files for the plugin.
	public function load_empfohlen_files(){	

		// custom post type 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/project-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/ticket-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/taxonomy/skills-taxonomy.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/request-post-type.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/withdrawl-post-type.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/task-post-type.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/invoice-post-type.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/review-post-type.php'; 



		// shortcodes 
		if ( $this->is_request( 'frontend' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/registeration/registeration_shotcode.php';
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/profile/profile_shotcode.php';
		}

		// memeber page template 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/member/pagetemplater.php';
		  

		


	}

}
