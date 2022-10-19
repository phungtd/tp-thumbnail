<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tpcrawl.com
 * @since      1.0.0
 *
 * @package    Tp_Thumbnail
 * @subpackage Tp_Thumbnail/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tp_Thumbnail
 * @subpackage Tp_Thumbnail/includes
 * @author     Phung Tran <contact@tpcrawl.com>
 */
class Tp_Thumbnail_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tp-thumbnail',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
