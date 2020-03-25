<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.creativetech-solutions.com
 * @since      1.0.0
 *
 * @package    Empfohlen
 * @subpackage Empfohlen/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Empfohlen
 * @subpackage Empfohlen/includes
 * @author     Creative Develope <arsalan7720@gmail.com>
 */
class Empfohlen_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		// load_plugin_textdomain(
		// 	'empfohlen',
		// 	false,
		// 	dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		// );

		load_plugin_textdomain(
			'empfohlen',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
