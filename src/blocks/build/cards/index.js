/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/src/cards/index.scss":
/*!*****************************************!*\
  !*** ./src/blocks/src/cards/index.scss ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!***************************************!*\
  !*** ./src/blocks/src/cards/index.js ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./src/blocks/src/cards/index.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
// Import CSS


// Import dependencies

// Import wp-i18n to internationalize the block


// Import select from wp-data to access the post meta


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
      let card = {
        id: item.getAttribute('data-id')
      };
      cards.push(card);
    });

    // Update featured array
    Array.prototype.forEach.call(featured_input_options, function (option) {
      let value = option.getAttribute('value');
      if (value && value !== '' && value !== '0') {
        let featured_item = {
          id: value
        };
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
  const acfUpdatePostSelection = elem => {
    if (typeof acf == 'undefined') {
      return;
    }
    var cards_id = '657b1c0185c4b';
    var featured_card_id = '66703ce0f4c81';
    var allHighlights = acf.findFields({
      key: 'field_' + cards_id
    });

    // If there are no highlights fields then exit
    if (!allHighlights) {
      return;
    }
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
    return new Promise(resolve => {
      const unsubscribe = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.subscribe)(() => {
        if ((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.select)('core/block-editor').getBlockCount() > 0) {
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
})();

/******/ })()
;
//# sourceMappingURL=index.js.map