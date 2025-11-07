// Import AlpineJS core
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
window.Alpine = Alpine;
Alpine.plugin(intersect);

// Sentinel watcher component
Alpine.data('sentinelWatcher', () => ({
  toggleHeader(isPastSentinal) {
    // console.log('toggleHeader called:', isPastSentinal);
    document.documentElement.classList.toggle('is-scrolled', isPastSentinal);
  }
}));

Alpine.start();

// Import Prism.js
import 'prismjs';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-twig';

// Option 2: Import an extra theme (included in the 'prism-themes' package)
// import 'prism-themes/themes/prism-coldark-dark.css';

//Import motion animation library
import { animate, scroll } from "motion";

// Motion functions
animate(
    ".logo",
    { x: 20, rotate: 10 },
    {
      duration: 0.5,
      rotate: { duration: 0.25, ease: "easeOut" }
    }
).then(() => {
    animate(
        ".logo",
        { x: 0, rotate: 0 },
        {
          duration: 0.5,
          rotate: { duration: 0.25, ease: "easeOut" }
        }
    )
})

//Import webawesome
// import '@awesome.me/webawesome/dist/components/intersection-observer/intersection-observer.js';
// import '@awesome.me/webawesome/dist/components/tooltip/tooltip.js';
// import '@awesome.me/webawesome/dist/components/carousel/carousel.js';


//Import cally https://wicky.nillia.ms/cally/
// import "cally";