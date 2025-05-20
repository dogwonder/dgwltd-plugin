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

    /**
     * Initialize the class and set its hooks
     */
    public function __construct() {
        // Hook into plugins_loaded to initialize the Feature API
        add_action('plugins_loaded', array($this, 'dgwltd_wp_feature_api_init'));
    }

    /**
     * Load the WP Feature API
     */
    public function dgwltd_wp_feature_api_init() {
        // Include the main plugin file - it automatically registers itself
        require_once DGWLTD_PLUGIN_PLUGIN_DIR . 'vendor/automattic/wp-feature-api/wp-feature-api.php';
        
        // Register features once we know API is initialized
        add_action('wp_feature_api_init', array($this, 'dgwltd_register_features'));
    }

    /**
     * Register features with the WP Feature API
     */
    public function dgwltd_register_features() {
        // Create a new feature instance
        $feature = new WP_Feature('dgwltd-plugin/example-feature', array(
            'name' => 'Example Feature',
            'description' => 'An example feature from my plugin',
            'callback' => array($this, 'dgwltd_plugin_example_feature_callback'),
            'type' => 'tool',
            'input_schema' => array(
                'type' => 'object',
                'properties' => array(
                    'example_param' => array(
                        'type' => 'string',
                        'description' => 'An example parameter',
                    ),
                ),
            ),
        ));
    }
    
    /**
     * Callback for the example feature
     *
     * @param array $args Arguments from the feature request
     * @return array Response to be returned
     */
    public function dgwltd_plugin_example_feature_callback($args) {
        // Process the feature request
        $example_param = isset($args['example_param']) ? $args['example_param'] : '';
        
        // Return a response
        return array(
            'status' => 'success',
            'data' => array(
                'message' => 'Example feature executed successfully with param: ' . $example_param,
            ),
        );
    }
}