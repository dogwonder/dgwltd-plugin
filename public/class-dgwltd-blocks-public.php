<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dgw.ltd
 * @since      1.0.0
 *
 * @package    dgwltd_Blocks
 * @subpackage dgwltd_Blocks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    dgwltd_Blocks
 * @subpackage dgwltd_Blocks/public
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class dgwltd_Blocks_Public {

	/**
	 * The ID of this plugin.
	 *

	 * @access   private
	 * @var      string    $dgwltd_Blocks    The ID of this plugin.
	 */
	private $dgwltd_Blocks;


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

	 * @param      string $dgwltd_Blocks       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $dgwltd_Blocks, $version ) {

		$this->dgwltd_Blocks = $dgwltd_Blocks;
		$this->version       = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 */
	public function dgwltd_enqueue_theme_styles() {

		wp_enqueue_style( $this->dgwltd_Blocks, plugin_dir_url( __FILE__ ) . 'css/dgwltd-blocks-theme.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 */
	public function dgwltd_enqueue_theme_scripts() {
		
		// Remove array('jquery') from wp_enqueue_script as we don't want to be dependant on the WP jQuery core
		wp_enqueue_script( $this->dgwltd_Blocks, plugin_dir_url( __FILE__ ) . 'scripts/dgwltd-blocks.min.js', array(), $this->version, false );

	}

	/**
	 * Add type=module to script tags
	 *
	 */
	public function dgwltd_add_type_attribute($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if ( 'dgwltd-blocks' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
		return $tag;
	}


	/**
	 * Get the embedded video host, YouTube or Vimeo
	 *
	 */
	public static function dgwltd_parse_video_uri( $url ) {

		// Parse the url
		$parse = parse_url( $url );

		// Set blank variables
		$video_type = '';
		$video_id   = '';

		// Url is http://youtu.be/xxxx
		if ( isset( $parse['host'] ) && $parse['host'] == 'youtu.be' ) {

			$video_type = 'youtube';
			$video_id   = ltrim( $parse['path'], '/' );

		}

		// Url is http://www.youtube.com/watch?v=xxxx
		// or http://www.youtube.com/watch?feature=player_embedded&v=xxx
		// or http://www.youtube.com/embed/xxxx
		if ( isset( $parse['host'] ) && ( $parse['host'] == 'youtube.com' ) || isset( $parse['host'] ) && ( $parse['host'] == 'www.youtube.com' ) ) {

			$video_type = 'youtube';

			parse_str( $parse['query'], $output );

			// print_r($output);

			$video_id = $output['v'];

			if ( ! empty( $feature ) ) {
				$video_id = end( explode( 'v=', $parse['query'] ) );
			}

			if ( strpos( $parse['path'], 'embed' ) == 1 ) {
				$video_id = end( explode( '/', $parse['path'] ) );
			}
		}

		// Url is http://www.vimeo.com
		if ( isset( $parse['host'] ) && ( $parse['host'] == 'vimeo.com' ) || isset( $parse['host'] ) && ( $parse['host'] == 'www.vimeo.com' ) ) {

			$video_type = 'vimeo';

			$video_id = ltrim( $parse['path'], '/' );

		}

		// If recognised type return video array
		if ( ! empty( $video_type ) ) {

			$video_array = array(
				'type' => $video_type,
				'id'   => $video_id,
			);

			return $video_array;

		} else {

			return false;

		}
	}

	/**
	 * Get an ACF block's color settings.
	 *
	 * @param array $block The block settings and attributes.
	 */
	public static function dgwltd_get_block_attrs( $block = null ) {

		if ( ! $block ) {
			return;
		}

		$block_class = null;
		$block_style = null;
		$block_align = null;
		$block_align_text = null;

		if ( $block['backgroundColor'] ) {
			$block_class .= ' has-background has-' . $block['backgroundColor'] . '-background-color ';
		}

		if ( $block['textColor'] ) {
			$block_class .= ' has-text-color has-' . $block['textColor'] . '-color ';
		}

		if ( $block['align'] ) {
			$block_class .= ' align' . $block['align'];
		}

		if ( $block['alignText'] ) {
			$block_class .= ' has-text-align-' . $block['alignText'];
		}

		if ( is_array($block['style']) && $block['style']['color']['background'] ) {
			$block_class .= ' has-background ';
			$block_style .= 'background-color: ' . $block['style']['color']['background'] . ';';
		}

		if ( is_array($block['style']) && $block['style']['color']['text'] ) {
			$block_class .= ' has-text-color ';
			$block_style .= 'color: ' . $block['style']['color']['text'] . ';';
		}

		return array(
			'class' => $block_class,
			'style' => $block_style,
		);

	}

}
