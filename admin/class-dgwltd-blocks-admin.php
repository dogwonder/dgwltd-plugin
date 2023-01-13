<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dgw.ltd
 * @since      1.0.0
 *
 * @package    Dgwltd_Blocks
 * @subpackage Dgwltd_Blocks/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dgwltd_Blocks
 * @subpackage Dgwltd_Blocks/admin
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class Dgwltd_Blocks_Admin {

	/**
	 * The ID of this plugin.
	 *

	 * @access   private
	 * @var      string    $Dgwltd_Blocks    The ID of this plugin.
	 */
	private $Dgwltd_Blocks;

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

	 * @param      string $Dgwltd_Blocks       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $Dgwltd_Blocks, $version ) {

		$this->Dgwltd_Blocks = $Dgwltd_Blocks;
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
		 * defined in Dgwltd_Blocks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dgwltd_Blocks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->Dgwltd_Blocks, plugin_dir_url( __FILE__ ) . 'css/dgwltd-blocks-editor.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 */
	public function dgwltd_enqueue_admin_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dgwltd_Blocks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dgwltd_Blocks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->Dgwltd_Blocks, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	}

}
