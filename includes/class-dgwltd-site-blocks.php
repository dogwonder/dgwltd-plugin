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

}