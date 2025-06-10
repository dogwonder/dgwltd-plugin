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

        // 1. DGW.ltd Accordion
        wp_register_feature(
            array(
            'id' => 'dgwltd-plugin/accordion', 
            'name' => 'Accordion',
            'description' => 'Expandable accordion component based on GOV.UK accordion pattern.',
            'type' => 'tool',
            'categories' => ['dgwltd', 'block', 'accordion', 'content'],
            'input_schema' => [
                'headingLevel' => ['type' => 'string', 'description' => 'Sets heading level for accordion items (h2-h6)'],
                'customClass' => ['type' => 'string', 'description' => 'Add custom CSS class to the accordion container']
            ],
            'output_schema' => [
                'name' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'usage' => ['type' => 'string'],
                'attributes' => ['type' => 'object']
            ],
            'callback' => '',
        ));

        // 2. DGW.ltd Banner
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/banner',
                'name' => 'Banner',
                'description' => 'Text and background image component similar to hero but less prominent.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block', 'banner', 'content'],
                'output_schema' => [
                    'name' => ['type' => 'string'],
                    'description' => ['type' => 'string'],
                    'usage' => ['type' => 'string'],
                    'block_content' => ['type' => 'string'],
                    'markup' => ['type' => 'string']
                ]
        ));


        // 3. DGW.ltd Breadcrumbs
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/breadcrumbs',
                'name' => 'DGW.ltd Breadcrumbs',
                'description' => 'Navigation breadcrumbs based on GOV.UK breadcrumbs pattern.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block', 'navigation', 'breadcrumbs'],
                'output_schema' => [
                    'name' => ['type' => 'string'],
                    'description' => ['type' => 'string'],
                    'usage' => ['type' => 'string'],
                    'attributes' => ['type' => 'object']
                ],
                'callback' => '',
        ));

        // 4. DGW.ltd Cards
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/cards',
                'name' => 'DGW.ltd Cards',
                'description' => 'Grid of featured cards with title, excerpt and featured image.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block', 'cards', 'content'],
                'callback' => '',
            ));

        
            // 5. DGW.ltd Embed
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/embed',
                'name' => 'DGW.ltd Embed',
                'description' => 'Lightweight embed component for YouTube and Vimeo videos.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block', 'embed', 'video'],
                'callback' => '',
        ));

        // 6. DGW.ltd Promo card
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/promo-card',
                'name' => 'DGW.ltd Promo Card',
                'description' => 'Offset image and content block for promotional content.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block', 'promo', 'content'],
                'callback' => '',
            ));

        // 7. DGW.ltd Hero Section
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/hero-section',
                'name' => 'DGW.ltd Hero Section',
                'description' => 'Hero component with large image/video as background and focal point selector.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block', 'hero', 'content'],
                'callback' => '',
            ));

        // Block Variations
        
        // 8. DGW.ltd Cover
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/cover-variation',
                'name' => 'DGW.ltd Cover',
                'description' => 'Extended cover block with H1 and paragraph text.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block-variation', 'cover', 'content'],
                'callback' => '',
            ));
        
        // 9. DGW.ltd Details Accordion
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/details-accordion-variation',
                'name' => 'DGW.ltd Details Accordion',
                'description' => 'Paragraph block followed by 4 details blocks.',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block-variation', 'accordion', 'details'],
                'callback' => '',
            ));

        // 10. DGW.ltd Code
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/code-variation',
                'name' => 'DGW.ltd Code Block',
                'description' => 'Code block with block styles for syntax highlighting via Prism.css',
                'type' => 'tool',
                'categories' => ['dgwltd', 'block-variation', 'code', 'development'],
                'callback' => '',
            ));

        // Tool to get all block information
        wp_register_feature(
            array(
                'id' => 'dgwltd-plugin/blocks-info',
                'name' => 'DGW.ltd Blocks Information',
                'description' => 'Get information about all available DGW.ltd blocks and variations',
                'type' => 'tool',
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
        ));
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