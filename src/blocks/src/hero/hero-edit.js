(function () {
  'use strict';

  // Add debounce function
  function debounce(func, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  const acfUpdateFocusPosition = () => {  
    // Get the selected block
    const selectedBlock = select('core/block-editor').getSelectedBlock();
    
    if (!selectedBlock || selectedBlock.name !== 'acf/dgwltd-hero') {
      console.warn('Selected block is not the hero block.');
      return;
    }

    const { clientId, attributes } = selectedBlock;

    if (!attributes || !attributes.data) {
      console.warn('No ACF data found in the selected block.');
      return;
    }

    // Extract the fields
    const { background_image, focal_point_x, focal_point_y } = attributes.data;

    if (!background_image) {
      console.warn('No background image found for this block.');
      return;
    }

    // Extract the focal point update logic to a reusable function
    const updateFocalPoint = (x, y) => {
      const focusDot = document.querySelector('.focus-dot');
      const focalXInput = document.querySelector('.acf-field-number[data-name="focal_point_x"] input');
      const focalYInput = document.querySelector('.acf-field-number[data-name="focal_point_y"] input');
      
      if (focusDot) {
        focusDot.style.left = `${x}%`;
        focusDot.style.top = `${y}%`;
      }
      
      if (focalXInput && focalYInput) {
        focalXInput.value = x.toFixed(2);
        focalYInput.value = y.toFixed(2);
        
        // Trigger input event to notify ACF of the change
        focalXInput.dispatchEvent(new Event('input', { bubbles: true }));
        focalYInput.dispatchEvent(new Event('input', { bubbles: true }));
        
        console.log(`Updated Focal Point X: ${x.toFixed(2)}%`);
        console.log(`Updated Focal Point Y: ${y.toFixed(2)}%`);
      } else {
        console.warn("Could not find ACF focal point input fields.");
      }
      
      // Dispatch update to block attributes in the editor
      dispatch("core/block-editor").updateBlockAttributes(clientId, {
        data: {
          ...attributes.data,
          focal_point_x: x.toFixed(2),
          focal_point_y: y.toFixed(2)
        }
      });
      
      console.log("Updated ACF Focal Points in Block:", {
        focal_point_x: x.toFixed(2),
        focal_point_y: y.toFixed(2)
      });
    };

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
      updateFocalPoint(focal_point_x, focal_point_y);

      // If a previous event handler exists, remove it
      if (sidebarImage.dataset.eventAttached === "true") {
        console.log("Event listener already attached, skipping...");
        return; // Prevent duplicate event listeners
      }

      // Define the event handler with debounce
      const handleImageClick = debounce((event) => {
        const rect = event.target.getBoundingClientRect();
        const xCoord = event.clientX - rect.left;
        const yCoord = event.clientY - rect.top;

        const xAsPercentage = (xCoord / rect.width) * 100;
        const yAsPercentage = (yCoord / rect.height) * 100;

        // Use the extracted update function
        updateFocalPoint(xAsPercentage, yAsPercentage);
      }, 150);

      // Attach the event listener only if it hasn't been attached already
      sidebarImage.addEventListener('click', handleImageClick);
      sidebarImage.dataset.eventAttached = "true"; // Mark the event as attached

      console.log('Click event attached to background image in sidebar.');
    }, 500); // Delay to ensure ACF loads fully
  };

  function trackBlockSelection() {
    let previousBlock = null;

    subscribe(() => {
      const selectedBlock = select('core/block-editor').getSelectedBlock();
      
      // Add cleanup on block deselection
      if (previousBlock && previousBlock.name === 'acf/dgwltd-hero' && 
          (!selectedBlock || selectedBlock.name !== 'acf/dgwltd-hero')) {
        const focusDot = document.querySelector('.focus-dot');
        if (focusDot) {
          focusDot.style.display = 'none';
        }
      }
      
      if (selectedBlock && selectedBlock.name === 'acf/dgwltd-hero') { 
        if (selectedBlock !== previousBlock) {
          console.log('Hero block selected:', selectedBlock);
          acfUpdateFocusPosition();
          // Show the focus dot again if it was hidden
          const focusDot = document.querySelector('.focus-dot');
          if (focusDot) {
            focusDot.style.display = 'block';
          }
        }
      }

      previousBlock = selectedBlock;
    });
  }

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

  document.addEventListener("DOMContentLoaded", function() {
    acf.add_action('ready', function() {
      whenEditorIsReady().then(() => {
        trackBlockSelection(); 
      });
    });
  });

})();