<?php
/**
 * Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$block_id = $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values. and "align" values.
$class_name = 'dgwltd-block dgwltd-hero';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Random ID
$rand = substr( md5( microtime() ), wp_rand( 0, 26 ), 5 );

// Block Fields
$image           = get_field( 'background_image' ) ? : '';
$image_mobile    = get_field( 'background_image_mobile' ) ? : '';
$has_video        = get_field( 'has_video' ) ? : '';
$video           = get_field( 'video', false, false );

// Block attributes (set in native block editor)
$block_attrs  = Dgwltd_Site_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

$overlay  = get_field( 'overlay' ) ? : '';
if($overlay) {
	//Convert to RGB
	$overlay_color  = get_field( 'overlay_color' ) ? : '';
	$hex2rgb = Dgwltd_Site_Public::dgwltd_hex2rgb( $overlay_color );
	$overlay_opacity  = get_field( 'overlay_opacity' ) ? : '0.7';
	$overlay_opacity = $overlay_opacity / 100;
	//Loop through RGB values and add opacity
	$overlay_rgb = 'rgba(' . $hex2rgb[0] . ',' . $hex2rgb[1] . ',' . $hex2rgb[2] . ')';
	$overlay_rgba = 'rgba(' . $hex2rgb[0] . ',' . $hex2rgb[1] . ',' . $hex2rgb[2] . ',' . $overlay_opacity . ')';
	$overlay_rgba_full = 'rgba(' . $hex2rgb[0] . ',' . $hex2rgb[1] . ',' . $hex2rgb[2] . ', 0)';
}

// Classes
$block_image   = $image ? 'has-image ' : '';
$block_video   = $video ? 'has-video ' : '';
$block_overlay  = $overlay ? 'has-overlay ' : '';

// Class list
$block_classes_arr = array( $class_name, $block_classes, $block_image, $block_video, $block_overlay);

// JSX Innerblocks - https://www.billerickson.net/innerblocks-with-acf-blocks/
$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/list', 'core/buttons', 'core/button' );
$block_template = array(
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => 'Add title...',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => 'Add content...',
		),
	),
);
?>

<div id="<?php echo $block_id; ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>

			<?php if ( ! empty( $image ) ) : ?>
				<?php
				$image_tiny        = $image['sizes']['dgwltd-tiny'];
				if($image_mobile) {
					$image_small = $image_mobile['sizes']['dgwltd-medium'];	
					$image_small_width  = esc_attr( $image_mobile['sizes']['dgwltd-medium-width'] );
					$image_small_height = esc_attr( $image_mobile['sizes']['dgwltd-medium-height'] );
				} else {
					$image_small = $image['sizes']['dgwltd-medium'];
					$image_small_width  = esc_attr( $image['sizes']['dgwltd-medium-width'] );
					$image_small_height = esc_attr( $image['sizes']['dgwltd-medium-height'] );
				}
				$image_large       = $image['sizes']['dgwltd-large'];
				$image_alt         = esc_attr( $image['alt'] );
				$image_width       = esc_attr( $image['width'] );
				$image_height      = esc_attr( $image['height'] );
				// For Low quality image placeholders (LQIP)
				$base64Image  = Dgwltd_Site_Public::dgwltd_image_to_base64_data_uri( $image_tiny );
				?>
				<link rel="preload" href="<?php echo $image_small; ?>" as="image" media="(max-width: 39.6875em)">
				<link rel="preload" href="<?php echo $image_large; ?>" as="image" media="(min-width: 40.0625em)">
				<?php if ( $overlay ) : ?>
					<style>
					#<?php echo $block_id; ?>.dgwltd-hero:before {
						display: block;
						z-index: 2;
						content: '';
						position: absolute;
						top: 0;
						right: 0;
						bottom: 0;
						left: 0;
						background-color: <?php echo $overlay; ?>;
						opacity:<?php echo ($overlay_opacity ? $overlay_opacity : '0.7'); ?>;
					}
					</style>
				<?php endif; ?>
				<div class="dgwltd-block__background">
					<?php 
					//Don't lazyload this https://cloudfour.com/thinks/stop-lazy-loading-product-and-hero-images/
					?>
					<figure>
						<picture>
							<source media="(min-width: 64em)" srcset="<?php echo $image_large; ?>">
							<img 
							src="<?php echo $image_small; ?>" 
							alt="<?php echo $image_alt ?>" 
							width="<?php echo $image_small_width; ?>" 
							height="<?php echo $image_small_height; ?>" 
							style="background-image: url(<?php echo $base64Image; ?>)" 
							fetchpriority="high"
							/>
						</picture>
					</figure>
				</div>
			<?php endif; ?>    

			<div class="dgwltd-hero__wrapper">

				<div class="dgwltd-hero__inner">   

					<div class="dgwltd-hero__content stack">

						<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $block_template ) ); ?>" />
						
						<?php if ( ! empty( $video ) && $has_video ) : ?>
							<div class="dgwltd-hero__play">
								<a class="dgwltd-button popup-trigger" data-popup-trigger="videoModal<?php echo $rand; ?>">
									<?php esc_html_e( 'Watch', 'dgwltd' ); ?>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M356.5 235.5C363.6 239.9 368 247.6 368 256C368 264.4 363.6 272.1 356.5 276.5L212.5 364.5C205.1 369 195.8 369.2 188.3 364.9C180.7 360.7 176 352.7 176 344V167.1C176 159.3 180.7 151.3 188.3 147.1C195.8 142.8 205.1 142.1 212.5 147.5L356.5 235.5zM208 182.3V329.7L328.7 255.1L208 182.3zM0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM256 480C379.7 480 480 379.7 480 256C480 132.3 379.7 32 256 32C132.3 32 32 132.3 32 256C32 379.7 132.3 480 256 480z"/></svg>     
								</a>
							</div>
						<?php endif; ?>

						

					</div>
				</div>

			</div>

</div>

<?php if ( ! empty( $video ) && $has_video) : 
$parse = wp_parse_url( $video );

if ( $parse['host'] == 'youtu.be' ) {
	$video_type = 'youtube';
	$video_id   = ltrim( $parse['path'], '/' );
}

if ( ( $parse['host'] == 'youtube.com' ) || ( $parse['host'] == 'www.youtube.com' ) ) {

	$video_type = 'youtube';						
	$video_id   = ltrim( $parse['query'], 'v=' );

	if ( strpos( $parse['path'], 'embed' ) == 1 ) {
		$video_id = end( explode( '/', $parse['path'] ) );
	}
}
if ( ( $parse['host'] == 'vimeo.com' ) || ( $parse['host'] == 'www.vimeo.com' ) ) {
	$video_type = 'vimeo';
	$video_id   = ltrim( $parse['path'], '/' );
}
?>
						
<div 
 class="popup-modal" 
 data-popup-modal="videoModal<?php echo $rand; ?>"
 data-popup-type="<?php echo $video_type ? : 'none'; ?>"
 role="dialog"
 aria-labelledby="dialog-title"
 aria-modal="true"
 >
	<div class="popup-modal__dialog">
	<button class="popup-modal__close" data-modal-close aria-label="Close">
	<svg class="icon" viewBox="0 0 320 512" width="0.75em" height="0.75em" stroke="currentColor" stroke-width="2" role="presentation" focusable="false">
	<path d="M312.1 375c9.369 9.369 9.369 24.57 0 33.94s-24.57 9.369-33.94 0L160 289.9l-119 119c-9.369 9.369-24.57 9.369-33.94 0s-9.369-24.57 0-33.94L126.1 256L7.027 136.1c-9.369-9.369-9.369-24.57 0-33.94s24.57-9.369 33.94 0L160 222.1l119-119c9.369-9.369 24.57-9.369 33.94 0s9.369 24.57 0 33.94L193.9 256L312.1 375z"/>
	</svg>
	</button>	
	<h2 class="popup-modal__heading" data-modal-title id="dialog-title" tabindex="-1"><?php esc_html_e( 'Watch the video', 'dgwltd' ); ?></h2>
	<?php if ( $video_type == 'youtube' ) { ?>
		<iframe 
		id="youtube_player" 
		title="<?php esc_html_e( 'Video modal', 'dgwltd' ); ?>"
		src="http://www.youtube.com/embed/<?php echo $video_id; ?>?enablejsapi=1" 
		frameborder="0" 
		allowfullscreen 
		></iframe>
	<?php } ?>
	<?php if ( $video_type == 'vimeo' ) { ?>
		<iframe 
		id="vimeo_player" 
		title="<?php esc_html_e( 'Video modal', 'dgwltd' ); ?>"
		src="https://player.vimeo.com/video/<?php echo $video_id; ?>" 
		frameborder="0" 
		allowfullscreen 
		></iframe>
	<?php } ?>
	</div>
</div>
<?php endif; ?>