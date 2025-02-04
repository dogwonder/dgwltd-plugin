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
class Dgwltd_Site_Admin {

	/**
	 * The ID of this plugin.
	 *

	 * @access   private
	 * @var      string    $dgwltd_site    The ID of this plugin.
	 */
	private $dgwltd_site;

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

	 * @param      string $dgwltd_site       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $dgwltd_site, $version ) {

		$this->dgwltd_site = $dgwltd_site;
		$this->version       = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 */
	public function dgwltd_enqueue_admin_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dgwltd_Site_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dgwltd_Site_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->dgwltd_site, plugin_dir_url( __FILE__ ) . 'css/dgwltd-plugin-editor.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 */
	public function dgwltd_enqueue_admin_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dgwltd_Site_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dgwltd_Site_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$asset_file = include plugin_dir_path(__DIR__) .
			 "dist/dgwltd-plugin-editor.asset.php";
		 wp_enqueue_script(
			 $this->dgwltd_site,
			 DGWLTD_SITE_PLUGIN_URL . "dist/dgwltd-plugin-editor.js",
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
			 $this->dgwltd_site,
			 DGWLTD_SITE_PLUGIN_URL . "dist/dgwltd-plugin-variations.js",
			 $asset_file["dependencies"],
			 $asset_file["version"],
			 true
		 );
		
	}



}
