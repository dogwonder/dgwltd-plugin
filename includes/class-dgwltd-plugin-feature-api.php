<?php
/**
 * Register features with the Feature API
 */
class DGWLTD_FEATURE_API {

    /**
     * Load the WP Feature API
     */
    public function __construct() {
        // Hook in late enough that the Feature API has been bootstrapped.
        add_action('plugins_loaded', array($this, 'dgwltd_wp_feature_api_init'), 15);
    }

    /**
     * Initialize the Feature API
     */
    public function dgwltd_wp_feature_api_init() {
        // Check if the file exists
        $feature_api_path = DGWLTD_PLUGIN_PLUGIN_DIR . 'vendor/automattic/wp-feature-api/wp-feature-api.php';
        
        if (file_exists($feature_api_path)) {
            // Include the main plugin file - it automatically registers itself with the version manager
            require_once $feature_api_path;
            
            // Register features once we know API is initialized
            add_action('wp_feature_api_init', array($this, 'dgwltd_register_features'), 15);
        } else {
            // Log error if file is not found
            error_log('WP Feature API file not found at path: ' . $feature_api_path);
        }
    }

    public function dgwltd_register_features() {

        //   AI/LLM Prompts You Can Now Use
        //     - "Insert a banner titled 'Welcome' with blue overlay at the beginning of post 123"
        //     - "Add a banner with this image URL as background to the current post"
        //     - "Analyze post 456 and tell me the best places to add visual breaks"
        //     - "Create a monochrome banner after the second heading in this post"

        // Check if Feature API is available
        if ( !function_exists( 'wp_register_feature' ) ) {
            error_log( 'Feature API not available when attempting to register block features' );
            return;
        }

        try {
            // DGW.ltd Banner Block Insertion Feature
            wp_register_feature( 
                array(                            
                    'id' => 'dgwltd-plugin/insert-banner',           
                    'name' => 'Insert DGW.ltd Banner Block',                
                    'description' => __('Insert a banner block with content, background image, and styling options into a post or page', 'dgwltd-plugin'),
                    'type' => 'tool',
                    'categories' => array( 'dgwltd', 'editor', 'blocks', 'content-creation' ),
                    'input_schema' => array(
                        'type' => 'object',
                        'properties' => array(
                            'post_id' => array(
                                'type' => 'integer',
                                'description' => 'ID of the post or page to insert the banner into. If not provided, will create a new post.',
                            ),
                            'post_type' => array(
                                'type' => 'string',
                                'description' => 'Type of content to create if post_id is not provided',
                                'enum' => array('post', 'page'),
                                'default' => 'post'
                            ),
                            'position' => array(
                                'type' => 'string',
                                'description' => 'Where to insert the block: "beginning", "end", or "after_block_index"',
                                'enum' => array('beginning', 'end', 'after_block_index'),
                                'default' => 'end'
                            ),
                            'block_index' => array(
                                'type' => 'integer',
                                'description' => 'Block index to insert after (only used with position "after_block_index")',
                            ),
                            'lede' => array(
                                'type' => 'string',
                                'description' => 'Lede text (H3 heading) for the banner',
                            ),
                            'title' => array(
                                'type' => 'string',
                                'description' => 'Main title text (H2 heading) for the banner',
                            ),
                            'content' => array(
                                'type' => 'string',
                                'description' => 'Paragraph content for the banner',
                            ),
                            'background_image_url' => array(
                                'type' => 'string',
                                'description' => 'URL of background image for the banner',
                                'format' => 'uri'
                            ),
                            'background_image_mobile_url' => array(
                                'type' => 'string',
                                'description' => 'URL of mobile background image for the banner',
                                'format' => 'uri'
                            ),
                            'overlay_color' => array(
                                'type' => 'string',
                                'description' => 'Hex color for image overlay (e.g., #000000)',
                                'pattern' => '^#[0-9A-Fa-f]{6}$'
                            ),
                            'overlay_opacity' => array(
                                'type' => 'integer',
                                'description' => 'Overlay opacity percentage (0-100)',
                                'minimum' => 0,
                                'maximum' => 100,
                                'default' => 70
                            ),
                            'block_style' => array(
                                'type' => 'string',
                                'description' => 'Block style variant',
                                'enum' => array('default', 'monochrome'),
                                'default' => 'default'
                            ),
                            'alignment' => array(
                                'type' => 'string',
                                'description' => 'Block alignment',
                                'enum' => array('none', 'wide', 'full'),
                                'default' => 'full'
                            )
                        ),
                        'required' => array( 'title' ),
                    ),
                    
                    'output_schema' => array(
                        'type' => 'object',
                        'properties' => array(
                            'success' => array( 'type' => 'boolean' ),
                            'post_id' => array( 'type' => 'integer' ),
                            'block_type' => array( 'type' => 'string' ),
                            'block_position' => array( 'type' => 'integer' ),
                            'message' => array( 'type' => 'string' ),
                            'edit_url' => array( 'type' => 'string' ),
                            'view_url' => array( 'type' => 'string' ),
                        ),
                        'required' => array( 'success', 'message' ),
                    ),
                    'callback' => array( $this, 'insert_banner_block' ),
                    'permissions_callback' => array( $this, 'check_banner_permissions' ),
                )
            );

            // DGW.ltd Content Analysis Feature
            wp_register_feature(
                array(
                    'id' => 'dgwltd-plugin/analyze-content',
                    'name' => 'Analyze Post Content',
                    'description' => __('Analyze the content structure of a post or page and suggest where to insert blocks', 'dgwltd-plugin'),
                    'type' => 'resource',
                    'categories' => array( 'dgwltd', 'content-analysis', 'editor' ),
                    'input_schema' => array(
                        'type' => 'object',
                        'properties' => array(
                            'post_id' => array(
                                'type' => 'integer',
                                'description' => 'ID of the post or page to analyze',
                            ),
                        ),
                        'required' => array( 'post_id' ),
                    ),
                    'output_schema' => array(
                        'type' => 'object',
                        'properties' => array(
                            'post_id' => array( 'type' => 'integer' ),
                            'title' => array( 'type' => 'string' ),
                            'block_count' => array( 'type' => 'integer' ),
                            'blocks' => array(
                                'type' => 'array',
                                'items' => array(
                                    'type' => 'object',
                                    'properties' => array(
                                        'index' => array( 'type' => 'integer' ),
                                        'type' => array( 'type' => 'string' ),
                                        'content_preview' => array( 'type' => 'string' ),
                                    )
                                )
                            ),
                            'suggestions' => array(
                                'type' => 'array',
                                'items' => array(
                                    'type' => 'object',
                                    'properties' => array(
                                        'position' => array( 'type' => 'string' ),
                                        'reason' => array( 'type' => 'string' ),
                                        'block_index' => array( 'type' => 'integer' ),
                                    )
                                )
                            ),
                        ),
                    ),
                    'callback' => array( $this, 'analyze_post_content' ),
                    'permissions_callback' => array( $this, 'check_content_permissions' ),
                )
            );

            error_log( 'DGW.ltd features registered successfully' );

        } catch ( Exception $e ) {
            error_log( 'Error registering features: ' . $e->getMessage() );
        }
    }

    /**
     * Insert a banner block into a post
     */
    public function insert_banner_block( $params ) {
        try {
            // Get or create post
            $post_id = isset( $params['post_id'] ) ? intval( $params['post_id'] ) : null;
            
            if ( ! $post_id ) {
                // Create new post or page
                $post_type = $params['post_type'] ?? 'post';
                $post_title = $params['title'] ?? ( $post_type === 'page' ? 'New Page with Banner' : 'New Post with Banner' );
                
                $post_data = array(
                    'post_title' => $post_title,
                    'post_status' => 'draft',
                    'post_type' => $post_type,
                );
                $post_id = wp_insert_post( $post_data );
                
                if ( is_wp_error( $post_id ) ) {
                    return array(
                        'success' => false,
                        'message' => 'Failed to create new ' . $post_type . ': ' . $post_id->get_error_message(),
                    );
                }
            }

            // Get current post content
            $post = get_post( $post_id );
            if ( ! $post ) {
                return array(
                    'success' => false,
                    'message' => 'Page/Post not found with ID: ' . $post_id,
                );
            }

            // Parse existing blocks
            $blocks = parse_blocks( $post->post_content );

            // Handle image uploads
            $background_image_id = null;
            $background_image_mobile_id = null;

            if ( ! empty( $params['background_image_url'] ) ) {
                $background_image_id = $this->upload_image_from_url( $params['background_image_url'] );
            }

            if ( ! empty( $params['background_image_mobile_url'] ) ) {
                $background_image_mobile_id = $this->upload_image_from_url( $params['background_image_mobile_url'] );
            }

            // Create banner block structure
            $banner_inner_blocks = array();
            
            // Add lede if provided
            if ( ! empty( $params['lede'] ) ) {
                $banner_inner_blocks[] = array(
                    'blockName' => 'core/heading',
                    'attrs' => array( 'level' => 3 ),
                    'innerBlocks' => array(),
                    'innerHTML' => '<h3 class="wp-block-heading">' . esc_html( $params['lede'] ) . '</h3>',
                    'innerContent' => array( '<h3 class="wp-block-heading">' . esc_html( $params['lede'] ) . '</h3>' ),
                );
            }

            // Add title
            if ( ! empty( $params['title'] ) ) {
                $banner_inner_blocks[] = array(
                    'blockName' => 'core/heading',
                    'attrs' => array( 'level' => 2 ),
                    'innerBlocks' => array(),
                    'innerHTML' => '<h2 class="wp-block-heading">' . esc_html( $params['title'] ) . '</h2>',
                    'innerContent' => array( '<h2 class="wp-block-heading">' . esc_html( $params['title'] ) . '</h2>' ),
                );
            }

            // Add content
            if ( ! empty( $params['content'] ) ) {
                $banner_inner_blocks[] = array(
                    'blockName' => 'core/paragraph',
                    'attrs' => array(),
                    'innerBlocks' => array(),
                    'innerHTML' => '<p>' . esc_html( $params['content'] ) . '</p>',
                    'innerContent' => array( '<p>' . esc_html( $params['content'] ) . '</p>' ),
                );
            }

            // Create banner block
            $banner_block = array(
                'blockName' => 'acf/dgwltd-banner',
                'attrs' => array(
                    'id' => uniqid( 'block_' ),
                    'name' => 'acf/dgwltd-banner',
                    'data' => array(
                        'background_image' => $background_image_id,
                        'background_image_mobile' => $background_image_mobile_id,
                        'overlay' => $params['overlay_color'] ?? '',
                        'overlay_opacity' => $params['overlay_opacity'] ?? 70,
                    ),
                    'align' => $params['alignment'] ?? 'full',
                    'className' => $params['block_style'] !== 'default' ? 'is-style-' . $params['block_style'] : '',
                ),
                'innerBlocks' => $banner_inner_blocks,
                'innerHTML' => '',
                'innerContent' => array( null ),
            );

            // Insert block at specified position
            $position = $params['position'] ?? 'end';
            $block_index = null;

            switch ( $position ) {
                case 'beginning':
                    array_unshift( $blocks, $banner_block );
                    $block_index = 0;
                    break;
                    
                case 'after_block_index':
                    $insert_after = intval( $params['block_index'] ?? 0 );
                    if ( $insert_after >= 0 && $insert_after < count( $blocks ) ) {
                        array_splice( $blocks, $insert_after + 1, 0, array( $banner_block ) );
                        $block_index = $insert_after + 1;
                    } else {
                        $blocks[] = $banner_block;
                        $block_index = count( $blocks ) - 1;
                    }
                    break;
                    
                default: // 'end'
                    $blocks[] = $banner_block;
                    $block_index = count( $blocks ) - 1;
                    break;
            }

            // Serialize blocks back to content
            $new_content = serialize_blocks( $blocks );

            // Update post
            $update_result = wp_update_post( array(
                'ID' => $post_id,
                'post_content' => $new_content,
            ) );

            if ( is_wp_error( $update_result ) ) {
                return array(
                    'success' => false,
                    'message' => 'Failed to update post: ' . $update_result->get_error_message(),
                );
            }

            $post_type_label = $post->post_type === 'page' ? 'page' : 'post';
            
            return array(
                'success' => true,
                'post_id' => $post_id,
                'post_type' => $post->post_type,
                'block_type' => 'acf/dgwltd-banner',
                'block_position' => $block_index,
                'message' => 'Banner block inserted successfully into ' . $post_type_label . ' at position ' . $block_index,
                'edit_url' => admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
                'view_url' => get_permalink( $post_id ),
            );

        } catch ( Exception $e ) {
            return array(
                'success' => false,
                'message' => 'Error inserting banner block: ' . $e->getMessage(),
            );
        }
    }

    /**
     * Analyze post content structure
     */
    public function analyze_post_content( $params ) {
        $post_id = intval( $params['post_id'] );
        $post = get_post( $post_id );

        if ( ! $post ) {
            return new WP_Error( 'post_not_found', 'Post not found' );
        }

        $blocks = parse_blocks( $post->post_content );
        $analysis = array(
            'post_id' => $post_id,
            'post_type' => $post->post_type,
            'title' => $post->post_title,
            'block_count' => count( $blocks ),
            'blocks' => array(),
            'suggestions' => array(),
        );

        // Analyze each block
        foreach ( $blocks as $index => $block ) {
            $content_preview = '';
            if ( ! empty( $block['innerHTML'] ) ) {
                $content_preview = wp_trim_words( wp_strip_all_tags( $block['innerHTML'] ), 10 );
            }

            $analysis['blocks'][] = array(
                'index' => $index,
                'type' => $block['blockName'] ?? 'unknown',
                'content_preview' => $content_preview,
            );
        }

        // Generate suggestions
        $content_type = $post->post_type === 'page' ? 'page' : 'post';
        
        if ( empty( $blocks ) ) {
            $analysis['suggestions'][] = array(
                'position' => 'beginning',
                'reason' => ucfirst( $content_type ) . ' is empty - banner would work well as opening content',
                'block_index' => null,
            );
        } else {
            // Suggest after headings
            foreach ( $blocks as $index => $block ) {
                if ( $block['blockName'] === 'core/heading' ) {
                    $analysis['suggestions'][] = array(
                        'position' => 'after_block_index',
                        'reason' => 'After heading block - good for visual break',
                        'block_index' => $index,
                    );
                }
            }

            // Always suggest at the end
            $analysis['suggestions'][] = array(
                'position' => 'end',
                'reason' => 'At the end - good for call-to-action banner',
                'block_index' => null,
            );
        }

        return $analysis;
    }

    /**
     * Upload image from URL
     */
    private function upload_image_from_url( $url ) {
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $attachment_id = media_sideload_image( $url, 0, null, 'id' );

        if ( is_wp_error( $attachment_id ) ) {
            error_log( 'Failed to upload image: ' . $attachment_id->get_error_message() );
            return null;
        }

        return $attachment_id;
    }

    /**
     * Check permissions for banner insertion
     */
    public function check_banner_permissions( $params ) {
        // Check if user can edit posts
        if ( ! current_user_can( 'edit_posts' ) ) {
            return false;
        }

        // If post_id is provided, check if user can edit that specific post
        if ( isset( $params['post_id'] ) ) {
            return current_user_can( 'edit_post', $params['post_id'] );
        }

        // For new posts/pages, check appropriate capability
        $post_type = $params['post_type'] ?? 'post';
        
        if ( $post_type === 'page' ) {
            return current_user_can( 'publish_pages' );
        } else {
            return current_user_can( 'publish_posts' );
        }
    }

    /**
     * Check permissions for content analysis
     */
    public function check_content_permissions( $params ) {
        if ( isset( $params['post_id'] ) ) {
            return current_user_can( 'edit_post', $params['post_id'] );
        }
        return current_user_can( 'edit_posts' );
    }

}
