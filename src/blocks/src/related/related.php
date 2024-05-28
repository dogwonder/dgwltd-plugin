<?php
/**
 * Related pages Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

 // Create id attribute allowing for custom "anchor" value.
$block_id = $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values. and "align" values.
$class_name = 'dgwltd-block dgwltd-related';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = Dgwltd_Site_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Block fields
$block_title = esc_html( get_field( 'block_title' ) ) ? : __('Related', 'dgwltd');
$related_pages = get_field( 'related' ) ? : '';

// Class list
$block_classes_arr = array( $class_name, $block_classes );
?>
<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>
	<?php if ( $related_pages ) : ?>
	<div class="contextual-footer">
		<h2 class="govuk-heading-m"><?php echo $block_title ?></h2>
		<ul class="dgwltd-list">
			<?php foreach ( $related_pages as $related ) : ?>
			<li><a class="govuk-link" href="<?php echo esc_url( get_permalink( $related->ID ) ); ?>"><?php echo esc_html( get_the_title( $related->ID ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>
