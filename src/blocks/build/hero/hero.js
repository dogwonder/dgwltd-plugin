/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************!*\
  !*** ./src/blocks/src/hero/hero.js ***!
  \*************************************/
document.addEventListener("DOMContentLoaded", function () {
  let priorFocus;
  let modalTriggers = document.querySelectorAll('[data-popup-trigger]');
  if (!modalTriggers) return;
  modalTriggers.forEach(trigger => {
    trigger.addEventListener('click', function () {
      //Get current modal 
      //Get data-popup-trigger value
      let modalID = trigger.getAttribute('data-popup-trigger');

      //Get modal element
      //Find modal with data-popup-modal value that matches data-popup-trigger value
      let modal = document.querySelector('[data-popup-modal="' + modalID + '"]');

      //Only open the currently modal with the same data-popup-trigger value
      openModal(modal);
    });
  });

  //If click on document body and is not button or modal or modal children, close modal
  document.body.addEventListener('click', function (e) {
    //Get current modal
    let modal = document.querySelector('.popup-modal.is--visible');
    //If click on .modal but not .modal__dialog close modal
    if (modal && !modal.contains(e.target) && !e.target.matches('[data-popup-trigger]')) {
      closeModal(modal);
    }
  });
  function openModal(modal) {
    // Track the element (likely a button) that had focus before we open the modal.
    priorFocus = document.activeElement;
    var modalClose = modal.querySelector('.popup-modal__close');

    // Set up the event listeners we need for the modal
    modal.addEventListener("keydown", keydownEvent);
    modalClose.addEventListener('click', function () {
      closeModal(modal);
    });

    // Find all focusable children
    var focusableElementsString = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable]';
    var focusableElements = modal.querySelectorAll(focusableElementsString);
    focusableElements = Array.prototype.slice.call(focusableElements);
    var firstTabStop = focusableElements[0];
    var lastTabStop = focusableElements[focusableElements.length - 1];

    // Show the modal and overlay
    modal.classList.add('is--visible');
    document.body.classList.add('is-blacked-out');
    firstTabStop.focus();
    function keydownEvent(e) {
      // Escape key should close the modal
      if (e.keyCode === 27) {
        closeModal(modal);
      }

      // Tab key check for first or last tab stop
      if (e.keyCode === 9) {
        // Tab + Shift (reverse tabbing)
        if (e.shiftKey) {
          if (document.activeElement === firstTabStop) {
            e.preventDefault();
            lastTabStop.focus();
          }
        } else {
          if (document.activeElement === lastTabStop) {
            e.preventDefault();
            firstTabStop.focus();
          }
        }
      }
    }
  }
  function closeModal(modal) {
    //Get data-popup-type value
    let videoType = modal.getAttribute('data-popup-type');
    if (videoType == 'vimeo') {
      //Stop vimeo if playing
      var vimeo_player = modal.querySelector('#vimeo_player');
      vimeo_player.contentWindow.postMessage('{"method":"pause"}', '*');
    }
    if (videoType == 'youtube') {
      //Stop youtube if playing
      var youtube_player = modal.querySelector('#youtube_player');
      youtube_player.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*');
    }

    // Hide the modal and overlay
    modal.classList.remove('is--visible');
    document.body.classList.remove('is-blacked-out');

    // Set focus back to element that had it before the modal was opened
    priorFocus.focus();
  }
});
/******/ })()
;
//# sourceMappingURL=hero.js.map