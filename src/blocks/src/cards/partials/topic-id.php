<?php
//Get the prinmary category of the post via Custom Field
if ( get_field( 'primary_topic', $card ) ) {
    $primaryTopic = get_field( 'primary_topic', $card );
    $primaryTopic = get_term( $primaryTopic );
} else {
    $primaryTopic = false;
} 
if ( $primaryTopic ) { ?>
    <div class="wccf-terms">
        <?php 
        $primaryTopic = get_term( $primaryTopic );
        echo '<a href="' . esc_url( get_term_link( $primaryTopic->term_id ) ) . '" class="wccf-term">' . esc_html( $primaryTopic->name ) . '</a>'; ?>
    </div>
<?php } else {  ?>
    <div class="wccf-terms">
        <?php 
        $topics = get_the_terms($card, 'topic') ?? array();
        $topic = $topics ? $topics[0] : null;
        if ( $topic ) {
        echo '<a href="' . esc_url( get_term_link( $topic->term_id ) ) . '" class="wccf-term">' . esc_html( $topic->name ) . '</a>';
        } ?>
    </div>
<?php }  ?>