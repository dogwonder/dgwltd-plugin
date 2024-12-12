<?php
/**
 * Promo Card Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$block_id = $block["id"] . '-' . uniqid();
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values. and "align" values.
$class_name = 'dgwltd-block dgwltd-promo-card';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = dgwltd_Site_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Block Fields
$type = get_field( 'type' ) ? : 'image';
$image = get_field( 'image' ) ? : '';
$embed = get_field( 'embed', false, false ) ? : '';
$video = get_field( 'video' ) ? : '';
$reversed         = get_field( 'reversed' ) ? : '';

// Classes
$block_type = $type ? 'has-' . $type : '';

// Class list
$block_classes_arr  = array( $class_name, $block_classes, $block_type );

// JSX Innerblocks - https://www.billerickson.net/innerblocks-with-acf-blocks/
$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/html', 'core/button' );
$block_template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => 'Add lede...', 
			'className'	=> 'dgwltd-promo-card__kicker'
		),
	),
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => 'Add title...',
		),
	),
	array(
		'core/button',
		array(
			'placeholder' => 'Button label...',
		),
	),
);
?>

<div id="<?php echo $block_id; ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?><?php echo ( $reversed ? ' data-state="reversed"' : '' ); ?>>
	<div class="dgwltd-promo-card__wrapper">
		<div class="dgwltd-promo-card__inner">   

			<div class="dgwltd-promo-card__content">
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $block_template ) ); ?>" />
			</div>

			<?php 
				if ($type == 'image' && !empty($image)) {
					require DGWLTD_SITE_PLUGIN_BLOCKS . 'build/promo-card/image.php';
				} elseif ($type == 'embed' && !empty($embed)) {
					require DGWLTD_SITE_PLUGIN_BLOCKS . 'build/promo-card/embed.php';
				} elseif ($type == 'video' && !empty($video)) {
					require DGWLTD_SITE_PLUGIN_BLOCKS . 'build/promo-card/video.php';
				}
			?>
		
		</div>
	</div>
</div>