
import { registerBlockVariation, registerBlockStyle } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

const heroIcon = wp.element.createElement(
	wp.primitives.SVG,
	{ xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 576 512" },
	wp.element.createElement(
		wp.primitives.Path,
		{
			d: "M512 64l0 224L64 288 64 64l448 0zM64 0C28.7 0 0 28.7 0 64L0 288c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-224c0-35.3-28.7-64-64-64L64 0zM0 448l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32zm192-32c-17.7 0-32 14.3-32 32l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0zm128 32l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32zm192-32c-17.7 0-32 14.3-32 32l0 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-32c0-17.7-14.3-32-32-32l-32 0z",
		}
	)
);

const accordionIcon = wp.element.createElement(
	wp.primitives.SVG,
	{ xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 448 512" },
	wp.element.createElement(
		wp.primitives.Path,
		{
			d: "M0 64l448 0 0 64L0 128 0 64zM0 224l448 0 0 64L0 288l0-64zM448 384l0 64L0 448l0-64 448 0z",
		}
	)
);

registerBlockVariation(
	'core/cover',
	{
		name: 'dgwltd-cover',
		title: 'DGW.ltd Cover',
		attributes: {
			align: 'full',
            className: 'dgwltd-block-cover',
		}, 
        icon: heroIcon, 
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

// Register a group block variation
registerBlockVariation(
	'core/group',
	{
		name: 'dgwltd-accordion',
		title: 'DGW.ltd Details Accordion',
		icon: accordionIcon,
		attributes: {
            className: 'dgwltd-block-accordion',
		}, 
		innerBlocks: [
			[
				'core/paragraph', 
				{
					content: 'Show all sections'
				}
			], 
			[
				'core/details'
			], 
			[
				'core/details'
			], 
			[
				'core/details'
			], 
			[
				'core/details'
			]
		]
	}
);


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