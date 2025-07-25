<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dgw.ltd
 * @since      1.0.0
 *
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/admin
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class DGWLTD_PLUGIN_ADMIN {

	/**
	 * The ID of this plugin.
	 *

	 * @access   private
	 * @var      string    $dgwltd_plugin    The ID of this plugin.
	 */
	private $dgwltd_plugin;

	/**
	 * The version of this plugin.
	 *

	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *

	 * @param      string $dgwltd_plugin       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $dgwltd_plugin, $version ) {

		$this->dgwltd_plugin = $dgwltd_plugin;
		$this->version       = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 * Only loads on block editor pages for optimal performance.
	 */
	public function dgwltd_enqueue_admin_styles() {

		// Only load admin styles in block editor context
		$screen = get_current_screen();
		if ( ! $screen || ! $screen->is_block_editor() ) {
			return;
		}

		wp_enqueue_style( $this->dgwltd_plugin, plugin_dir_url( __FILE__ ) . 'css/dgwltd-plugin-editor.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 */
	public function dgwltd_enqueue_admin_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in DGWLTD_PLUGIN_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The DGWLTD_PLUGIN_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$asset_file = include plugin_dir_path(__DIR__) .
			 "dist/dgwltd-plugin-editor.asset.php";
		 wp_enqueue_script(
			 $this->dgwltd_plugin,
			 DGWLTD_PLUGIN_URL . "dist/dgwltd-plugin-editor.js",
			 $asset_file["dependencies"],
			 $asset_file["version"],
			 true
		 );

	}
	
	/**
	 * Register the JavaScript for blocks variation
	 *
	 */
	public function dgwltd_enqueue_variations_scripts() {
		$asset_file = include plugin_dir_path(__DIR__) .
			 "dist/dgwltd-plugin-variations.asset.php";
		 wp_enqueue_script(
			 $this->dgwltd_plugin,
			 DGWLTD_PLUGIN_URL . "dist/dgwltd-plugin-variations.js",
			 $asset_file["dependencies"],
			 $asset_file["version"],
			 true
		 );
	}



}
