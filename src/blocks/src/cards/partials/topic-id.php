<div class="wccf-terms">
    <?php 
    $topics = get_the_terms($card, 'topic') ?? array();
    $topic = $topics ? $topics[0] : null;
    if ( $topic ) {
    echo '<a href="' . esc_url( get_term_link( $topic->term_id ) ) . '" class="wccf-term">' . esc_html( $topic->name ) . '</a>';
    } ?>
</div>