// Import CSS
import './index.scss';

// Import dependencies

// Import wp-i18n to internationalize the block
import { __ } from '@wordpress/i18n';

// Import select from wp-data to access the post meta
import { select, subscribe } from '@wordpress/data';

// Create a function
(function () {
  'use strict';

  const updateFeaturedCards = (block, featured_card_id) => {
    let cards = [];
    let featured = [];
    let cards_options = block.querySelectorAll('.values [data-id]');
    let featured_input = block.parentNode.querySelector('.acf-field-' + featured_card_id + ' .acf-input select');
    let featured_input_options = block.parentNode.querySelectorAll('.acf-field-' + featured_card_id + ' .acf-input select option');
    let featured_card = acf.getField('field_6682bce463a69');

    // Update cards array
    Array.prototype.forEach.call(cards_options, function (item) {
      let card = { id: item.getAttribute('data-id') };
      cards.push(card);
    });

    // Update featured array
    Array.prototype.forEach.call(featured_input_options, function (option) {
      let value = option.getAttribute('value');
      if (value && value !== '' && value !== '0') {
        let featured_item = { id: value };
        featured.push(featured_item);
      }
    });

    // Add new cards to featured if not already present
    cards.forEach(function (card) {
      let found = featured.find(featured_item => featured_item.id == card.id);
      if (!found) {
        let post_title = block.querySelector('.values [data-id="' + card.id + '"]').textContent;
        featured_input.insertAdjacentHTML('beforeend', `<option value="${card.id}">${post_title}</option>`);
      }
    });

    if (featured_card) {
      // Use .val() to get the value of the ACF field
      let selectedValue = featured_card.val();

      if (selectedValue && selectedValue !== '') {
        // Ensure featured_input is the select element and set the value
        let optionExists = Array.from(featured_input.options).some(option => option.value === selectedValue);
        if (optionExists) {
          featured_input.value = selectedValue;
        } else {
          console.error("Selected value does not exist in the dropdown options.");
        }
      }
    }

    // Remove any featured items not in cards
    Array.prototype.forEach.call(featured_input_options, function (option) {
      let value = option.getAttribute('value');
      if (value && value !== '' && value !== '0' && !cards.find(card => card.id == value)) {
        option.remove();
      }
      // If the option that's removed is the same as the featured card, remove the featured card ID from the field
      if (value == featured_input.value && !cards.find(card => card.id == value)) {
        featured_input.value = '';
      }
    });

    // Save the selection to the block field
    saveFeaturedSelection(block, featured_card_id, featured_input.value);
  };

  const saveFeaturedSelection = (block, featured_card_id, selectedValue) => {
    let featured_card = acf.getField('field_6682bce463a69');
    if (featured_card) {
      // Save the selected value to the block field
      featured_card.val(selectedValue);
    }
  };

  const acfUpdatePostSelection = (elem) => {
    if (typeof acf == 'undefined') { return; }

    var cards_id = '657b1c0185c4b';
    var featured_card_id = '66703ce0f4c81';

    var allHighlights = acf.findFields({ key: 'field_' + cards_id });

    // If there are no highlights fields then exit
    if (!allHighlights) { return; }

    Array.prototype.forEach.call(allHighlights, function (block, i) {
      // Initial update on page load
      updateFeaturedCards(block, featured_card_id);

      // Listener for changes
      block.addEventListener('click', function () {
        updateFeaturedCards(block, featured_card_id);
      });

      // If the select field changes, update the featured card
      let featured_input = block.parentNode.querySelector('.acf-field-' + featured_card_id + ' .acf-input select');
      featured_input.addEventListener('change', function () {
        saveFeaturedSelection(block, featured_card_id, featured_input.value);
      });
    });
  };

  // Via https://gist.github.com/KevinBatdorf/fca19e1f3b749b5c57db8158f4850eff
  function whenEditorIsReady() {
    return new Promise((resolve) => {
      const unsubscribe = subscribe(() => {
        if (select('core/block-editor').getBlockCount() > 0) {
          unsubscribe();
          resolve();
        }
      });
    });
  }

  // Wait for the DOM to load
  document.addEventListener('DOMContentLoaded', function () {
    acf.add_action('load', function () {
      whenEditorIsReady().then(() => {
        acfUpdatePostSelection();
      });
    });
  });
})();