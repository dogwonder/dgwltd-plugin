<?php
/**
 * Details Cards Template.
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
$class_name = 'dgwltd-block dgwltd-featured-boxes';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = Dgwltd_Site_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Content & Layout
$cards        = get_field( 'cards' ) ? : '';
$cards_manual = get_field( 'cards_manual' ) ? : '';
$cards_type   = get_field( 'cards_type' ) ? : '';

// Block options
$hide_image     = get_field( 'hide_image' ) ? : '';
$hide_description	= get_field( 'hide_description' ) ? : '';
$heading_type = get_field( 'heading_type' ) ? : '';

// Count the cards
if ( $cards_type === 'relationship' && ! empty( $cards ) ) :
	$cards_count = count( $cards ) ? 'dgwltd-cards-' . count( $cards ) : '0';
elseif ( $cards_type === 'manual' && ! empty( $cards_manual ) ) :
	$cards_count = count( $cards_manual ) ? 'dgwltd-cards-' . count( $cards_manual ) : '0';
else :
	$cards_count = '0';
endif;

// Classes
$block_image   = $hide_image ? 'hide-image ' : '';
$block_description   = $hide_description ? 'hide-description ' : '';

// Card index
$card_index = 0;

// Block fields
$block_classes_arr = array( $class_name, $block_classes, $cards_count, $block_description );

// JSX Innerblocks - https://www.billerickson.net/innerblocks-with-acf-blocks/
$allowed_blocks = array( 'core/heading', 'core/paragraph' );
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
<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>

		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $block_template ) ); ?>" />

		<div class="dgwltd-featured-boxes__inner dgwltd-cards">
	
		<?php if ( $cards_type === 'relationship' ) : ?>
			<?php
			if ( $cards ) :
				?>
				<?php foreach ( $cards as $card ) : ?>
					<?php // print_r($card) ?>
					<?php $card_index++; ?>
					<?php require DGWLTD_SITE_PLUGIN_DIR . 'src/blocks/build/featured-boxes/card-id.php' ; ?>
		<?php endforeach; ?>
				<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
			<?php endif; ?>

		<?php elseif ( $cards_type === 'manual' ) : ?>
			<?php
			if ( ! empty( $cards_manual ) ) :
				// check if the repeater field has rows of data
				if ( have_rows( 'cards_manual' ) ) :
					// loop through the rows of data
					while ( have_rows( 'cards_manual' ) ) :
						the_row();
						?>
						<?php $card_index++; ?>
						<?php require DGWLTD_SITE_PLUGIN_DIR . 'src/blocks/build/featured-boxes/card-manual.php' ; ?>
						<?php
					endwhile;
			endif;
				?>
			<?php endif; ?>

		<?php endif; // end cards type check ?>
		</div>

</div>
