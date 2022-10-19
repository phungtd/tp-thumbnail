<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tpcrawl.com
 * @since      1.0.0
 *
 * @package    Tp_Thumbnail
 * @subpackage Tp_Thumbnail/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tp_Thumbnail
 * @subpackage Tp_Thumbnail/includes
 * @author     Phung Tran <contact@tpcrawl.com>
 */
class Tp_Thumbnail_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( 'tp_thumbnail', array(
				'force'      => '',
				'width'      => '1200',
				'height'     => '630',
				'fontName'   => 'Oswald-Regular',
				'fontSize'   => '60',
				'color'      => '#ffffff',
				'background' => '#003f5c, #503d5c, #008585, #666b5e',
				'shadow'     => '#000000',
			)
		);
	}

}
