
import { registerBlockVariation, registerBlockStyle } from '@wordpress/blocks';
import {SVG, Path} from '@wordpress/primitives';

registerBlockVariation(
	'core/cover',
	{
		name: 'dgwltd-cover',
		title: 'DGW.ltd Cover',
		attributes: {
			align: 'full',
            className: 'dgwltd-block-cover',
		}, 
        icon: (
			<SVG role="img" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
				<Path d="M512 64l0 224L64 288 64 64l448 0zM64 0C28.7 0 0 28.7 0 64L0 288c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-224c0-35.3-28.7-64-64-64L64 0zM0 448l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32zm192-32c-17.7 0-32 14.3-32 32l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0zm128 32l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32zm192-32c-17.7 0-32 14.3-32 32l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0z"/>
			</SVG>
		), 
        innerBlocks: [
			[
				'core/heading',
				{
					level: 1,
					placeholder: 'Heading'
				} 
			],
			[
				'core/paragraph',
				{
					placeholder: 'Enter content here...'
				} 
			],
		],
	}
);

/**
 * Register a paragraph block variation with meta binding
 */
// registerBlockVariation( 'core/paragraph', {
// 	name: 'show-my-data',
// 	title: __( 'Show My Data', 'block-binding-shortcut' ),
// 	description: __(
// 		'Display custom meta data in a paragraph',
// 		'block-binding-shortcut'
// 	),
//     attributes: {
// 		metadata: {
// 			bindings: {
// 				content: {
// 					source: 'acf/field',
// 					args: {
// 						key: 'custom_meta',
// 					},
// 				},
// 			},
// 		},
// 	},
//     isActive: [ 'metadata.bindings.content.args.key' ],
// 	scope: [ 'inserter', 'transform' ],
// } );


//Register a block style
const styles = [
    {
        name: 'default',
        label: 'Default',
        isDefault: true,
    },
    {
        name: 'html',
        label: 'HTML',
    },
    {
        name: 'css',
        label: 'CSS',
    }, 
	{
		name: 'js',
		label: 'JS'
	}, 
	{
		name: 'json',
		label: 'JSON'
	}, 
	{
		name: 'php',
		label: 'PHP'
	},
	{
		name: 'twig',
		label: 'TWIG'
	}
];

registerBlockStyle("core/code", [...styles]);