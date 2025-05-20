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
     * Load the WP Feature API
     */
    public function dgwltd_wp_feature_api_init() {

        require_once DGWLTD_PLUGIN_PLUGIN_DIR . 'vendor/automattic/wp-feature-api/wp-feature-api.php';

    }

    /**
     * Register features with the WP Feature API
     */
    public function dgwltd_register_features() {
        wp_register_feature('dgwltd-plugin/example-feature', array(
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