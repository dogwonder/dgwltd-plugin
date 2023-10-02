<?php
/**
 * Define the blocks functionality.
 *
 * Loads and defines the ACF blocks for this plugin
 *
 * @since      1.0.0
 * @package    Dgwltd_Blocks
 * @subpackage Dgwltd_Blocks/includes
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class Dgwltd_Blocks_ACF {

	public function dgwltd_register_wp_block_scripts() {

		//As our JS files aren't built in libraries, we have to manually wp_register_script for that file before the block.json 
		wp_register_script( 'hero', DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'hero/hero.js' );

	}

	public function dgwltd_register_wp_blocks() {

		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'accordion/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'anchor/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'featured-boxes/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'call-to-action/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'embed/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'feature/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'hero/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'image/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'related/block.json' );
		register_block_type( DGWLTD_BLOCKS_PLUGIN_BLOCKS . 'summary-list/block.json' );

	}

	// Get blocks list and update some database options so it's more performant
	public function dgwltd_acf_get_blocks() {
		// Check for options.
		$blocks  = get_option( 'dgwltd_acf_blocks' );
		$version = get_option( 'dgwltd_acf_blocks_version' );
	
		if ( empty( $blocks ) || version_compare( DGWLTD_BLOCKS_VERSION, $version ) || ( function_exists( 'wp_get_environment_type' ) && 'production' !== wp_get_environment_type() ) ) {
			$blocks = scandir( DGWLTD_BLOCKS_PLUGIN_BLOCKS );
			$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store' ) ) );
	
			// Update our options.
			update_option( 'dgwltd_acf_blocks', $blocks );
			update_option( 'dgwltd_acf_blocks_version', DGWLTD_BLOCKS_VERSION );
		}
	
		return $blocks;
	}

	// Register options page
	public function dgwltd_register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => 'Site General Settings',
					'menu_title' => 'Site Settings',
					'menu_slug'  => 'site-general-settings',
					'capability' => 'edit_posts',
					'redirect'   => false,
				)
			);
		}
	}

	/**
	 * Set a custom ACF JSON load path.
	 *
	 * @link https://www.advancedcustomfields.com/resources/local-json/#loading-explained
	 *
	 * @param array $paths Existing, incoming paths.
	 *
	 * @return array $paths New, outgoing paths.
	 *
	 * @since 0.1.1
	 */
	public function dgwltd_acf_json_load_paths( $paths ) {
		$paths[] = DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/field-groups';
		$paths[] = DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/options-pages';
		$paths[] = DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/post-types';
		$paths[] = DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/taxonomies';
		return $paths;
	}
	/**
	 * Set custom ACF JSON save point for
	 * ACF generated post types.
	 *
	 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
	 *
	 * @return string $path New, outgoing path.
	 *
	 * @since 0.1.1
	 */
	public function dgwltd_acf_json_save_path_for_post_types() {
		return DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/post-types';
	}

	/**
	 * Set custom ACF JSON save point for
	 * ACF generated field groups.
	 *
	 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
	 *
	 * @return string $path New, outgoing path.
	 *
	 * @since 0.1.1
	 */
	public function dgwltd_acf_json_save_path_for_field_groups() {
		return DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/field-groups';
	}

	/**
	 * Set custom ACF JSON save point for
	 * ACF generated taxonomies.
	 *
	 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
	 *
	 * @return string $path New, outgoing path.
	 *
	 * @since 0.1.1
	 */
	public function dgwltd_acf_json_save_path_for_taxonomies() {
		return DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/taxonomies';
	}

	/**
	 * Set custom ACF JSON save point for
	 * ACF generated Options Pages.
	 *
	 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
	 *
	 * @return string $path New, outgoing path.
	 *
	 * @since 0.1.1
	 */
	public function dgwltd_acf_json_save_path_for_option_pages() {
		return DGWLTD_BLOCKS_PLUGIN_DIR . 'src/acf-json/options-pages';
	}

	/**
	 * Customize the file names for each file.
	 *
	 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
	 *
	 * @param string $filename  The default filename.
	 * @param array  $post      The main post array for the item being saved.
	 * @param string $load_path The path that the item was loaded from.
	 *
	 * @return string $filename
	 *
	 * @since  0.1.1
	 */
	public function dgwltd_acf_json_filename( $filename, $post, $load_path ) {
		$filename = str_replace(
			array(
				' ',
				'_',
			),
			array(
				'-',
				'-',
			),
			$post['title']
		);

		$filename = strtolower( $filename ) . '.json';

		return $filename;
	}


}
