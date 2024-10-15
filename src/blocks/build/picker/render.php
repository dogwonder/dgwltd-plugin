<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * $attributes (array): The block attributes.
 * $content (string): The block default content.
 * $block (WP_Block): The block instance.
*/

$posts = $attributes['selectedPosts'];
$post_ids = wp_list_pluck( $posts, 'id' );

$query_args = [
    'post_type' => 'any',
    'post__in' => $post_ids,
    'orderby' => 'post__in', // Preserve the order of IDs
];

$query = new WP_Query( $query_args );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php if ( $query->have_posts() ) : ?>
        <ul>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?> (<?php echo get_post_type(); ?>)
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p><?php esc_html_e( 'No posts selected', 'dgwltd-site' ); ?></p>
    <?php endif; ?>
</div>