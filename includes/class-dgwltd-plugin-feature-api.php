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

        // Register example feature
        wp_register_feature(
            array(
                'id'          => 'dgwltd-plugin/example-feature',
                'name'        => 'Example Feature',
                'description' => 'An example feature from my plugin',
                'type'        => 'tool', // or WP_Feature::TYPE_TOOL if constant is available
                'categories'  => array('dgwltd', 'example'),
                'callback'    => array($this, 'dgwltd_plugin_example_feature_callback'),
                'input_schema' => array(
                    'type' => 'object',
                    'properties' => array(
                        'example_param' => array(
                            'type' => 'string',
                            'description' => 'An example parameter',
                        ),
                    ),
                ),
            )
        );
        
        // Register a site info feature similar to the example
        wp_register_feature(
            array(
                'id'          => 'dgwltd-plugin/site-info',
                'name'        => 'Site Information',
                'description' => 'Get basic information about the WordPress site.',
                'type'        => 'resource', // or WP_Feature::TYPE_RESOURCE if constant is available
                'categories'  => array('dgwltd', 'site', 'information'),
                'callback'    => array($this, 'dgwltd_plugin_site_info_callback'),
            )
        );
    
    }
    
    /**
     * Callback for the example feature
     *
     * @param array $input Input parameters for the feature.
     * @return array Response to be returned
     */
    public function dgwltd_plugin_example_feature_callback($input) {
        // Process the feature request
        $example_param = isset($input['example_param']) ? $input['example_param'] : '';
        
        // Return a response
        return array(
            'status' => 'success',
            'data' => array(
                'message' => 'Example feature executed successfully with param: ' . $example_param,
                'timestamp' => current_time('mysql'),
            ),
        );
    }
    
    /**
     * Callback for the site info feature
     *
     * @param array $input Input parameters for the feature.
     * @return array Site information.
     */
    public function dgwltd_plugin_site_info_callback($input) {
        return array(
            'name'        => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url'         => home_url(),
            'version'     => get_bloginfo('version'),
            'language'    => get_bloginfo('language'),
            'timezone'    => wp_timezone_string(),
            'date_format' => get_option('date_format'),
            'time_format' => get_option('time_format'),
            'active_plugins' => get_option('active_plugins'),
            'active_theme' => get_option('stylesheet'),
        );
    }
}