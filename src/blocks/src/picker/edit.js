// edit.js
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { useCallback } from '@wordpress/element';
import { PanelBody, Button } from '@wordpress/components';
import { ContentPicker, PostContext, PostTitle } from '@10up/block-components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

const Edit = (props) => {
    const { attributes, setAttributes } = props;
    const { selectedPosts } = attributes;

    // Handler to manage selection changes
    const onPickChange = useCallback(
        (pickedContent) => {
            if (!pickedContent || !pickedContent.length) {
                return;
            }

            // Create a new array to hold updated selections
            const updatedPosts = [...selectedPosts];

            pickedContent.forEach((item) => {
                // Check for duplicates based on 'id'
                const exists = updatedPosts.some((post) => post.id === item.id);
                if (!exists) {
                    console.log(item);
                    updatedPosts.push({ id: item.id, type: item.type });
                }
            });

            setAttributes({ selectedPosts: updatedPosts });
        },
        [selectedPosts, setAttributes]
    );

    // Handler to remove a post from the selectedPosts array
    const removePost = useCallback(
        (postId) => {
            const updatedPosts = selectedPosts.filter((post) => post.id !== postId);
            setAttributes({ selectedPosts: updatedPosts });
        },
        [selectedPosts, setAttributes]
    );

    // Handlers to move posts up and down in the list
    const movePostUp = useCallback(
        (index) => {
            if (index === 0) return; // Already at the top
            const updatedPosts = [...selectedPosts];
            const temp = updatedPosts[index - 1];
            updatedPosts[index - 1] = updatedPosts[index];
            updatedPosts[index] = temp;
            setAttributes({ selectedPosts: updatedPosts });
        },
        [selectedPosts, setAttributes]
    );

    const movePostDown = useCallback(
        (index) => {
            if (index === selectedPosts.length - 1) return; // Already at the bottom
            const updatedPosts = [...selectedPosts];
            const temp = updatedPosts[index + 1];
            updatedPosts[index + 1] = updatedPosts[index];
            updatedPosts[index] = temp;
            setAttributes({ selectedPosts: updatedPosts });
        },
        [selectedPosts, setAttributes]
    );

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Select Posts', 'myplugin')}>
                    <ContentPicker
                        selectedItems={selectedPosts}
                        onPickChange={onPickChange}
                        mode="post"
                        hideLabelFromVision={false}
                        label={__('Please select Posts or Pages:', 'dgwltd-site')}
                        contentTypes={['post', 'page']}
                    />
                </PanelBody>
            </InspectorControls>
            <div { ...useBlockProps() }>
                {selectedPosts && selectedPosts.length > 0 ? (
                    <ul>
                        {selectedPosts.map((post, index) => (
                            <li key={post.id} style={{ display: 'flex', alignItems: 'center', marginBottom: '8px' }}>
                                <PostContext postId={post.id} postType={post.type} isEditable={false} >
                                    <PostTitle tagName="h3" className="wp-block-example-hero__title" />
                                </PostContext>
                                <div>
                                    <Button
                                        onClick={() => movePostUp(index)}
                                        disabled={index === 0}
                                        aria-label={__('Move Up', 'dgwltd-site')}
                                    >
                                        ↑
                                    </Button>
                                    <Button
                                        onClick={() => movePostDown(index)}
                                        disabled={index === selectedPosts.length - 1}
                                        aria-label={__('Move Down', 'dgwltd-site')}
                                        style={{ marginLeft: '4px' }}
                                    >
                                        ↓
                                    </Button>
                                    <Button
                                        onClick={() => removePost(post.id)}
                                        isDestructive
                                        aria-label={__('Remove Post', 'dgwltd-site')}
                                        style={{ marginLeft: '4px' }}
                                    >
                                        ✕
                                    </Button>
                                </div>
                            </li>
                        ))}
                    </ul>
                ) : (
                    <p>{__('No posts selected', 'dgwltd-site')}</p>
                )}
            </div>
        </>
    );
};

export default Edit;