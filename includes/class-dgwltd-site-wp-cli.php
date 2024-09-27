<?php
/**
 * Define the blocks functionality.
 *
 * Loads and defines the ACF blocks for this plugin
 *
 * @since      1.0.0
 * @package    Dgwltd_Site
 * @subpackage Dgwltd_Site/includes
 * @author     Rich Holman <dogwonder@gmail.com>
 */
class Dgwltd_Site_WP_CLI {
    
    /**
     * Generates a clamp() expression for fluid typography.
     *
     * @param string $min Minimum font size (e.g., "1rem").
     * @param string $max Maximum font size (e.g., "1.25rem").
     * @return string The clamp() expression.
     */
    private function generate_clamp_expression($min, $max, $min_vw = 20, $max_vw = 93.75) {

        // Convert rem values to float for calculation
        $min_value = floatval(str_replace('rem', '', $min));
        $max_value = floatval(str_replace('rem', '', $max));

        // Note 
        // Minimum viewport width in rem - 320px
        // Maximum viewport width in rem - 1500px

        $roundValue = function($value) {
            return round($value, 4);
        };

        // Calculate slope for linear scaling
        $slope = ($max_value - $min_value) / ($max_vw - $min_vw);

        // Calculate intercept for linear scaling
        $intercept = (-1 * $min_vw) * $slope + $min_value;

        // Round the values
        $slope_rounded = $roundValue($slope);
        $intercept_rounded = $roundValue($intercept);

        // Generate the clamp() expression
        return "clamp({$min}, {$intercept_rounded}rem + " . ($slope_rounded * 100) . "vw, {$max})";

    }

	public function dgwltd_register_wp_cli_commands() {
        if (defined('WP_CLI') && WP_CLI) {
            /**
             * Exports global styles defined in theme.json to a CSS file.
             *
             * This command parses the theme.json settings and generates a CSS file
             * with CSS variables for colors, typography, spacing, and more.
             *
             * ## EXAMPLES
             *
             *     wp export-global-styles /path/to/output/styles.css
             *
             * @when after_wp_load
             */
            WP_CLI::add_command('export-global-styles', function($args, $assoc_args) {
                if (empty($args)) {
                    WP_CLI::error("Please provide the output path for the CSS file.");
                    return;
                }

                $output_path = $args[0];
                $css = "";

                // Initialize arrays
                $custom_widths_array = [];
                $color_palette_array = [];
                $font_families_array = [];
                $font_sizes_array = [];
                $spacing_sizes_array = [];
                $custom_spacing_array = [];
                $line_heights_array = [];
                $letter_spacing_array = [];
                $custom_elements_array = [];
                $items = [];

                // Get theme settings
                if (!class_exists("WP_Theme_JSON_Resolver")) {
                    WP_CLI::error("WP_Theme_JSON_Resolver class not found.");
                    return;
                }

                $settings = WP_Theme_JSON_Resolver::get_theme_data()->get_settings();
                $tree = WP_Theme_JSON_Resolver::get_merged_data();
                $raw_theme_data = $tree->get_raw_data();

                // Process widths
                if (isset($settings["custom"]["width"])) {
                    foreach ($settings["custom"]["width"] as $slug => $value) {
                        $custom_widths_array[] = [
                            "slug" => $slug,
                            "value" => $value,
                        ];
                    }
                }

                // Process color palette
                if (isset($settings["color"]["palette"]["theme"])) {
                    foreach ($settings["color"]["palette"]["theme"] as $color) {
                        $color_palette_array[] = [
                            "slug" => $color["slug"],
                            "color" => $color["color"],
                        ];
                    }
                }

                // Process font families
                if (isset($settings["typography"]["fontFamilies"]["theme"])) {
                    foreach ($settings["typography"]["fontFamilies"]["theme"] as $font) {
                        $font_families_array[] = [
                            "fontFamily" => $font["fontFamily"],
                            "slug" => $font["slug"],
                        ];
                    }
                }

                // Process font sizes with fluid support
                if (isset($settings["typography"]["fontSizes"]["theme"])) {
                    foreach ($settings["typography"]["fontSizes"]["theme"] as $font) {
                        if (is_array($font["fluid"])) {
                            // Generate the clamp() expression
                            $clamp_expression = $this->generate_clamp_expression($font["fluid"]["min"], $font["fluid"]["max"]);
                            
                            $font_size = $clamp_expression;
                        } else {
                            // Use fixed font size
                            $font_size = $font["size"];
                        }

                        $font_sizes_array[] = [
                            "size" => $font_size,
                            "slug" => preg_replace('/(\d)([a-zA-Z])/', '$1-$2', $font["slug"]),
                            // 'fluid', 'min', 'max' are no longer needed
                        ];
                    }
                }

                // Process custom spacing
                if (isset($settings["custom"]["spacing"])) {
                    foreach ($settings["custom"]["spacing"] as $size => $value) {
                        if (is_array($value)) {
                            foreach ($value as $subSize => $subValue) {
                                $custom_spacing_array[] = [
                                    "slug" => "{$size}--{$subSize}",
                                    "value" => $subValue,
                                ];
                            }
                        } else {
                            $custom_spacing_array[] = [
                                "slug" => preg_replace('/(\d)([a-zA-Z])/', '$1-$2', $size),
                                "value" => $value,
                            ];
                        }
                    }
                }

                // Process spacing sizes
                if (isset($settings["spacing"]["spacingSizes"]["theme"])) {
                    foreach ($settings["spacing"]["spacingSizes"]["theme"] as $space) {
                        $spacing_sizes_array[] = [
                            "name" => $space["name"],
                            "slug" => $space["slug"],
                            "size" => $space["size"],
                        ];
                    }
                }

                // Process line heights
                if (isset($settings["custom"]["typography"]["lineHeight"])) {
                    foreach ($settings["custom"]["typography"]["lineHeight"] as $size => $value) {
                        if (is_array($value)) {
                            foreach ($value as $subSize => $subValue) {
                                $line_heights_array[] = [
                                    "slug" => "{$size}--{$subSize}",
                                    "value" => $subValue,
                                ];
                            }
                        } else {
                            $line_heights_array[] = [
                                "slug" => $size,
                                "value" => $value,
                            ];
                        }
                    }
                }

                // Process letter spacing
                if (isset($settings["custom"]["typography"]["letterSpacing"])) {
                    foreach ($settings["custom"]["typography"]["letterSpacing"] as $size => $value) {
                        if (is_array($value)) {
                            foreach ($value as $subSize => $subValue) {
                                $letter_spacing_array[] = [
                                    "slug" => "{$size}-{$subSize}",
                                    "value" => $subValue,
                                ];
                            }
                        } else {
                            $letter_spacing_array[] = [
                                "slug" => $size,
                                "value" => $value,
                            ];
                        }
                    }
                }

                // Process custom elements (currently not used in CSS)
                if (isset($raw_theme_data["styles"]["elements"])) {
                    foreach ($raw_theme_data["styles"]["elements"] as $element => $properties) {
                        foreach ($properties as $property => $values) {
                            foreach ($values as $key => $value) {
                                $custom_elements_array[] = [
                                    "element" => $element,
                                    "property" => $property,
                                    "key" => $key,
                                    "value" => $value,
                                ];
                            }
                        }
                    }
                }

                //Build widths array
                if (!empty($custom_widths_array)) {
                    foreach ($custom_widths_array as $width) {
                        $items['widths'][] = [
                            "type" => "width",
                            "slug" => $width['slug'],
                            "value" => $width['value'],
                        ];
                    }
                }

                // Build items array
                if (!empty($color_palette_array)) {
                    foreach ($color_palette_array as $color) {
                        $items['palette'][] = [
                            "type" => "color",
                            "slug" => $color['slug'],
                            "value" => $color['color'],
                        ];
                    }
                }

                if (!empty($font_families_array)) {
                    foreach ($font_families_array as $font) {
                        $items['fontFamilies'][] = [
                            "type" => "font-family",
                            "slug" => $font['slug'],
                            "value" => $font['fontFamily'],
                        ];
                    }
                }

                if (!empty($font_sizes_array)) {
                    foreach ($font_sizes_array as $size) {
                        $items['fontSizes'][] = [
                            "type" => "font-size",
                            "slug" => $size['slug'],
                            "value" => $size['size'],
                        ];
                    }
                }

                if (!empty($custom_spacing_array)) {
                    foreach ($custom_spacing_array as $space) {
                        $items['customSpacing'][] = [
                            "type" => "custom-spacing",
                            "slug" => $space['slug'],
                            "value" => $space['value'],
                        ];
                    }
                }

                if (!empty($spacing_sizes_array)) {
                    foreach ($spacing_sizes_array as $size) {
                        $items['spacingSizes'][] = [
                            "type" => "spacing-size",
                            "slug" => $size['slug'],
                            "size" => $size['size'],
                        ];
                    }
                }

                if (!empty($line_heights_array)) {
                    foreach ($line_heights_array as $size) {
                        $items['lineHeights'][] = [
                            "type" => "line-height",
                            "slug" => $size['slug'],
                            "value" => $size['value'],
                        ];
                    }
                }

                if (!empty($letter_spacing_array)) {
                    foreach ($letter_spacing_array as $size) {
                        $items['letterSpacing'][] = [
                            "type" => "letter-spacing",
                            "slug" => $size['slug'],
                            "value" => $size['value'],
                        ];
                    }
                }

                // Convert items array to data
                $data = $items;

                // Generate CSS
                $css = ":root {\n";

                $mappings = [
                    'widths' => '--wp--custom--width--',
                    'palette' => '--wp--preset--color--',
                    'fontFamilies' => '--wp--preset--font-family--',
                    'fontSizes' => '--wp--preset--font-size--',
                    'lineHeights' => '--wp--custom--typography--line-height--',
                    'letterSpacing' => '--wp--custom--typography--letter-spacing--',
                    'customSpacing' => '--wp--custom--spacing--',
                    'spacingSizes' => '--wp--preset--spacing--',
                ];

                foreach ($mappings as $key => $prefix) {
                    if (isset($data[$key])) {
                        foreach ($data[$key] as $item) {
                            $varName = "{$prefix}{$item['slug']}";
                            $varValue = $item['value'] ?? $item['size'] ?? '';
                            $css .= "{$varName}: {$varValue};\n";
                        }
                    }
                }

                $css .= "}\n";

                // Process the 'color' styles for body
                if (isset($raw_theme_data["styles"])) {
                    $css .= "body {\n";
                    if (isset($raw_theme_data["styles"]["color"]["text"])) {
                        $css .= "    color: {$raw_theme_data['styles']['color']['text']};\n";
                    }
                    if (isset($raw_theme_data["styles"]["color"]["background"])) {
                        $css .= "    background-color: {$raw_theme_data['styles']['color']['background']};\n";
                    }
                    if (isset($raw_theme_data["styles"]["typography"]["fontFamily"])) {
                        $css .= "    font-family: {$raw_theme_data['styles']['typography']['fontFamily']};\n";
                    }
                    if (isset($raw_theme_data["styles"]["typography"]["fontSize"])) {
                        $css .= "    font-size: {$raw_theme_data['styles']['typography']['fontSize']};\n";
                    }
                    if (isset($raw_theme_data["styles"]["typography"]["fontWeight"])) {
                        $css .= "    font-weight: {$raw_theme_data['styles']['typography']['fontWeight']};\n";
                    }
                    if (isset($raw_theme_data["styles"]["typography"]["letterSpacing"])) {
                        $css .= "    letter-spacing: {$raw_theme_data['styles']['typography']['letterSpacing']};\n";
                    }
                    if (isset($raw_theme_data["styles"]["typography"]["lineHeight"])) {
                        $css .= "    line-height: {$raw_theme_data['styles']['typography']['lineHeight']};\n";
                    }
                    $css .= "}\n\n";
                }


                // Process 'elements' styles
                if (!empty($custom_elements_array)) {
                    // Organize elements by their name
                    $elements = [];
                    foreach ($custom_elements_array as $element) {
                        $elements[$element['element']][$element['property']][$element['key']] = $element['value'];
                    }

                    foreach ($elements as $element_name => $properties) {
                        $css .= "{$element_name} {\n";

                        foreach ($properties as $property => $values) {
                            switch ($property) {
                                case 'border':
                                    if (isset($values['color'])) {
                                        $css .= "    border-color: {$values['color']};\n";
                                    }
                                    if (isset($values['radius'])) {
                                        $css .= "    border-radius: {$values['radius']};\n";
                                    }
                                    if (isset($values['style'])) {
                                        $css .= "    border-style: {$values['style']};\n";
                                    }
                                    if (isset($values['width'])) {
                                        $css .= "    border-width: {$values['width']};\n";
                                    }
                                    break;

                                case 'color':
                                    if (isset($values['background'])) {
                                        $css .= "    background-color: {$values['background']};\n";
                                    }
                                    if (isset($values['text'])) {
                                        $css .= "    color: {$values['text']};\n";
                                    }
                                    break;

                                case 'spacing':
                                    if (isset($values['padding'])) {
                                        $padding = $values['padding'];
                                        $css .= "    padding: {$padding['top']} {$padding['right']} {$padding['bottom']} {$padding['left']};\n";
                                    }
                                    break;

                                case 'typography':
                                    if (isset($values['fontSize'])) {
                                        $css .= "    font-size: {$values['fontSize']};\n";
                                    }
                                    if (isset($values['lineHeight'])) {
                                        $css .= "    line-height: {$values['lineHeight']};\n";
                                    }
                                    if (isset($values['fontWeight'])) {
                                        $css .= "    font-weight: {$values['fontWeight']};\n";
                                    }
                                    if (isset($values['fontFamily'])) {
                                        $css .= "    font-family: {$values['fontFamily']};\n";
                                    }
                                    if (isset($values['letterSpacing'])) {
                                        $css .= "    letter-spacing: {$values['letterSpacing']};\n";
                                    }
                                    break;

                                default:
                                    // Handle other properties if necessary
                                    break;
                            }
                        }

                        $css .= "}\n\n";
                    }
                }

                // Ensure the output directory exists
                $directory = dirname($output_path);
                global $wp_filesystem;

                 // Initialize the WP_Filesystem API
                if ( ! function_exists( 'WP_Filesystem' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }

                WP_Filesystem();

                if ( ! $wp_filesystem->is_dir( $directory ) ) {
                    if ( ! $wp_filesystem->mkdir( $directory ) ) {
                        WP_CLI::error( "Failed to create directory: {$directory}" );
                        return;
                    }
                }

                // Write CSS to file
                if ( ! $wp_filesystem->put_contents( $output_path, $css, FS_CHMOD_FILE ) ) {
                    WP_CLI::error( "Failed to write to {$output_path}" );
                } else {
                    WP_CLI::success( "Global styles exported to {$output_path}" );
                }

            });
        }
    }


}
