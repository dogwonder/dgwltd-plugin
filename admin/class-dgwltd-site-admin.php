<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wp.dgw.ltd
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
	 * @var      string    $Dgwltd_Site    The ID of this plugin.
	 */
	private $Dgwltd_Site;

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

	 * @param      string $Dgwltd_Site       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $Dgwltd_Site, $version ) {

		$this->Dgwltd_Site = $Dgwltd_Site;
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

		wp_enqueue_style( $this->Dgwltd_Site, plugin_dir_url( __FILE__ ) . 'css/dgwltd-site-editor.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->Dgwltd_Site, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	}

}
