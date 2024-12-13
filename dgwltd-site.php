<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin. Based on http://wppb.io // https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * @link              https://wp.dgw.ltd
 * @since             1.0.0
 * @package           Dgwltd_Site
 *
 * @wordpress-plugin
 * Plugin Name:       DGW.ltd: Site
 * Plugin URI:        https://wp.dgw.ltd
 * Description:       Accompanying plugin for the DGW.ltd theme
 * Version: 		  1.0.5
 * Author:            Dogwonder Ltd
 * Author URI:        https://richholman.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/dogwonder/dgwltd-plugin
 * Primary Branch: main 
 * Text Domain:       dgwltd-site
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants
 */
$versionData = wp_remote_get(plugin_dir_url( __FILE__ ) . 'package.json');
if (is_wp_error($versionData)) {
    $pkgVersion = '0.0.1';
} else {
    $versionContents = wp_remote_retrieve_body($versionData);
    $package = json_decode($versionContents, true);
    $pkgVersion = $package['version'] ?? '0.0.1';
}

define( 'DGWLTD_SITE_VERSION', $pkgVersion );
define( 'DGWLTD_SITE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DGWLTD_SITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DGWLTD_SITE_PLUGIN_BLOCKS', DGWLTD_SITE_PLUGIN_DIR . 'src/blocks/' );

require_once DGWLTD_SITE_PLUGIN_DIR . '/vendor/autoload.php';
( new \Fragen\Git_Updater\Lite( __FILE__ ) )->run('https://wp.dgw.ltd/wp-json/git-updater/v1/plugins-api/?slug=dgwltd-site');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dgwltd-site-activator.php
 */
function activate_dgwltd_site() {
	require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-activator.php';
	Dgwltd_Site_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dgwltd-site-deactivator.php
 */
function deactivate_dgwltd_site() {
	require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-deactivator.php';
	Dgwltd_Site_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dgwltd_site' );
register_deactivation_hook( __FILE__, 'deactivate_dgwltd_site' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dgwltd_site() {

	$plugin = new Dgwltd_Site();
	$plugin->run();

}
run_dgwltd_site();
