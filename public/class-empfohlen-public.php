<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.creativetech-solutions.com
 * @since      1.0.0
 *
 * @package    Empfohlen
 * @subpackage Empfohlen/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Empfohlen
 * @subpackage Empfohlen/public
 * @author     Creative Develope <arsalan7720@gmail.com>
 */
class Empfohlen_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_shotcode();
		


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Empfohlen_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Empfohlen_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/empfohlen-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'empfohlen-css-responsive', plugin_dir_url( __FILE__ ) . 'css/empfohlen-responsive.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Empfohlen_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Empfohlen_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$randomizr = '-' . rand(100,999);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/empfohlen-public.js', array( 'jquery' ), $randomizr, false );

		wp_localize_script( $this->plugin_name, 'emp_vars', array('emp_ajax_url' => admin_url( 'admin-ajax.php' ), 'empfohlen_uri' => EMPFOHLEN_URI ));



	}



	public function load_shotcode() {
		// require_once plugin_dir_url( __FILE__ ) . 'partials/registeration/registeration_shotcode.php';
	}

}
