<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render and https://github.com/bacoords/example-dynamic-block/
 * $attributes (array): The block attributes.
 * $content (string): The block default content.
 * $block (WP_Block): The block instance.
*/


$heading = $attributes['heading'] ?? '';
$content_type = $attributes['contentType'] ?? 'post';

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
    <div class="stack">
    <?php if ( $heading ) : ?>
        <h2><?php echo esc_html( $heading ); ?></h2>
    <?php endif; ?>
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
        <p><?php esc_html_e( 'No posts selected', 'dgwltd-plugin' ); ?></p>
    <?php endif; ?>
    </div>
</div>