(function () {
  'use strict';

  const { subscribe, select, dispatch } = wp.data;

  // Utility: Debounce function
  const debounce = (func, wait) => {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  };

  let lastSelectedClientId = null;

  const updateFocalPointVisual = (x, y) => {
    const focusDot = document.querySelector('.focus-dot');
    if (focusDot) {
      focusDot.style.left = `${x}%`;
      focusDot.style.top = `${y}%`;
    }
  };

  const updateFocalPointData = (clientId, attributes, x, y) => {

    const xNum = Number(x);
    const yNum = Number(y);

    const focalX = Number.isFinite(xNum) ? xNum.toFixed(2) : '50.00';
    const focalY = Number.isFinite(yNum) ? yNum.toFixed(2) : '50.00';

    const focalXInput = document.querySelector('.acf-field-number[data-name="focal_point_x"] input');
    const focalYInput = document.querySelector('.acf-field-number[data-name="focal_point_y"] input');

    if (focalXInput && focalYInput) {
      focalXInput.value = focalX;
      focalYInput.value = focalY;
      focalXInput.dispatchEvent(new Event('input', { bubbles: true }));
      focalYInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    dispatch('core/block-editor').updateBlockAttributes(clientId, {
      data: {
        ...attributes.data,
        focal_point_x: focalX,
        focal_point_y: focalY
      }
    });
  };

  const setupFocalPointEditor = (block) => {
    const { clientId, attributes } = block;
    const { background_image, focal_point_x = 50, focal_point_y = 50 } = attributes?.data || {};

    if (!background_image) {
      console.warn('No background image found.');
      return;
    }

    setTimeout(() => {
      const sidebarImageWrap = document.querySelector('.acf-field-image[data-name="background_image"] .image-wrap');
      const sidebarImage = sidebarImageWrap?.querySelector('img');

      if (!sidebarImageWrap || !sidebarImage) return;

      // Reuse or create focus dot
      let focusDot = sidebarImageWrap.querySelector('.focus-dot');
      if (!focusDot) {
        focusDot = document.createElement('div');
        focusDot.className = 'focus-dot';
        sidebarImageWrap.appendChild(focusDot);
      }
      focusDot.style.display = 'block';

      updateFocalPointVisual(focal_point_x, focal_point_y);

      if (sidebarImage.dataset.focusAttached === "true") return;

      const handleImageClick = debounce((e) => {
        const rect = sidebarImage.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        updateFocalPointVisual(x, y);
        updateFocalPointData(clientId, attributes, x, y);
      }, 150);

      sidebarImage.addEventListener('click', handleImageClick);
      sidebarImage.dataset.focusAttached = "true";
    }, 500);
  };

  const trackHeroBlockSelection = () => {
    subscribe(() => {
      const selectedBlock = select('core/block-editor').getSelectedBlock();
      if (!selectedBlock || selectedBlock.name !== 'acf/dgwltd-hero') {
        const dot = document.querySelector('.focus-dot');
        if (dot) dot.style.display = 'none';
        lastSelectedClientId = null;
        return;
      }

      if (selectedBlock.clientId !== lastSelectedClientId) {
        lastSelectedClientId = selectedBlock.clientId;
        setupFocalPointEditor(selectedBlock);
      }
    });
  };

  const whenEditorReady = () => {
    return new Promise((resolve) => {
      const unsubscribe = subscribe(() => {
        if (select('core/block-editor').getBlockCount() > 0) {
          unsubscribe();
          resolve();
        }
      });
    });
  };

  document.addEventListener('DOMContentLoaded', () => {
    acf.add_action('ready', () => {
      whenEditorReady().then(trackHeroBlockSelection);
    });
  });
})();