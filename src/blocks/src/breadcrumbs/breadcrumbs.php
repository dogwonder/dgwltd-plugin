<?php
/**
 * Anchor Block Template.
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
$class_name = 'dgwltd-block dgwltd-breadcrumbs';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

global $post;

// Custom ancestor
$custom_ancestor = get_field( 'ancestor' ) ?? false;

$currentpost_id = $post->ID;

// get ancestors of current post
$ancestors = get_post_ancestors( $post->ID );

// Post parent ID (which can be 0 if there is no parent)
$parent = wp_get_post_parent_id( $currentpost_id );

// Show home true / false
$show_home = get_field( 'show_home') ?? false;

// If show home add class
$class_name .= $show_home ? ' has-home' : '';

// Class list
$block_classes_arr  = array( $class_name );
?>
<div id="<?php echo $block_id; ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>">

	<div class="govuk-breadcrumbs">
		<ol class="govuk-breadcrumbs__list">

			<?php 
			if ( $show_home ) : ?>
			<li class="govuk-breadcrumbs__list-item">
				<a class="govuk-breadcrumbs__link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dgwltd' ); ?></a>
			</li>
			<?php endif; ?>

			<?php if ( is_page() && $parent > 0 && !$custom_ancestor ) : ?>
				<?php
				// Get all ancestors and loop through them in reverse order
				$ancestor_pages = array_reverse( $ancestors );
				foreach ( $ancestor_pages as $ancestor ) :
				?>
				<li class="govuk-breadcrumbs__list-item">
					<a class="govuk-breadcrumbs__link" href="<?php echo esc_url( get_permalink( $ancestor ) ); ?>">
						<?php echo esc_html( get_the_title( $ancestor ) ); ?>
					</a>
				</li>
				<?php endforeach; ?>
			<?php wp_reset_query(); // results query ?>
			<?php endif; ?>

			<?php if($custom_ancestor) : ?>
				<li class="govuk-breadcrumbs__list-item">
					<a class="govuk-breadcrumbs__link" href="<?php echo esc_url( get_permalink( $custom_ancestor->ID ) ); ?>">
						<?php echo esc_html( get_the_title( $custom_ancestor->ID ) ); ?>
					</a>
				</li>
			<?php endif; ?>
			
			<?php if ( ! is_front_page() ) : ?>
				<li class="govuk-breadcrumbs__list-item" aria-current="page">
				<?php if ( is_search() ) : ?>
					<?php esc_html_e( 'Search results', 'dgwltd' ); ?>    
				<?php elseif ( is_404() ) : ?>
					<?php esc_html_e( '404, page not found', 'dgwltd' ); ?>
				<?php elseif ( is_category() ) : ?>
					<?php single_cat_title(); ?>
				<?php elseif ( is_tag() ) : ?>
					<?php single_tag_title(); ?>
				<?php elseif ( is_page() || is_single() ) : ?>
					<?php echo esc_html( get_the_title( $post->ID ) ); ?>
				<?php endif; ?>
				</li>
			<?php endif; ?>

		</ol>
	</div>

</div>
