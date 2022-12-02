<?php
/**
 * Details Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$block_id = 'block-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values. and "align" values.
$class_name = 'dgwltd-block dgwltd-details';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = dgwltd_blocks_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Block fields
$summary = esc_html( get_field( 'summary' ) ) ? : '';
$details = get_field( 'details' ) ? : '';

// Class list
$block_classes_arr = array( $class_name, $block_classes );
?>
<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>
  <details class="govuk-details" data-module="govuk-details">
	<summary class="govuk-details__summary">
	<?php if ( ! empty( $summary ) ) : ?>
	  <span class="govuk-details__summary-text">
		<?php echo $summary; ?>    
	  </span>
	<?php endif; ?>
	</summary>
	<?php if ( ! empty( $details ) ) : ?>
	<div class="govuk-details__text">
		<?php echo $details; ?>
	</div>
	<?php endif; ?>
  </details>
</div>
