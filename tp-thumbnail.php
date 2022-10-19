<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tpcrawl.com
 * @since             1.0.0
 * @package           Tp_Thumbnail
 *
 * @wordpress-plugin
 * Plugin Name:       TP Thumbnail
 * Plugin URI:        https://tpcrawl.com
 * Description:       Auto generate post thumbnail from post title.
 * Version:           1.0.0
 * Author:            Phung Tran
 * Author URI:        https://tpcrawl.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tp-thumbnail
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TP_THUMBNAIL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tp-thumbnail-activator.php
 */
function activate_tp_thumbnail() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tp-thumbnail-activator.php';
	Tp_Thumbnail_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tp-thumbnail-deactivator.php
 */
function deactivate_tp_thumbnail() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tp-thumbnail-deactivator.php';
	Tp_Thumbnail_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tp_thumbnail' );
register_deactivation_hook( __FILE__, 'deactivate_tp_thumbnail' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tp-thumbnail.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tp_thumbnail() {

	$plugin = new Tp_Thumbnail();
	$plugin->run();

}

run_tp_thumbnail();
