<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wp.dgw.ltd
 * @since      1.0.0
 *
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/includes
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class Dgwltd_Site {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *

	 * @access   protected
	 * @var      Dgwltd_Site_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *

	 * @access   protected
	 * @var      string    $dgwltd_site    The string used to uniquely identify this plugin.
	 */
	protected $dgwltd_site;

	/**
	 * The current version of the plugin.
	 *

	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {
		if ( defined( 'DGWLTD_SITE_VERSION' ) ) {
			$this->version = DGWLTD_SITE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->dgwltd_site = 'dgwltd-site';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_acf_hooks();
		$this->define_block_hooks();
		$this->define_site_rules();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dgwltd_Site_Loader. Orchestrates the hooks of the plugin.
	 * - Dgwltd_Site_I18n. Defines internationalization functionality.
	 * - Dgwltd_Site_Admin. Defines all hooks for the admin area.
	 * - Dgwltd_Site_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *

	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'admin/class-dgwltd-site-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'public/class-dgwltd-site-public.php';

		/**
		 * The class responsible for defining all custom ACF functionality
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-acf.php';

		/**
		 * The class responsible for defining all actions that occur for building out the custom blocks
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-blocks.php';

		/**
		 * The class responsible for defining all actions that occur for building out the custom rules
		 */
		require_once DGWLTD_SITE_PLUGIN_DIR . 'includes/class-dgwltd-site-rules.php';

		$this->loader = new Dgwltd_Site_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dgwltd_Site_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *

	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dgwltd_Site_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *

	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dgwltd_Site_Admin( $this->get_dgwltd_Site(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'dgwltd_enqueue_admin_styles' );
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'dgwltd_enqueue_admin_scripts' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'dgwltd_enqueue_variations_scripts' );

		
	}

	/**
	 * Register all of the hooks related to the ACF blocks area functionality
	 * of the plugin.
	 *

	 * @access   private
	 */
	private function define_acf_hooks() {

		$plugin_acf = new Dgwltd_Site_ACF();

		// $this->loader->add_action( 'init', $plugin_acf, 'dgwltd_register_wp_block_scripts');
		$this->loader->add_action( 'init', $plugin_acf, 'dgwltd_register_wp_blocks' );
		// $this->loader->add_action( 'init', $plugin_acf, 'dgwltd_acf_get_blocks' );
		$this->loader->add_action( 'init', $plugin_acf, 'dgwltd_register_options_page' );

		$this->loader->add_filter( 'acf/json/load_paths', $plugin_acf, 'dgwltd_acf_json_load_paths' );
		$this->loader->add_filter( 'acf/settings/save_json/type=acf-field-group', $plugin_acf, 'dgwltd_acf_json_save_path_for_field_groups' );
		$this->loader->add_filter( 'acf/settings/save_json/type=acf-ui-options-page', $plugin_acf, 'dgwltd_acf_json_save_path_for_option_pages' );
		$this->loader->add_filter( 'acf/settings/save_json/type=acf-post-type', $plugin_acf, 'dgwltd_acf_json_save_path_for_post_types' );
		$this->loader->add_filter( 'acf/settings/save_json/type=acf-taxonomy', $plugin_acf, 'dgwltd_acf_json_save_path_for_taxonomies' );
		$this->loader->add_filter( 'acf/json/save_file_name', $plugin_acf, 'dgwltd_acf_json_filename', 10, 3 );

		$this->loader->add_filter('acf/prepare_field/name=featured_card_id', $plugin_acf, 'dgwltd_acf_field_wrapper_class');

	}

	/**
	 * Register all of the hooks related to the Custom blocks area functionality
	 * of the plugin.
	 *

	 * @access   private
	 */
	private function define_block_hooks() {

		$plugin_blocks = new Dgwltd_Site_Blocks();
		
		//Add data attributes to gallery block
		add_filter( 'render_block_core/gallery', $plugin_blocks, 'dgwltd_utility_edit_gallery_markup', 10, 3 );
		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *

	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dgwltd_Site_Public( $this->get_dgwltd_Site(), $this->get_version() );

		$this->loader->add_filter('script_loader_tag', $plugin_public, 'dgwltd_add_type_attribute', 10, 3);

		// We load these in the theme, so we don't need these in this instance
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dgwltd_enqueue_theme_styles' );

		// Load theme scripts
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dgwltd_enqueue_theme_scripts' );


	}

	
	/**
	 * Register all of the hooks related to the ACF blocks area functionality
	 * of the plugin.
	 *

	 * @access   private
	 */
	private function define_site_rules() {

		$plugin_rules = new Dgwltd_Site_Rules();

		/**
 		* Allow-list the block types available in the inserter.
 		*/
		// $this->loader->add_filter( 'allowed_block_types_all', $plugin_rules, 'dgwltd_register_block_rules' );

		/**
 		* Apply user filters to theme.json data.
 		*/
		$this->loader->add_action( 'after_setup_theme', $plugin_rules, 'dgwltd_apply_theme_json_user_filters' );

		/**
 		* Restict headings
 		*/
		 $this->loader->add_filter( 'register_block_type_args', $plugin_rules, 'dgwltd_restrict_heading_levels', 10, 2 );

		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_dgwltd_Site() {
		return $this->dgwltd_site;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dgwltd_Site_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
