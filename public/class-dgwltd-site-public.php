<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wp.dgw.ltd
 * @since      1.0.0
 *
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/public
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class Dgwltd_Site_Public {

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

	 * @param      string $dgwltd_site       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $dgwltd_site, $version ) {

		$this->dgwltd_site = $dgwltd_site;
		$this->version       = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 */
	public function dgwltd_enqueue_theme_styles() {

		wp_enqueue_style( $this->dgwltd_site, plugin_dir_url( __FILE__ ) . 'css/dgwltd-site-theme.css', array(), $this->version, 'all' );
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 */

	 public function dgwltd_enqueue_theme_scripts()
	 {
		 $asset_file = include plugin_dir_path(__DIR__) .
			 "dist/dgwltd-site-theme.asset.php";
		 wp_enqueue_script(
			 $this->dgwltd_site,
			 DGWLTD_SITE_PLUGIN_URL . "dist/dgwltd-site-theme.js",
			 $asset_file["dependencies"],
			 $asset_file["version"],
			 true
		 );
	 }

	/**
	 * Add type=module to script tags
	 *
	 */
	public function dgwltd_add_type_attribute($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if ( 'dgwltd-site' !== $handle ) {
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
		$parse = wp_parse_url( $url );

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

	public static function dgwltd_image_to_base64_data_uri( $imagePath ) {
		// Ensure the WordPress Filesystem API is available
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
	
		// Initialize the Filesystem API
		global $wp_filesystem;
		if ( ! WP_Filesystem() ) {
			error_log( 'Failed to initialize WordPress Filesystem API.' );
			return false;
		}
	
		// Determine if the path is a URL or a local file
		if ( filter_var( $imagePath, FILTER_VALIDATE_URL ) ) {
			// It's a remote URL; use wp_remote_get
			$response = wp_remote_get( $imagePath );
	
			if ( is_wp_error( $response ) ) {
				error_log( 'wp_remote_get failed: ' . $response->get_error_message() );
				return false;
			}
	
			$http_code = wp_remote_retrieve_response_code( $response );
			if ( $http_code !== 200 ) {
				error_log( "wp_remote_get returned HTTP code {$http_code} for URL: {$imagePath}" );
				return false;
			}
	
			$fileData = wp_remote_retrieve_body( $response );
			if ( empty( $fileData ) ) {
				error_log( "Empty response body from URL: {$imagePath}" );
				return false;
			}
	
			// Attempt to get MIME type from headers
			$mimeType = wp_remote_retrieve_header( $response, 'content-type' );
	
			if ( empty( $mimeType ) ) {
				// Fallback to using FileInfo if MIME type is not provided
				$finfo = finfo_open( FILEINFO_MIME_TYPE );
				if ( $finfo ) {
					$mimeType = finfo_buffer( $finfo, $fileData );
					finfo_close( $finfo );
				}
			}
		} else {
			// It's a local file path; use WordPress Filesystem API
			if ( ! $wp_filesystem->exists( $imagePath ) ) {
				error_log( "File does not exist: {$imagePath}" );
				return false;
			}
	
			if ( ! $wp_filesystem->is_readable( $imagePath ) ) {
				error_log( "File is not readable: {$imagePath}" );
				return false;
			}
	
			// Use WordPress's wp_check_filetype function to get the MIME type
			$filetype = wp_check_filetype( basename( $imagePath ) );
			$mimeType = $filetype['type'];
	
			if ( empty( $mimeType ) ) {
				error_log( "Unable to determine MIME type for file: {$imagePath}" );
				return false;
			}
	
			// Get the file contents using WordPress Filesystem API
			$fileData = $wp_filesystem->get_contents( $imagePath );
			if ( $fileData === false ) {
				error_log( "Failed to read file contents: {$imagePath}" );
				return false;
			}
		}
	
		// Ensure the MIME type is an image
		if ( strpos( $mimeType, 'image/' ) !== 0 ) {
			error_log( "File is not an image: {$imagePath}" );
			return false;
		}
	
		// Encode the data to Base64
		$base64Data = base64_encode( $fileData );
		if ( $base64Data === false ) {
			error_log( "Base64 encoding failed for file: {$imagePath}" );
			return false;
		}
	
		// Construct the data URI
		$dataURI = "data:{$mimeType};base64,{$base64Data}";
	
		return $dataURI;
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

		$blockOptStyle = $block['style'] ?? null;
		$blockOptBackgroundColor = $block['backgroundColor'] ?? null;
		$blockOptTextColor = $block['textColor'] ?? null;
		$blockOptAlign = $block['align'] ?? null;
		$blockOptAlignText = $block['alignText'] ?? null;
		$blockOptStyleColorBackground = $block['style']['color']['background'] ?? null;
		$blockOptStyleColorText = $block['style']['color']['text'] ?? null;

		if ( $blockOptBackgroundColor ) {
			$block_class .= ' has-background has-' . $block['backgroundColor'] . '-background-color ';
		}

		if ( $blockOptTextColor ) {
			$block_class .= ' has-text-color has-' . $block['textColor'] . '-color ';
		}

		if ( $blockOptAlign ) {
			$block_class .= ' align' . $block['align'];
		}

		if ( $blockOptAlignText ) {
			$block_class .= ' has-text-align-' . $block['alignText'];
		}

		if ( is_array($blockOptStyle) && $blockOptStyleColorBackground ) {
			$block_class .= ' has-background ';
			$block_style .= 'background-color: ' . $block['style']['color']['background'] . ';';
		}

		if ( is_array($blockOptStyle) && $blockOptStyleColorText ) {
			$block_class .= ' has-text-color ';
			$block_style .= 'color: ' . $block['style']['color']['text'] . ';';
		}

		return array(
			'class' => $block_class,
			'style' => $block_style,
		);

	}

	/*
	 * Convert a HEX color to RGB using PHP.
	 *
	 */
	public static function dgwltd_hex2rgb( $hex ) {

		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$rgb = array( $r, $g, $b );

		return $rgb; // returns an array with the rgb values
	}

	/*
	 * Setup the card type mappings.
	 *
	 */
	public static function dgwltd_get_card_type_mappings() {
		//Card type
		$cardTypeMappings = [
			'default' => [
				'has_image' => true, 
				'has_kicker' => true,
				'has_description' => true,
				'grid_classes' => "",
				'heading_level' => "h2",
				'thumbnail_size' => "dgwltd-medium-card"
			],
			'news-summary-card' => [ 
				'has_kicker' => false, 
				'has_category' => true, 
				'has_description' => true,
				'has_date' => true
			]
		];
		return $cardTypeMappings;
	}

}
