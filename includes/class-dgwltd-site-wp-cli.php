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

	public function dgwltd_register_wp_cli_commands() {

                // File: wp-content/plugins/export-global-styles/export-global-styles.php

                if (defined('WP_CLI') && WP_CLI) {
                    /**
                     * Exports global styles defined in theme.json to a CSS file.
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
                        $color_palette = [];
                        $font_families = [];
                        $font_sizes = [];
                        $spacing_sizes = [];
                        $custom_spacing = [];
                        $line_heights = [];
                        $letter_spacing = [];
                        $custom_elements = [];
                        $items = [];

                        if (class_exists("WP_Theme_JSON_Resolver")) {

                            $settings = WP_Theme_JSON_Resolver::get_theme_data()->get_settings();
                            $tree       = WP_Theme_JSON_Resolver::get_merged_data();
                            $data = $tree->get_raw_data();
                
                            // full theme color palette
                            if (isset($settings["color"]["palette"]["theme"])) {
                                $color_palette = $settings["color"]["palette"]["theme"];
                            }

                             //Typography - font families
                            if (isset($settings["typography"]["fontFamilies"]["theme"])) {
                                $font_families = $settings["typography"]["fontFamilies"]["theme"];
                            }
                        
                            //Typography - font sizes
                            if (isset($settings["typography"]["fontSizes"]["theme"])) {
                                $font_sizes = $settings["typography"]["fontSizes"]["theme"];
                            }
                        
                            //Custom spacing
                            if (isset($settings["custom"]["spacing"])) {
                                $custom_spacing = $settings["custom"]["spacing"];
                            }

                            //Spacing
                            if (isset($settings["spacing"]["spacingSizes"]["theme"])) {
                                $spacing_sizes = $settings["spacing"]["spacingSizes"]["theme"];
                            }
                        
                            //Line heights
                            if (isset($settings["custom"]["typography"]["lineHeight"])) {
                                $line_heights = $settings["custom"]["typography"]["lineHeight"];
                            }
                        
                            //Letter spacing
                            if (isset($settings["custom"]["typography"]["letterSpacing"])) {
                                $letter_spacing = $settings["custom"]["typography"]["letterSpacing"];
                            }
                            
                            //Custom elements
                            if (isset($data["styles"]["elements"])) {
                                $custom_elements = $data["styles"]["elements"];
                            }

                            foreach ($color_palette as $color) {
                                $color_palette_array[] = [
                                    "slug" => $color["slug"],
                                    "color" => $color["color"],
                                ];
                            }
                        
                            foreach ($font_families as $font) {
                                $font_families_array[] = [
                                    "fontFamily" => $font["fontFamily"],
                                    "slug" => $font["slug"],
                                ];
                            }
                        
                            foreach ($font_sizes as $font) {
                                $font_sizes_array[] = [
                                    "size" => $font["size"],
                                    "slug" => preg_replace('/(\d)([a-zA-Z])/', '$1-$2', $font["slug"]),
                                    "fluid" => $font["fluid"],
                                    "min" => $font["fluid"] ? $font["fluid"]["min"] : null,
                                    "max" => $font["fluid"] ? $font["fluid"]["max"] : null,
                                ];
                            }
                        
                            foreach ($line_heights as $size => $value) {
                                if (is_array($value)) {
                                    foreach ($value as $subSize => $subValue) {
                                        $line_heights_array[] = [
                                            "slug" => $size . "--" . $subSize,
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
                        
                            foreach ($letter_spacing as $size => $value) {
                                if (is_array($value)) {
                                    foreach ($value as $subSize => $subValue) {
                                        $letter_spacing_array[] = [
                                            "slug" => $size . "-" . $subSize,
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

                            foreach ($custom_spacing as $size => $value) {
                                if (is_array($value)) {
                                    foreach ($value as $subSize => $subValue) {
                                        $custom_spacing_array[] = [
                                            "slug" => $size . "--" . $subSize,
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

                            foreach ($spacing_sizes as $space) {
                                $spacing_sizes_array[] = [
                                    "name" => $space["name"],
                                    "slug" => $space["slug"],
                                    "size" => $space["size"], // Assuming 'size' is the correct key for the spacing value
                                ];
                            }
                
                            $custom_elements_array = [];
                            foreach ($custom_elements as $element => $properties) {
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

                            $items = [];

                            foreach ($color_palette_array as $color) {
                                $items['palette'][] = ["type" => "color", "slug" => $color['slug'], "value" => $color['color']];
                            }
                            foreach ($font_families_array as $font) {
                                $fontFamily = isset($font['fontFamily']) ? $font['fontFamily'] : null;
                                $items['fontFamilies'][] = ["type" => "font-family", "slug" => $font['slug'], "value" => $fontFamily];
                            }
                            foreach ($font_sizes_array as $size) {
                                $items['fontSizes'][] = ["type" => "font-size", "slug" => $size['slug'], "value" => $size['size']];
                            }
                            foreach ($custom_spacing_array as $space) {
                                $items['customSpacing'][] = ["type" => "custom-spacing", "slug" => $space['slug'], "value" => $space['value']];
                            }
                            foreach ($spacing_sizes_array as $size) {
                                $items['spacingSizes'][] = ["type" => "spacing-size", "slug" => $size['slug'], "size" => $size['size']];
                            }
                            foreach ($line_heights_array as $size) {
                                $items['lineHeights'][] = ["type" => "line-height", "slug" => $size['slug'], "value" => $size['value']];
                            }
                            foreach ($letter_spacing_array as $size) {
                                $value = isset($size['value']) ? $size['value'] : null;
                                $items['letterSacing'][] = ["type" => "letter-spacing", "slug" => $size['slug'], "value" => $value];
                            }


                        }
                        
                        // Convert the items array to JSON
                        $json = json_encode($items);

                        // Decode the JSON string into a PHP array
                        $data = json_decode($json, true);

                        //Add :root to the css
                        $css .= ":root {\n";

                        // Iterate over the palette array and generate CSS variables
                        if (isset($data['palette'])) {
                            foreach ($data['palette'] as $color) {
                                $css .= "--wp--preset--color--" . $color['slug'] . ": " . $color['value'] . ";\n";
                            }
                        }

                        // Iterate over the font families array and generate CSS variables
                        if (isset($data['fontFamilies'])) {
                            foreach ($data['fontFamilies'] as $font) {
                                $css .= "--wp--preset--font-family--" . $font['slug'] . ": " . $font['value'] . ";\n";
                            }
                        }

                        // Iterate over the fontSizes array and generate CSS variables
                        if (isset($data['fontSizes'])) {
                            foreach ($data['fontSizes'] as $size) {
                                $css .= "--wp--preset--font-size--" . $size['slug']. ": " . $size['value'] . ";\n";
                            }
                        }

                         // Iterate over the lineHeights array and generate CSS variables
                         if (isset($data['lineHeights'])) {
                            foreach ($data['lineHeights'] as $size) {
                                $css .= "--wp--custom--typography--line-height--" . $size['slug'] . ": " . $size['value'] . ";\n";
                            }
                        }

                        // Iterate over the letterSpacing array and generate CSS variables
                        if (isset($data['letterSacing'])) {
                            foreach ($data['letterSacing'] as $size) {
                                $css .= "--wp--custom--typography--letter-spacing--" . $size['slug'] . ": " . $size['value'] . ";\n";
                            }
                        }

                        // Iterate over the customSpacing array and generate CSS variables
                        if (isset($data['customSpacing'])) {
                            foreach ($data['customSpacing'] as $space) {
                                $css .= "--wp--custom--spacing--" . $space['slug'] . ": " . $space['value'] . ";\n";
                            }
                        }

                        // Iterate over the spacingSizes array and generate CSS variables
                        if (isset($data['spacingSizes'])) {
                            foreach ($data['spacingSizes'] as $size) {
                                $css .= "--wp--preset--spacing--" . $size['slug'] . ": " . $size['size'] . ";\n";
                            }
                        }

                        // Close the :root block
                        $css .= "}\n";

                        //output the css to terminal
                        // WP_CLI::line($css);

                        if (file_put_contents($output_path, $css)) {
                            WP_CLI::success("Global styles exported to {$output_path}");
                        } else {
                            WP_CLI::error("Failed to write to {$output_path}");
                        }
                    });
                }

	}


}
