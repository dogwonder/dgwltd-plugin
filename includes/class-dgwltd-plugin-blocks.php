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
class DGWLTD_PLUGIN_BLOCKS {

    public function dgwltd_utility_edit_gallery_markup( $block_content, $block, $instance ) {

        //From Mark Wilkinson - https://gist.github.com/wpmark/2bd9764bae3d2e283c6aaf58a9d0b0ea

        // create a new instance of the WP_HTML_Tag_Processor class.
        $tags = new WP_HTML_Tag_Processor( $block_content );
    
        // set figure counter.
        $count = 0;
    
        // loop through each image block.
        while ( $tags->next_tag( [ 'class_name' => 'wp-block-image' ] ) ) {
    
            // increment the count.
            $count++;
    
            // add an attribute for the current image count.
            $tags->set_attribute( 'data-image-current', $count );
    
            // set the total images count attribute.
            $tags->set_attribute( 'data-image-total', count( $block['innerBlocks'] ) );
    
        }
    
        // save the manipulated HTML back to the block content.
        $block_content = $tags->get_updated_html();
    
        // return the block content.
        return $block_content;
    
    }

    public function dgwltd_utility_edit_accordion_markup( $block_content, $block, $instance ) {
    
        $tags = new WP_HTML_Tag_Processor( $block_content );
        $section_count = 0;
        
        // Add wrapper classes
        if ( $tags->next_tag( [ 'class_name' => 'wp-block-accordion' ] ) ) {
            $tags->add_class( 'govuk-accordion' );
            $tags->add_class( 'dgwltd-block-accordion' );
            $tags->set_attribute( 'data-module', 'govuk-accordion' );
            $tags->set_attribute( 'id', 'accordion-' . get_the_ID() );
        }
        
        $html = $tags->get_updated_html();
        $tags = new WP_HTML_Tag_Processor( $html );
        
        // Process each accordion item
        while ( $tags->next_tag( [ 'class_name' => 'wp-block-accordion-item' ] ) ) {
            $section_count++;
            $tags->add_class( 'govuk-accordion__section' );
            
            $item_bookmark = $tags->set_bookmark( 'accordion_item' );
            
            // Add h3 heading class
            if ( $tags->next_tag( [ 'tag_name' => 'h3' ] ) ) {
                $tags->add_class( 'govuk-accordion__section-heading' );
            }
            
            $tags->seek( 'accordion_item' );
            
            // Add button classes
            if ( $tags->next_tag( [ 'class_name' => 'wp-block-accordion-heading__toggle' ] ) ) {
                $tags->add_class( 'govuk-accordion__section-button' );
                $tags->set_attribute( 'id', 'accordion-heading-' . $section_count );
            }
            
            $tags->seek( 'accordion_item' );

            // Icon
            if ( $tags->next_tag( [ 'class_name' => 'wp-block-accordion-heading__toggle-icon' ] ) ) {
                $tags->add_class( 'visually-hidden' );
            }
            
            $tags->seek( 'accordion_item' );
            
            // Add content panel classes
            if ( $tags->next_tag( [ 'class_name' => 'wp-block-accordion-panel' ] ) ) {
                $tags->add_class( 'govuk-accordion__section-content' );
                $tags->set_attribute( 'id', 'accordion-content-' . $section_count );
            }
            
            $tags->seek( 'accordion_item' );
        }
        
        $html = $tags->get_updated_html();
        
        // Wrap h3 in section-header div
        $html = preg_replace(
            '/(<h3[^>]*class="[^"]*govuk-accordion__section-heading[^"]*"[^>]*>.*?<\/h3>)/s',
            '<div class="govuk-accordion__section-header">$1</div>',
            $html
        );
        
        return $html;
    }

    public function dgwltd_utility_edit_code_markup( $block_content, $block, $instance ) {

        // create a new instance of the WP_HTML_Tag_Processor class.
        $tags = new WP_HTML_Tag_Processor( $block_content );

        $styles = ['html', 'css', 'js', 'php'];

        // loop through each code block.
        while ( $tags->next_tag( [ 'class_name' => 'wp-block-code' ] ) ) {
    
            //Find the CSS class
            $class = $tags->get_attribute('class');
            //This returns wp-block-code is-style-html I just want to get the is-style- part
            $style = str_replace('wp-block-code is-style-', '', $class);
            
            // Check if the current tag has children and navigate to the child <code> tag
            if ( $tags->next_tag( [ 'tag_name' => 'code' ], true ) ) {
                // Add the class to the child <code> tag
                $tags->set_attribute( 'class', 'language-' . $style );
            }
    
        }

        // save the manipulated HTML back to the block content.
        $block_content = $tags->get_updated_html();
    
        // return the block content.
        return $block_content;

       
    }

}