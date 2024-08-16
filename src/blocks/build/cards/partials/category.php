<div class="wccf-terms">
    <?php 
    //Get all the post categories as an array
    $categories = get_the_terms($card, 'category') ?? array();
    $category = $categories ? $categories[0] : null;
    if ( $category ) {
        echo '<a href="' . esc_url( get_term_link( $category->term_id ) ) . '" class="wccf-term">' . esc_html( $category->name ) . '</a>';
    } ?>
</div>