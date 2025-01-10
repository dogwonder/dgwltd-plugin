// Import AlpineJS core 
import Alpine from 'alpinejs';

//Optional import AlpineJS plugins
//import focus from '@alpinejs/focus'

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

window.Alpine = Alpine;

// CSS selector
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