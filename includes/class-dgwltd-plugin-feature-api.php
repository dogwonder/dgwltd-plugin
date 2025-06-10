<?php
/**
 * Define the WordPress Feature API.
 *
 * Loads and defines the Feature API for the plugin
 *
 * @since      1.0.0
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/includes
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class DGWLTD_FEATURE_API {

    //IDEAS: https://www.pootlepress.com/2025/05/5-incredible-things-coming-to-wordpress-through-the-features-api/

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


    /**
     * Register features with the WP Feature API
     */
    public function dgwltd_register_features() {

        // Check if Feature API is available
        if (!function_exists('wp_register_feature')) {
            error_log('Feature API not available when attempting to register block features');
            return;
        }

        // wp_register_feature('dgwltd-plugin/banner', [
        //     'id' => 'dgwltd-plugin/banner',
        //     'name'        => 'DGW.ltd Banner',
        //     'description' => 'Insert a banner block',
        //     'type'        => 'tool',
        //     'categories'  => [ 'dgwltd', 'editor', 'blocks', 'patterns' ],

        //     'input_schema'  => [
        //         'type'       => 'object',
        //         'properties' => [
        //             'lede'    => [ 'type' => 'string' ],
        //             'title'   => [ 'type' => 'string' ],
        //             'content' => [ 'type' => 'string' ],
        //         ],
        //         'required'   => [ 'title' ],
        //     ],

        //     'output_schema' => [
        //         'type'       => 'object',
        //         'properties' => [
        //             'success'        => [ 'type' => 'boolean' ],
        //             'blockType'      => [ 'type' => 'string' ],
        //         ],
        //         'required'   => [ 'success', 'blockType' ],
        //     ],

        //     'callback' => function (array $context, WP_Feature $feature) {
    
        //             if (empty($context['title'])) {
        //                 throw new Exception('Title is required for banner block');
        //             }

        //             try {
        //                 // Create block HTML server-side
        //                 $block_content = '';
                        
        //                 // Add lede if provided
        //                 if (!empty($context['lede'])) {
        //                     $block_content .= '<!-- wp:heading {"level":3} -->';
        //                     $block_content .= '<h3>' . esc_html($context['lede']) . '</h3>';
        //                     $block_content .= '<!-- /wp:heading -->';
        //                 }
                        
        //                 // Add title (required)
        //                 $block_content .= '<!-- wp:heading {"level":2} -->';
        //                 $block_content .= '<h2>' . esc_html($context['title']) . '</h2>';
        //                 $block_content .= '<!-- /wp:heading -->';
                        
        //                 // Add content if provided
        //                 if (!empty($context['content'])) {
        //                     $block_content .= '<!-- wp:paragraph -->';
        //                     $block_content .= '<p>' . wp_kses_post($context['content']) . '</p>';
        //                     $block_content .= '<!-- /wp:paragraph -->';
        //                 }

        //                 return array(
        //                     'success' => true,
        //                     'blockType' => 'content-pattern',
        //                     'blockContent' => $block_content,
        //                     'message' => 'Content pattern created successfully'
        //                 );

        //             } catch (Exception $e) {
        //                 return array(
        //                     'success' => false,
        //                     'error' => $e->getMessage()
        //                 );
        //             }
        //         },
        //     ]
        // );

        
        // You see that we're borrowing the terminology from MCP for the `type` of feature. Tools are generally actionable and have effects, whereas resources are generally passive and are used to provide more context. Think of it as the difference between GET and POST requests.
        
        // Resources are used to provide more context. Because of this, resources are often registered server-side, because they can expose data over the REST API.

        // Tool to get all block information
        wp_register_feature('dgwltd-plugin/blocks-info', [
                'id' => 'dgwltd-plugin/blocks-info',
                'name' => 'DGW.ltd Blocks Information',
                'description' => 'Get information about all available DGW.ltd blocks and variations',
                'type' => 'resource',
                'categories' => ['dgwltd', 'blocks', 'information'],
                'input_schema' => [
                    'type' => ['type' => 'string', 'description' => 'Filter by block type (block or block-variation)'],
                    'category' => ['type' => 'string', 'description' => 'Filter by block category (content, navigation, embed, etc.)']
                ],
                'output_schema' => [
                    'total' => ['type' => 'number'],
                    'blocks' => ['type' => 'object']
                ],
                'callback' => array($this, 'dgwltd_blocks_info_callback'),
        ]
    );
    
    }

    /**
     * Callback for the blocks info tool
     */
    public function dgwltd_blocks_info_callback($input) {
            $blocks = array(
                'accordion' => array(
                    'name' => 'DGW.ltd Accordion',
                    'description' => 'Expandable accordion component based on GOV.UK accordion pattern.',
                    'category' => 'content',
                    'type' => 'block'
                ),
                'banner' => array(
                    'name' => 'DGW.ltd Banner',
                    'description' => 'Text and background image component similar to hero but less prominent.',
                    'category' => 'content',
                    'type' => 'block'
                ),
                'breadcrumbs' => array(
                    'name' => 'DGW.ltd Breadcrumbs',
                    'description' => 'Navigation breadcrumbs based on GOV.UK breadcrumbs pattern.',
                    'category' => 'navigation',
                    'type' => 'block'
                ),
                'cards' => array(
                    'name' => 'DGW.ltd Cards',
                    'description' => 'Grid of featured cards with title, excerpt and featured image.',
                    'category' => 'content',
                    'type' => 'block'
                ),
                'embed' => array(
                    'name' => 'DGW.ltd Embed',
                    'description' => 'Lightweight embed component for YouTube and Vimeo videos.',
                    'category' => 'embed',
                    'type' => 'block'
                ),
                'promo-card' => array(
                    'name' => 'DGW.ltd Promo Card',
                    'description' => 'Offset image and content block for promotional content.',
                    'category' => 'content',
                    'type' => 'block'
                ),
                'hero-section' => array(
                    'name' => 'DGW.ltd Hero Section',
                    'description' => 'Hero component with large image/video as background and focal point selector.',
                    'category' => 'content',
                    'type' => 'block'
                ),
                'cover-variation' => array(
                    'name' => 'DGW.ltd Cover',
                    'description' => 'Extended cover block with H1 and paragraph text.',
                    'category' => 'content',
                    'type' => 'block-variation',
                    'extends' => 'core/cover'
                ),
                'details-accordion-variation' => array(
                    'name' => 'DGW.ltd Details Accordion',
                    'description' => 'Paragraph block followed by 4 details blocks.',
                    'category' => 'content',
                    'type' => 'block-variation',
                    'extends' => 'core/details'
                ),
                'code-variation' => array(
                    'name' => 'DGW.ltd Code',
                    'description' => 'Code block with block styles for syntax highlighting via Prism.css',
                    'category' => 'content',
                    'type' => 'block-variation',
                    'extends' => 'core/code'
                )
            );

            // Filter blocks if requested
            if (isset($input['type']) && !empty($input['type'])) {
                $blocks = array_filter($blocks, function($block) use ($input) {
                    return $block['type'] === $input['type'];
                });
            }

            if (isset($input['category']) && !empty($input['category'])) {
                $blocks = array_filter($blocks, function($block) use ($input) {
                    return $block['category'] === $input['category'];
                });
            }

            return array(
                'total' => count($blocks),
                'blocks' => $blocks
            );
        }

}
