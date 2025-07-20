<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin. Based on http://wppb.io // https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * @link              https://dgw.ltd
 * @since             1.0.0
 * @package           Dgwltd_Site
 *
 * @wordpress-plugin
 * Plugin Name:       DGW.ltd: Plugin
 * Plugin URI:        https://dgw.ltd
 * Description:       Accompanying plugin for the DGW.ltd theme
 * Version: 		  1.0.102
 * Author:            Dogwonder Ltd
 * Author URI:        https://richholman.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/dogwonder/dgwltd-plugin
 * Primary Branch: main 
 * Text Domain:       dgwltd-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants
 */
define( 'DGWLTD_PLUGIN_VERSION', '1.0.102' );
define( 'DGWLTD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DGWLTD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DGWLTD_PLUGIN_BLOCKS', DGWLTD_PLUGIN_DIR . 'src/blocks/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dgwltd-plugin-activator.php
 */
function activate_dgwltd_plugin() {
	require_once DGWLTD_PLUGIN_DIR . 'includes/class-dgwltd-plugin-activator.php';
	DGWLTD_PLUGIN_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dgwltd-plugin-deactivator.php
 */
function deactivate_dgwltd_plugin() {
	require_once DGWLTD_PLUGIN_DIR . 'includes/class-dgwltd-plugin-deactivator.php';
	DGWLTD_PLUGIN_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dgwltd_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_dgwltd_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require DGWLTD_PLUGIN_DIR . 'includes/class-dgwltd-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dgwltd_plugin() {

	$plugin = new Dgwltd_Site();
	$plugin->run();

}
run_dgwltd_plugin();