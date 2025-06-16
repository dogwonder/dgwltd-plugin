<?php
/**
 * Define the blocks functionality.
 *
 * Loads and defines the ACF blocks for this plugin
 *
 * @since      1.0.0
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/includes
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class DGWLTD_PLUGIN_ACF {

	public function dgwltd_register_wp_blocks() {

		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/accordion/block.json' );
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/banner/block.json' );
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/breadcrumbs/block.json' );
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/cards/block.json' );
    	register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/cards/query/block.json' );
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/embed/block.json' );
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/hero/block.json' );
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/promo-card/block.json' );
	

		//Native
		register_block_type( DGWLTD_PLUGIN_PLUGIN_BLOCKS . 'build/picker/block.json' );

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
		$paths[] = DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/field-groups';
		$paths[] = DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/options-pages';
		$paths[] = DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/post-types';
		$paths[] = DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/taxonomies';
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
		return DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/post-types';
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
		return DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/field-groups';
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
		return DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/taxonomies';
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
		return DGWLTD_PLUGIN_PLUGIN_DIR . 'src/acf-json/options-pages';
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

	//Add CSS class to ACF field to hide it from the admin
	public function dgwltd_acf_field_wrapper_class($field)
	{
		$field['wrapper']['class'] = 'hidden';
		return $field;
	}


}
