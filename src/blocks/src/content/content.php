<?php
/**
 * Content Block Template.
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
$class_name = 'dgwltd-block dgwltd-content-block';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = dgwltd_Site_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Block fields
$type = get_field( 'type' ) ? : 'illustrative';
$image = get_field( 'image' ) ? : '';

// Block options
$reversed         = get_field( 'reversed' ) ? : '';

// Classes
$block_image   = $image ? 'has-image ' : '';
$block_type_classes = $type ? 'is-' . $type . '-type ' : '';

// Class list
$block_classes_arr = array( $class_name, $block_classes, $block_image, $block_type_classes );

// JSX Innerblocks - https://www.billerickson.net/innerblocks-with-acf-blocks/
$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/button' );
$block_template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
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
<div id="<?php echo $block_id; ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ( $reversed ? ' data-state="reversed"' : '' ); ?><?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>
	<div class="dgwltd-content-block__inner">

			<div class="dgwltd-content-block__content">
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $block_template ) ); ?>" />
			</div>

			 <?php if ( ! empty( $image ) ) : ?>
					<?php
					$image_tiny        = $image['sizes']['dgwltd-tiny'];
					$image_small       = $image['sizes']['dgwltd-small'];
					$image_medium      = $image['sizes']['dgwltd-large'];
					$image_alt         = esc_attr( $image['alt'] );
					$image_width       = esc_attr( $image['width'] );
					$image_height      = esc_attr( $image['height'] );
					$image_small_width  = esc_attr( $image['sizes']['dgwltd-small-width'] );
					$image_small_height = esc_attr( $image['sizes']['dgwltd-small-height'] );
					// For Low quality image placeholders (LQIP)
					$type   = pathinfo( $image_tiny, PATHINFO_EXTENSION );
					$data   = file_get_contents( $image_tiny );
					$base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );
					?>
				<figure class="dgwltd-content-block__image">
					<picture class="frame">
						<source media="(min-width: 769px)" srcset="<?php echo ( $image_medium ? $image_medium : $image_small ); ?>">
						<img src="<?php echo $image_small; ?>" width="<?php echo $image_small_width; ?>" height="<?php echo $image_small_height; ?>" alt="<?php echo ( $image_alt ? $image_alt : '' ); ?>" loading="lazy" style="background-image: url(<?php echo $base64; ?>)" />
					</picture>
				</figure>
			<?php endif; ?>    
		</div>
 </div>
