<?php
$image_tiny        = $image['sizes']['dgwltd-tiny'];
$image_small = $image['sizes']['dgwltd-medium'];
$image_small_width  = esc_attr( $image['sizes']['dgwltd-medium-width'] );
$image_small_height = esc_attr( $image['sizes']['dgwltd-medium-height'] );
$image_large       = $image['sizes']['dgwltd-large'];
$image_alt         = esc_attr( $image['alt'] );
$image_width       = esc_attr( $image['width'] );
$image_height      = esc_attr( $image['height'] );
// For Low quality image placeholders (LQIP)
$base64Image  = DGWLTD_PLUGIN_Public::dgwltd_image_to_base64_data_uri( $image_tiny );
?>
<div class="dgwltd-promo-card__image">
    <figure>
        <picture>
            <source media="(min-width: 900px)" srcset="<?php echo $image_large; ?>">
            <img 
            src="<?php echo $image_small; ?>" 
            width="<?php echo $image_small_width; ?>" 
            height="<?php echo $image_small_height; ?>" 
            alt="<?php echo $image_alt ?>" 
            loading="lazy" 
            style="background-image: url(<?php echo $base64Image; ?>)" 
            />
        </picture>
    </figure>
</div>
   