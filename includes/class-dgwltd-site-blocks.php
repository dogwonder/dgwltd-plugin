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
class Dgwltd_Site_Blocks {

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

        // create a new instance of the WP_HTML_Tag_Processor class.
        $tags = new WP_HTML_Tag_Processor( $block_content );
    
        // Generate a random ID for the details element, via the post ID
        $details_id = 'details-' . get_the_ID();
    
        // loop through each details block
        while ( $tags->next_tag( [ array( 'tag_name' => 'details' ) ] ) ) {

            // add an attribute for the current image count.
            $tags->set_attribute( 'name', $details_id );
    
        }
    
        // save the manipulated HTML back to the block content.
        $block_content = $tags->get_updated_html();
    
        // return the block content.
        return $block_content;
    
    }

    public function dgwltd_utility_edit_code_markup( $block_content, $block, $instance ) {

        // create a new instance of the WP_HTML_Tag_Processor class.
        $tags = new WP_HTML_Tag_Processor( $block_content );

        $styles = ['html', 'css', 'js', 'php'];

        // loop through each image block.
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