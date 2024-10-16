/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(
	metadata,
	{
		icon: {
			src: <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" enable-background="new 0 0 448 512">
					<path class="fa-secondary" opacity=".4" d="M0 64L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-384c0-35.3-28.7-64-64-64L64 0C28.7 0 0 28.7 0 64zM56.3 244.9c.2-1 .5-2 .9-3c.8-1.9 2-3.7 3.5-5.3c6.2-6.2 16.4-6.2 22.6 0c4.2 4.2 8.5 8.5 12.7 12.7c12.2-12.2 24.5-24.5 36.7-36.7c6.2-6.2 16.4-6.2 22.6 0c3.1 3.1 4.7 7.2 4.7 11.3s-1.6 8.2-4.7 11.3c-16 16-32 32-48 48c-6.2 6.2-16.4 6.2-22.6 0c-8-8-16-16-24-24c-1.6-1.6-2.7-3.4-3.5-5.3c-.4-1-.7-2-.9-3c-.1-.8-.2-1.5-.2-1.5c-.1-.8-.1-.8-.1-1.6c0-1 .1-1.3 .1-1.5c0-.5 .1-1 .2-1.5zm8-136.2c.2-1 .5-2 .9-3c.8-1.9 2-3.6 3.4-5.1C71.6 97.8 75.6 96 80 96l32 0c8.8 0 16 7.2 16 16c0 10.7 0 21.3 0 32c0 8.8-7.2 16-16 16l-32 0c-4.4 0-8.4-1.8-11.3-4.7c-1.4-1.4-2.6-3.2-3.4-5.1c-.4-1-.7-2-.9-3c-.1-.5-.2-1.1-.2-1.6s-.1-1.1-.1-1.6c0-10.7 0-21.3 0-32.2c0-.4 0-.9 .1-1.5s.1-1.1 .2-1.6zm0 256c.2-1 .5-2 .9-3c.8-1.9 2-3.6 3.4-5.1c2.9-2.9 6.9-4.7 11.3-4.7l32 0c8.8 0 16 7.2 16 16c0 10.7 0 21.3 0 32c0 8.8-7.2 16-16 16l-32 0c-4.4 0-8.4-1.8-11.3-4.7c-1.4-1.4-2.6-3.2-3.4-5.1c-.4-1-.7-2-.9-3c-.1-.5-.2-1.1-.2-1.6s-.1-1.1-.1-1.6c0-10.7 0-21.3 0-32.2c0-.4 0-.9 .1-1.5s.1-1.1 .2-1.6zM192 128c0-8.8 7.2-16 16-16l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16zm0 128c0-8.8 7.2-16 16-16l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16zm0 128c0-8.8 7.2-16 16-16l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16z"/><path class="fa-primary" d="M64 112l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16L80 96c-8.8 0-16 7.2-16 16zm0 256l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm91.3-132.7c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L96 249.4 83.3 236.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l24 24c6.2 6.2 16.4 6.2 22.6 0l48-48z"/>
			</svg>
		},
		edit: Edit,
	}
)
