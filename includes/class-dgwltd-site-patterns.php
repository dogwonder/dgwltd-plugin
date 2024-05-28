<?php

/**
 * Define the blocks functionality.
 *
 * Loads and defines the block patterns for this plugin
 *
 * @since      1.0.0
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/includes
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class Dgwltd_Site_Patterns {

	/**
	* Register Event Block Pattern Category
	*/
	public function dgwltd_register_block_categories() {
		if ( ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
			return;
		}

		register_block_pattern_category(
			'blocks',
			array( 'label' => _x( 'DGW.ltd', 'Block pattern category', 'dgwltd-site' ) )
		);
	}

		/**
		 * Register Block Patterns
		 */
	public function dgwltd_register_block_patterns() {

		if ( ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
			return;
		}

		$patterns['dgwltd-site/layout-page'] = array(
			'title'       => __( 'Layout page', 'dgwltd-site' ),
			'description' => __( 'A generic page layout', 'dgwltd-site' ),
			'content'     => '
				<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
				<div class="wp-block-group alignwide">
				<!-- wp:acf/dgwltd-hero {"name":"acf/dgwltd-hero","align":"full","mode":"preview","alignText":"left","backgroundColor":"primary","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}}} -->
				<!-- wp:heading {"level":1} -->
				<h1>Add Hero title...</h1>
				<!-- /wp:heading -->
				<!-- wp:paragraph -->
				<p>Add Hero content...</p>
				<!-- /wp:paragraph -->
				<!-- /wp:acf/dgwltd-hero -->

				<!-- wp:acf/dgwltd-call-to-action {"name":"acf/dgwltd-call-to-action","mode":"preview"} -->
				<!-- wp:heading {"placeholder":""} -->
				<h2>Add Call-to-Action title...</h2>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"placeholder":""} -->
				<p>Add Call-to-Action content...</p>
				<!-- /wp:paragraph -->
				<!-- /wp:acf/dgwltd-call-to-action -->

				</div>
				<!-- /wp:group -->
			',
			'categories'  => array( 'blocks' ),
		);

		$patterns['dgwltd-site/lockable-content'] = array(
			'title'       => __( 'Lockable content', 'dgwltd-site' ),
			'description' => __( 'Content-only block editing', 'dgwltd-site' ),
			'content'     => '
				<!-- wp:group {"templateLock": "contentOnly", "align":"wide","layout":{"type":"constrained","wideSize":"2000px"}} -->
				<div class="wp-block-group alignwide"><!-- wp:columns -->
				<div class="wp-block-columns"><!-- wp:column -->
				<div class="wp-block-column"><!-- wp:image {"id":231,"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="https://s.w.org/images/core/5.8/outside-02.jpg" alt="" class="wp-image-231"/></figure>
				<!-- /wp:image -->
				
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group"><!-- wp:heading {"fontSize":"large"} -->
				<h2 class="has-large-font-size"><strong>Project 2</strong></h2>
				<!-- /wp:heading -->
				
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size">A tiny description of this project.</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:group --></div>
				<!-- /wp:column -->
				
				<!-- wp:column -->
				<div class="wp-block-column"><!-- wp:spacer -->
				<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
				
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group"><!-- wp:heading {"fontSize":"large"} -->
				<h2 class="has-large-font-size"><strong>Project 1</strong></h2>
				<!-- /wp:heading -->
				
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size">A tiny description of this project.</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:group -->
				
				<!-- wp:image {"id":232,"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="https://s.w.org/images/core/5.8/outside-02.jpg" alt="" class="wp-image-232"/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns --></div>
				<!-- /wp:group -->
			',
			'categories'  => array( 'blocks' ),
		);

		$patterns['dgwltd-site/supporters'] = array(
			'title'       => __( 'Supporters', 'dgwltd-site' ),
			'description' => __( 'A group of images and links to list supporters.', 'dgwltd-site' ),
			'content'     => '
					<!-- wp:group {"style":{"color":{"background":"var(--wp--preset--color--alt),"text":"var(--wp--preset--color--dark)""}}} -->
						<div class="wp-block-group has-background has-alt-background-color dgwltd-block-group"><div class="wp-block-group__inner-container">
						<!-- wp:heading {"align":"center","level":3} -->
						<h3 class="has-text-align-center">Thanks to our supporters</h3>
						<!-- /wp:heading -->

						<!-- wp:paragraph {"align":"center"} -->
						<p class="has-text-align-center">A really short blurb. Add and remove columns as needed.</p>
						<!-- /wp:paragraph -->

						<!-- wp:columns -->
							<div class="wp-block-columns">
							<!-- wp:column {"width":25} -->
								<div class="wp-block-column" style="flex-basis:25%">
								<!-- wp:group {"backgroundColor":"white"} -->
									<div class="wp-block-group has-white-background-color has-background"><div class="wp-block-group__inner-container">

									<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
									<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
									<!-- /wp:image -->

									<!-- wp:heading {"align":"center","level":4} -->
									<h4 class="has-text-align-center"><a href="#supporter1">Supporter 1</a></h4>
									<!-- /wp:heading -->
									</div></div>
								<!-- /wp:group -->
								</div>
							<!-- /wp:column -->

							<!-- wp:column {"width":25} -->
								<div class="wp-block-column" style="flex-basis:25%">
								<!-- wp:group {"backgroundColor":"white"} -->
									<div class="wp-block-group has-white-background-color has-background"><div class="wp-block-group__inner-container">

									<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
									<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
									<!-- /wp:image -->

									<!-- wp:heading {"align":"center","level":4} -->
									<h4 class="has-text-align-center"><a href="#supporter2">Supporter 2</a></h4>
									<!-- /wp:heading -->
									</div></div>
								<!-- /wp:group -->
								</div>
							<!-- /wp:column -->

							<!-- wp:column {"width":25} -->
								<div class="wp-block-column" style="flex-basis:25%">
								<!-- wp:group {"backgroundColor":"white"} -->
									<div class="wp-block-group has-white-background-color has-background"><div class="wp-block-group__inner-container">

									<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
									<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
									<!-- /wp:image -->

									<!-- wp:heading {"align":"center","level":4} -->
									<h4 class="has-text-align-center"><a href="#supporter2">Supporter 3</a></h4>
									<!-- /wp:heading -->
									</div></div>
								<!-- /wp:group -->
								</div>
							<!-- /wp:column -->

							<!-- wp:column {"width":25} -->
								<div class="wp-block-column" style="flex-basis:25%">
								<!-- wp:group {"backgroundColor":"white"} -->
									<div class="wp-block-group has-white-background-color has-background"><div class="wp-block-group__inner-container">

									<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
									<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
									<!-- /wp:image -->

									<!-- wp:heading {"align":"center","level":4} -->
									<h4 class="has-text-align-center"><a href="#supporter4">Supporter 4</a></h4>
									<!-- /wp:heading -->

									</div></div>
								<!-- /wp:group -->
								</div>
							<!-- /wp:column -->
							</div>
						<!-- /wp:columns -->
						</div></div>
					<!-- /wp:group -->
				',
			'categories'  => array( 'blocks' ),
		);

		$patterns['dgwltd-site/columns-dark'] = array(
			'title'       => __( 'Columns - dark', 'dgwltd-site' ),
			'description' => __( 'Columns for content with a dark background colour', 'dgwltd-site' ),
			'content'     => '
					<!-- wp:group {"style":{"color":{"background":"var(--wp--preset--color--primary)","text":"var(--wp--preset--color--dark)"}}} -->
					<div class="wp-block-group has-text-color has-background has-primary-background-color dgwltd-block-group"><div class="wp-block-group__inner-container">

					<!-- wp:heading {"level":2} -->
					<h2 class="has-white-color has-text-color">DGW.ltd</h2>
					<!-- /wp:heading -->

					<!-- wp:columns -->
					<div class="wp-block-columns">
						<!-- wp:column -->
							<div class="wp-block-column">
								<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
								<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
								<!-- /wp:image -->
                                <!-- wp:heading {"level":3} -->
                                <h3 class="has-white-color has-text-color"><a href="#">Headline here</a></h3>
					            <!-- /wp:heading -->
                                <!-- wp:paragraph -->
                                <p>A really short blurb. Add and remove columns as needed.</p>
                                <!-- /wp:paragraph -->
							</div>
						<!-- /wp:column -->
                        <!-- wp:column -->
							<div class="wp-block-column">
								<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
								<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
								<!-- /wp:image -->
								<!-- wp:heading {"level":3} -->
								<h3 class="has-white-color has-text-color"><a href="#">Headline here</a></h3>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p>A really short blurb. Add and remove columns as needed.</p>
								<!-- /wp:paragraph -->
                            </div>
                        <!-- /wp:column -->
                        <!-- wp:column -->
							<div class="wp-block-column">
								<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
								<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
								<!-- /wp:image -->
								<!-- wp:heading {"level":3} -->
								<h3 class="has-white-color has-text-color"><a href="#">Headline here</a></h3>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p>A really short blurb. Add and remove columns as needed.</p>
								<!-- /wp:paragraph -->
                            </div>
                        <!-- /wp:column -->
						</div>
					<!-- /wp:columns -->
						
					</div></div>
					<!-- /wp:group -->
				',
			'categories'  => array( 'blocks' ),
		);

		$patterns['dgwltd-site/columns-light'] = array(
			'title'       => __( 'Columns - light', 'dgwltd-site' ),
			'description' => __( 'Columns for content with a light background colour', 'dgwltd-site' ),
			'content'     => '
					<!-- wp:group {"style":{"color":{"background":"var(--wp--preset--color--alt)","text":"var(--wp--preset--color--dark)"}}} -->
					<div class="wp-block-group has-text-color has-background has-alt-background-color dgwltd-block-group"><div class="wp-block-group__inner-container">

					<!-- wp:heading {"level":2} -->
					<h2>DGW.ltd</h2>
					<!-- /wp:heading -->

					<!-- wp:columns -->
					<div class="wp-block-columns">
						<!-- wp:column -->
							<div class="wp-block-column">
								<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
								<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
								<!-- /wp:image -->
                                <!-- wp:heading {"level":3} -->
                                <h3><a href="#">Headline here</a></h3>
					            <!-- /wp:heading -->
                                <!-- wp:paragraph -->
                                <p>A really short blurb. Add and remove columns as needed.</p>
                                <!-- /wp:paragraph -->
							</div>
						<!-- /wp:column -->
                        <!-- wp:column -->
							<div class="wp-block-column">
								<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
								<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
								<!-- /wp:image -->
								<!-- wp:heading {"level":3} -->
								<h3><a href="#">Headline here</a></h3>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p>A really short blurb. Add and remove columns as needed.</p>
								<!-- /wp:paragraph -->
                            </div>
                        <!-- /wp:column -->
                        <!-- wp:column -->
							<div class="wp-block-column">
								<!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large","className":"is-style-default"} -->
								<div class="wp-block-image is-style-default"><figure class="aligncenter size-large is-resized"><img src="https:\/\/s.w.org\/images\/core\/5.5\/don-quixote-03.jpg" alt="' . __( 'Pencil drawing of Don Quixote' ) . '" width="64" height="64"/></figure></div>
								<!-- /wp:image -->
								<!-- wp:heading {"level":3} -->
								<h3><a href="#">Headline here</a></h3>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p>A really short blurb. Add and remove columns as needed.</p>
								<!-- /wp:paragraph -->
                            </div>
                        <!-- /wp:column -->
						</div>
					<!-- /wp:columns -->
					</div></div>
					<!-- /wp:group -->
				',
			'categories'  => array( 'blocks' ),
		);

		$patterns = apply_filters( 'dgwltd_block_patterns', $patterns );

		foreach ( $patterns as $pattern => $definition ) {
			register_block_pattern( $pattern, $definition );
			
		}
	}



}
