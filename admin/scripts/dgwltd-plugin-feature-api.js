import { __ } from '@wordpress/i18n';
import { createBlock } from '@wordpress/blocks';
import { dispatch } from '@wordpress/data';
import { isInEditor } from '@wordpress/edit-post';

//Add dgwltd-plugin/banner, lede: Lede, title: Hello World! content: 'This is a banner block.'
wp.features.register('dgwltd-plugin/banner', {
  id: 'dgwltd-plugin/banner',
  name: __('DGW.ltd Banner'),
  description: __('Insert a banner block'),
  type: 'tool',
  location: 'client',
  categories: [ 'dgwltd', 'editor', 'blocks', 'patterns' ],
  is_eligible: isInEditor,
  input_schema: {
    type: 'object',
    properties: {
      lede: {
        type: 'string',
        description: __( 'Lede text (H3 heading).', 'dgwltd' ),
      },
      title: {
        type: 'string',
        description: __( 'Main title text (H2 heading).', 'dgwltd' ),
      },
      content: {
        type: 'string',
        description: __( 'Paragraph content.', 'dgwltd' ),
      },
    },
    required: [ 'title' ],
  },
  output_schema: {
    type: 'object',
    properties: {
      success: { type: 'boolean' },
      blockType: { type: 'string' },
    },
    required: [ 'success', 'blockType' ],
  },
   callback: ( args ) => {  
    console.log('Banner feature called with args:', args);
    
   if ( !args || typeof args.title !== 'string' || args.title.trim() === '' ) {
      throw new Error( 'Title is required for content pattern.' );
  }

    try {
      const blocks = [];
      
      // Create lede block (H3) if provided
      if ( args.lede && args.lede.trim() ) {
        blocks.push( createBlock( 'core/heading', {
          level: 3,
          content: args.lede.trim(),
        }));
      }
      
      // Create title block (H2) - required
      blocks.push( createBlock( 'core/heading', {
        level: 2,
        content: args.title.trim(),
      }));
      
      // Create paragraph block if provided
      if ( args.content && args.content.trim() ) {
        const contentHtml = args.content
          .replace( /\\n/g, '\n' )
          .replace( /\n/g, '<br>' );
          
        blocks.push( createBlock( 'core/paragraph', {
          content: contentHtml,
        }));
      }

      if ( blocks.length === 0 ) {
        throw new Error( 'No blocks created for content pattern.' );
      }

      // Insert all blocks at once
      dispatch( 'core/block-editor' ).insertBlocks( blocks );

      console.log(`Content pattern inserted successfully: ${blocks.length} blocks`);

      return {
        success: true,
        blockType: 'content-pattern',
        blocksInserted: blocks.length,
        message: `Content pattern with ${blocks.length} blocks has been inserted successfully.`,
      };
    } catch ( error ) {
      console.error('Error inserting content pattern:', error);
      throw new Error(
        `Failed to insert content pattern: ${
          error instanceof Error ? error.message : String( error )
        }`
      );
    }
  },
} );

// gets all features from the registry
// const features = wp.features.get();

// Reformat our features as tools for the LLM
// const toolsFromFeatures = function ( features ) {
// 	return features.map( ( { id, name, description, input_schema } ) => ( {
// 		id,
// 		name,
// 		description,
// 		parameters: input_schema,
// 		execute: async ( input ) => {
// 			return await wp.features.run( id, input );
// 		},
// 	} ) );
// };

//Run the toolsFromFeatures function to get our tools
// const tools = toolsFromFeatures( features );

// Export the tools for use in other parts of the application
// export { tools };


// Example usage of the feature API to insert a banner block
// wp.features.run( 'dgwltd-plugin/banner', {
//   title: 'Hello World!',
//   content: 'This is a banner block.',
// } );