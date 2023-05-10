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
		wp_register_script( 'hero', plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/hero/hero.js' );

	}

	public function dgwltd_register_wp_blocks() {

		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/accordion/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/anchor/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/featured-boxes/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/call-to-action/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/details/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/embed/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/feature/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/hero/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/image/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/related/block.json' );
		register_block_type( plugin_dir_path( dirname( __FILE__ ) ) . 'src/blocks/summary-list/block.json' );

	}

	// save json with fields
	public function dgwltd_acf_json_save_point( $path ) {

		// update path
		$path = plugin_dir_path( dirname( __FILE__ ) ) . 'src/acf-json';
		// return
		return $path;

	}

	// load json with fields
	public function dgwltd_acf_json_load_point( $paths ) {

		unset( $paths[0] );

		// append path
		$paths[] = plugin_dir_path( dirname( __FILE__ ) ) . 'src/acf-json';
		// return
		return $paths;

	}

}
