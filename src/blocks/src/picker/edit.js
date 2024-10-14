import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { ContentPicker } from '@10up/block-components';

const Edit = (props) => {
  const { attributes, setAttributes } = props;
  const { selectedPosts } = attributes;

  const onSelectPosts = (posts) => {
    setAttributes({ selectedPosts: posts });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Select Posts', 'myplugin')}>
          <ContentPicker
            selectedItems={selectedPosts}
            onChange={onSelectPosts}
          />
        </PanelBody>
      </InspectorControls>
      <div>
        <h3>{__('Selected Posts', 'myplugin')}</h3>
        <ul>
          {selectedPosts.map((post) => (
            <li key={post.id}>{post.title.rendered}</li>
          ))}
        </ul>
      </div>
    </>
  );
};

export default Edit;