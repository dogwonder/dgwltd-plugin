/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

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
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!******************************************!*\
  !*** ./src/blocks/src/hero/hero-edit.js ***!
  \******************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
// Inspired via 
// https://henry.codes/writing/pure-css-focal-points/

// Import wp-i18n to internationalize the block


// Import select and dispatch from wp-data to access block meta

(function () {
  'use strict';

  const acfUpdateFocusPosition = () => {
    // Get the selected block
    const selectedBlock = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.select)('core/block-editor').getSelectedBlock();
    if (!selectedBlock || selectedBlock.name !== 'acf/dgwltd-hero') {
      console.warn('Selected block is not the hero block.');
      return;
    }
    const {
      clientId,
      attributes
    } = selectedBlock;
    if (!attributes || !attributes.data) {
      console.warn('No ACF data found in the selected block.');
      return;
    }

    // Extract the fields
    const {
      background_image,
      focal_point_x,
      focal_point_y
    } = attributes.data;
    if (!background_image) {
      console.warn('No background image found for this block.');
      return;
    }

    // Find the image inside the ACF sidebar
    setTimeout(() => {
      const sidebarImageWrap = document.querySelector('.acf-field-image[data-name="background_image"] .image-wrap');
      const sidebarImage = sidebarImageWrap ? sidebarImageWrap.querySelector('img') : null;
      if (!sidebarImage || !sidebarImageWrap) {
        console.warn('Could not find the background image in the ACF sidebar field.');
        return;
      }
      console.log('Background Image Found in Sidebar:', sidebarImage);

      // Create or select the focal point indicator dot
      let focusDot = sidebarImageWrap.querySelector('.focus-dot');
      if (!focusDot) {
        focusDot = document.createElement('div');
        focusDot.classList.add('focus-dot');
        sidebarImageWrap.appendChild(focusDot);
      }

      // Apply initial position based on saved focal points
      focusDot.style.left = `${focal_point_x}%`;
      focusDot.style.top = `${focal_point_y}%`;

      // If a previous event handler exists, remove it
      if (sidebarImage.dataset.eventAttached === "true") {
        console.log("Event listener already attached, skipping...");
        return; // Prevent duplicate event listeners
      }

      // Define the event handler
      const handleImageClick = event => {
        const rect = event.target.getBoundingClientRect();
        const xCoord = event.clientX - rect.left;
        const yCoord = event.clientY - rect.top;
        const xAsPercentage = xCoord / rect.width * 100;
        const yAsPercentage = yCoord / rect.height * 100;

        // Update the corresponding ACF input fields in the sidebar
        const focalXInput = document.querySelector('.acf-field-number[data-name="focal_point_x"] input');
        const focalYInput = document.querySelector('.acf-field-number[data-name="focal_point_y"] input');

        // Update dot position
        focusDot.style.left = `${xAsPercentage}%`;
        focusDot.style.top = `${yAsPercentage}%`;
        if (focalXInput && focalYInput) {
          focalXInput.value = xAsPercentage.toFixed(2);
          focalYInput.value = yAsPercentage.toFixed(2);

          // Trigger input event to notify ACF of the change
          focalXInput.dispatchEvent(new Event('input', {
            bubbles: true
          }));
          focalYInput.dispatchEvent(new Event('input', {
            bubbles: true
          }));
          console.log(`Updated Focal Point X: ${xAsPercentage.toFixed(2)}%`);
          console.log(`Updated Focal Point Y: ${yAsPercentage.toFixed(2)}%`);
        } else {
          console.warn("Could not find ACF focal point input fields.");
        }

        // Dispatch update to block attributes in the editor
        (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.dispatch)("core/block-editor").updateBlockAttributes(clientId, {
          data: {
            ...attributes.data,
            focal_point_x: xAsPercentage.toFixed(2),
            focal_point_y: yAsPercentage.toFixed(2)
          }
        });
        console.log("Updated ACF Focal Points in Block:", {
          focal_point_x: xAsPercentage.toFixed(2),
          focal_point_y: yAsPercentage.toFixed(2)
        });
      };

      // Attach the event listener only if it hasn't been attached already
      sidebarImage.addEventListener('click', handleImageClick);
      sidebarImage.dataset.eventAttached = "true"; // Mark the event as attached

      console.log('Click event attached to background image in sidebar.');
    }, 500); // Delay to ensure ACF loads fully
  };
  function whenEditorIsReady() {
    return new Promise(resolve => {
      const unsubscribe = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.subscribe)(() => {
        if ((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.select)('core/block-editor').getBlockCount() > 0) {
          unsubscribe();
          resolve();
        }
      });
    });
  }
  function trackBlockSelection() {
    let previousBlock = null;
    (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.subscribe)(() => {
      const selectedBlock = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.select)('core/block-editor').getSelectedBlock();
      if (selectedBlock && selectedBlock.name === 'acf/dgwltd-hero') {
        if (selectedBlock !== previousBlock) {
          console.log('Sidebar block selected:', selectedBlock);
          acfUpdateFocusPosition();
        }
      }
      previousBlock = selectedBlock;
    });
  }
  document.addEventListener("DOMContentLoaded", function () {
    acf.add_action('ready', function () {
      whenEditorIsReady().then(() => {
        trackBlockSelection();
      });
    });
  });
})();
})();

/******/ })()
;
//# sourceMappingURL=hero-edit.js.map