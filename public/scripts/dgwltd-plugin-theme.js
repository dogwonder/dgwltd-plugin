// Import AlpineJS core
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Sentinel watcher component
Alpine.data('sentinelWatcher', () => ({
  init() {
    const sentinel = document.getElementById('sentinel');
    if (!sentinel) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        const isPastHeader = entry.boundingClientRect.top < 0 && !entry.isIntersecting;
        document.documentElement.classList.toggle('is-scrolled', isPastHeader);
      },
      {
        root: null,
        threshold: 0,
        rootMargin: '0px 0px -1px 0px',
      }
    );
    observer.observe(sentinel);
  },
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