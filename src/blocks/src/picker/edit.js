// edit.js
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, RichText} from '@wordpress/block-editor';
import { useCallback } from '@wordpress/element';
import { SelectControl, PanelBody, Button } from '@wordpress/components';
import { Icon, arrowUp, arrowDown, dragHandle, starEmpty, starFilled, close } from '@wordpress/icons';
import { CSS } from '@dnd-kit/utilities';
import { DndContext, closestCenter, MouseSensor, TouchSensor, useSensor, useSensors } from '@dnd-kit/core';
import { arrayMove, SortableContext, verticalListSortingStrategy, useSortable } from '@dnd-kit/sortable';

/**
 * @see https://github.com/10up/block-components/tree/develop?tab=readme-ov-file
 */
import { ContentPicker, PostContext, PostTitle, PostFeaturedImage } from '@10up/block-components';

/**
 * Import necessary styles
 */
import './editor.scss';

const Edit = (props) => {


    const { attributes, setAttributes } = props;
    const { selectedPosts = [], contentType = 'news', heading = __('Selected Posts', 'dgwltd-site'), favouritePost = null  } = attributes; // Ensure selectedPosts, contentType, and heading are initialized

    const sensors = useSensors(useSensor(MouseSensor), useSensor(TouchSensor));

    // Sortable Item Component
    const SortableItem = ({ id, post, index, movePostUp, movePostDown, removePost }) => {
        const {
            attributes,
            listeners,
            setNodeRef,
            transform,
            transition,
            isDragging,
        } = useSortable({ id });

        const style = {
            transform: CSS.Transform.toString(transform),
            transition,
            border: isDragging ? '2px dashed #ddd' : '2px dashed transparent',
            paddingTop: '10px',
            paddingBottom: '10px',
            display: 'flex',
            alignItems: 'center',
            paddingLeft: '8px',
        };

        return (
            <div ref={setNodeRef} style={style} {...attributes} className='wp-block-dgwltd-picker__item'>
                <div {...listeners} style={{ cursor: 'grab', marginTop: '8px', marginRight: '8px' }}>
                    <Icon icon={dragHandle} />
                </div>
                <PostContext postId={post.id} postType={post.type} isEditable={false}>
                    <PostFeaturedImage className="wp-block-dgwltd-picker__featured_image" />
                    <PostTitle tagName="h3" className="wp-block-dgwltd-picker__title" />
                </PostContext>
                <div>
                    <Button
                        onClick={() => movePostUp(index)}
                        disabled={index === 0}
                        aria-label={__('Move Up', 'dgwltd-site')}
                    >
                        <Icon icon={arrowUp} />
                    </Button>
                    <Button
                        onClick={() => movePostDown(index)}
                        disabled={index === selectedPosts.length - 1} 
                        aria-label={__('Move Down', 'dgwltd-site')}
                        style={{ marginLeft: '4px' }}
                    >
                        <Icon icon={arrowDown} />
                    </Button>
                    <Button
                        onClick={() => setFavouritePost(post.id)}
                        aria-label={__('Set as Favourite', 'dgwltd-site')}
                        style={{ marginLeft: '4px' }}
                    >
                        {post.id === favouritePost ? <Icon icon={starFilled} /> : <Icon icon={starEmpty} />}
                    </Button>
                    <Button
                        onClick={() => removePost(post.id)}
                        isDestructive
                        aria-label={__('Remove Post', 'dgwltd-site')}
                        style={{ marginLeft: '4px' }}
                    >
                        <Icon icon={close} />
                    </Button>
                </div>
            </div>
        );
    };

    // Handler to manage selection changes
    const onSearchPick = useCallback(
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
                    updatedPosts.push({ id: item.id, type: item.type });
                }
            });

            setAttributes({ selectedPosts: updatedPosts });
        },
        [selectedPosts, setAttributes]
    );

    // Function to move a post up in the list
    const movePostUp = (index) => {
        if (index === 0) return;
        const updatedPosts = arrayMove(selectedPosts, index, index - 1);
        setAttributes({ selectedPosts: updatedPosts });
    };

    // Function to move a post down in the list
    const movePostDown = (index) => {
        if (index === selectedPosts.length - 1) return;
        const updatedPosts = arrayMove(selectedPosts, index, index + 1);
        setAttributes({ selectedPosts: updatedPosts });
    };

    // Function to remove a post from the list
    const removePost = (id) => {
        const updatedPosts = selectedPosts.filter((post) => post.id !== id);
        setAttributes({ selectedPosts: updatedPosts });
    };

    const setFavouritePost = (postId) => {
        setAttributes({ favouritePost: postId });
    };

    // Handler for drag end event
    const handleDragEnd = ({ active, over }) => {
        if (over && active.id !== over.id) {
            const oldIndex = selectedPosts.findIndex((post) => `draggable-${post.id}` === active.id);
            const newIndex = selectedPosts.findIndex((post) => `draggable-${post.id}` === over.id);

            if (oldIndex !== -1 && newIndex !== -1) {
                const updatedPosts = arrayMove(selectedPosts, oldIndex, newIndex);
                setAttributes({ selectedPosts: updatedPosts });
            }
        }
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Select Posts', 'dgwltd-site')}>
                    <SelectControl
                        label={__('Content Type', 'dgwltd-site')}
                        value={contentType}
                        options={[
                            { label: __('News', 'dgwltd-site'), value: 'news' },
                            { label: __('Event', 'dgwltd-site'), value: 'event' },
                            { label: __('Blog', 'dgwltd-site'), value: 'blog' },
                        ]}
                        onChange={(value) => setAttributes({ contentType: value })} // Save the selected content type
                    />
                    <ContentPicker
                        selectedItems={selectedPosts}
                        onPickChange={onSearchPick}
                        mode="post"
                        hideLabelFromVision={false}
                        label={__('Please select Posts or Pages:', 'dgwltd-site')}
                        contentTypes={['post', 'page']}
                    />
                </PanelBody>
            </InspectorControls>
            <div {...useBlockProps()}>
                <RichText
                    tagName="h2"
                    value={heading}
                    onChange={(value) => setAttributes({ heading: value })}
                    placeholder={__('Enter heading...', 'dgwltd-site')}
                />
                <DndContext sensors={sensors} collisionDetection={closestCenter} onDragEnd={handleDragEnd}>
                    <SortableContext items={selectedPosts.map(post => `draggable-${post.id}`)} strategy={verticalListSortingStrategy}>
                        {selectedPosts && selectedPosts.length > 0 ? (
                            <div className="wp-block-dgwltd-picker__list">
                                {selectedPosts.map((post, index) => (
                                    <SortableItem
                                        key={post.id}
                                        id={`draggable-${post.id}`}
                                        post={post}
                                        index={index}
                                        movePostUp={movePostUp}
                                        movePostDown={movePostDown}
                                        removePost={removePost}
                                    />
                                ))}
                            </div>
                        ) : (
                            <p>{__('No posts selected', 'dgwltd-site')}</p>
                        )}
                    </SortableContext>
                </DndContext>
            </div>
        </>
    );
};

export default Edit;