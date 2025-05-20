<?php 
$v    = DGWLTD_PLUGIN_PUBLIC::dgwltd_parse_video_uri( $embed );
$vid  = $v['id'];
?>
<div class="dgwltd-promo-card__video">
    <?php if ( $v['type'] == 'youtube' ) : ?>
        <lite-youtube videoid="<?php echo $vid; ?>"></lite-youtube>    
    <?php elseif ( $v['type'] == 'vimeo' ) : ?>
        <lite-vimeo videoid="<?php echo $vid; ?>"></lite-vimeo>
    <?php else : ?>
        <?php the_field( 'video' ); ?>
    <?php endif; ?>
</div>