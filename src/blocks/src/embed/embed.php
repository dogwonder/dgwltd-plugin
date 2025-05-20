<?php
/**
 * Lite Embed Block Template.
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
$class_name = 'dgwltd-block dgwltd-embed';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = DGWLTD_PLUGIN_PUBLIC::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Block fields
$embed = get_field( 'embed', false, false ) ? : '';
$v     = DGWLTD_PLUGIN_PUBLIC::dgwltd_parse_video_uri( $embed );
$vid   = $v['id'];

// Class list
$block_classes_arr = array( $class_name, $block_classes );
?>
 <div id="<?php echo $block_id; ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>
	<div class="dgwltd-embed__inner">
			<div class="dgwltd-embed__content">
				<?php if ( $v['type'] == 'youtube' ) : ?>
				<lite-youtube videoid="<?php echo $vid; ?>"></lite-youtube>    
				<?php elseif ( $v['type'] == 'vimeo' ) : ?>
				<lite-vimeo videoid="<?php echo $vid; ?>"></lite-vimeo>
				<?php else : ?>
					<?php the_field( 'embed' ); ?>
				<?php endif; ?>
			</div>
		</div>
 </div>
