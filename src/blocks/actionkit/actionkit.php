<?php
/**
 * Actionkit Block Template.
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
// Create class attribute allowing for custom "className"
$class_name = 'dgwltd-block dgwltd-ak';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

// Block attributes (set in native block editor)
$block_attrs  = dgwltd_blocks_Public::dgwltd_get_block_attrs( $block ) ? : '';
$block_classes = $block_attrs['class'] ? $block_attrs['class'] : '';
$block_styles = $block_attrs['style'] ? $block_attrs['style'] : '';

// Block Fields
$image = get_field( 'image' ) ? : '';
$akID = get_field( 'actionkit_page_name' ) ? : '';
$fetchJson = get_field( 'fetch_json' ) ? : '';

//Actionkit vars
if($fetchJson) {
	
	$actionkit_username = 'api_one.org';
	$actionkit_password = $_ENV["AKPASS"];
	$id = get_field( 'actionkit_page_id' ) ? : '';
	$request = "https://{$actionkit_username}:{$actionkit_password}@one.actionkit.com/rest/v1/page/{$id}/";

	if( is_wp_error( $request ) ) {
		return false; // Bail early
	}
	$result_string = wp_remote_get($request);
	$data = wp_remote_retrieve_body( $result_string );
	$json = json_decode( $data, true );

	if( ! empty( $json ) ) {
		$actionkit_page_title = $json['title'];
		$actionkit_page_slug = $json['name'];
		$actionkit_page_image = $json['fields']['main_petition_page_image'];
		$actionkit_page_summary = $json['fields']['petition_summary'];
		$actionkit_page_redirect = $json['followup']['url'];
	}

} else {
	
	$actionkit_page_redirect = get_field( 'actionkit_redirect_url' ) ? : '';	

}

// Classes
$block_image   = $image ? 'has-image ' : '';

// Class list
$block_classes_arr  = array( $class_name, $block_classes, $block_image);

// JSX Innerblocks - https://www.billerickson.net/innerblocks-with-acf-blocks/
$allowed_blocks = array( 'core/heading', 'core/paragraph' );
$block_template = array(
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => 'Add title...',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => 'Add summary...',
		),
	),
);
?>
 <div id="<?php echo $block_id; ?>" class="<?php echo esc_attr( implode( ' ', $block_classes_arr ) ); ?>"<?php echo ($block_styles ? ' style="' . $block_styles . '"' : ''); ?>>
	<div class="dgwltd-ak__inner">
		<div class="dgwltd-ak__content">
	
			<?php if( $fetchJson  ) { ?>

					<?php if( ! empty ($actionkit_page_image) ) : ?>
					<figure class="oneorg-ak__image">
						<img src="<?php echo $actionkit_page_image; ?>" alt="<?php echo ($actionkit_page_title ? $actionkit_page_title : ''); ?>" loading="lazy" />
					</figure>
					<?php endif; ?>

					<?php echo ($actionkit_page_title ? '<h2 class="oneorg-ak__meta-title">' . $actionkit_page_title . '</h2>' : ''); ?>
					<?php echo ($actionkit_page_summary ? '<div class="oneorg-ak__meta-summary">' . $actionkit_page_summary . '</div>' : ''); 
					?>
				
			<?php } else { ?>

				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $block_template ) ); ?>" />

				<?php if ( ! empty( $image ) ) : ?>
				<?php
				$image_tiny        = $image['sizes']['dgwltd-tiny'];
				$image_small       = $image['sizes']['dgwltd-small'];
				$image_medium      = $image['sizes']['dgwltd-medium'];
				$image_alt         = esc_attr( $image['alt'] );
				$image_width       = esc_attr( $image['width'] );
				$image_height      = esc_attr( $image['height'] );
				$image_small_width  = esc_attr( $image['sizes']['dgwltd-small-width'] );
				$image_small_height = esc_attr( $image['sizes']['dgwltd-small-height'] );
				// For Low quality image placeholders (LQIP)
				$type   = pathinfo( $image_tiny, PATHINFO_EXTENSION );
				$data   = file_get_contents( $image_tiny );
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );
				?>
				<figure class="dgwltd-cta__image">
					<picture class="frame">
						<source media="(min-width: 769px)" srcset="<?php echo ( $image_medium ? $image_medium : $image_small ); ?>">
						<img src="<?php echo $image_small; ?>" width="<?php echo $image_small_width; ?>" height="<?php echo $image_small_height; ?>" alt="<?php echo ( $image_alt ? $image_alt : '' ); ?>" loading="lazy" style="background-image: url(<?php echo $base64; ?>)" />
					</picture>
				</figure>
				<?php endif; ?>    

			<?php } ?>

			<?php require_once plugin_dir_path( __FILE__ ) . 'form.php'; ?>

		</div>
	</div>
 </div>