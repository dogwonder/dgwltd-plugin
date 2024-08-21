(()=>{var e={374:()=>{class e extends HTMLElement{connectedCallback(){this.videoId=this.getAttribute("videoid");let t=this.querySelector(".lty-playbtn");if(this.playLabel=t&&t.textContent.trim()||this.getAttribute("playlabel")||"Play",this.dataset.title=this.getAttribute("title")||"",this.style.backgroundImage||(this.style.backgroundImage=`url("https://i.ytimg.com/vi/${this.videoId}/hqdefault.jpg")`,this.upgradePosterImage()),t||(t=document.createElement("button"),t.type="button",t.classList.add("lty-playbtn"),this.append(t)),!t.textContent){const e=document.createElement("span");e.className="lyt-visually-hidden",e.textContent=this.playLabel,t.append(e)}this.addNoscriptIframe(),"A"===t.nodeName&&(t.removeAttribute("href"),t.setAttribute("tabindex","0"),t.setAttribute("role","button"),t.addEventListener("keydown",(e=>{"Enter"!==e.key&&" "!==e.key||(e.preventDefault(),this.activate())}))),this.addEventListener("pointerover",e.warmConnections,{once:!0}),this.addEventListener("focusin",e.warmConnections,{once:!0}),this.addEventListener("click",this.activate),this.needsYTApi=this.hasAttribute("js-api")||navigator.vendor.includes("Apple")||navigator.userAgent.includes("Mobi")}static addPrefetch(e,t,n){const i=document.createElement("link");i.rel=e,i.href=t,n&&(i.as=n),document.head.append(i)}static warmConnections(){e.preconnected||(e.addPrefetch("preconnect","https://www.youtube-nocookie.com"),e.addPrefetch("preconnect","https://www.google.com"),e.addPrefetch("preconnect","https://googleads.g.doubleclick.net"),e.addPrefetch("preconnect","https://static.doubleclick.net"),e.preconnected=!0)}fetchYTPlayerApi(){window.YT||window.YT&&window.YT.Player||(this.ytApiPromise=new Promise(((e,t)=>{var n=document.createElement("script");n.src="https://www.youtube.com/iframe_api",n.async=!0,n.onload=t=>{YT.ready(e)},n.onerror=t,this.append(n)})))}async getYTPlayer(){return this.playerPromise||await this.activate(),this.playerPromise}async addYTPlayerIframe(){this.fetchYTPlayerApi(),await this.ytApiPromise;const e=document.createElement("div");this.append(e);const t=Object.fromEntries(this.getParams().entries());this.playerPromise=new Promise((n=>{let i=new YT.Player(e,{width:"100%",videoId:this.videoId,playerVars:t,events:{onReady:e=>{e.target.playVideo(),n(i)}}})}))}addNoscriptIframe(){const e=this.createBasicIframe(),t=document.createElement("noscript");t.innerHTML=e.outerHTML,this.append(t)}getParams(){const e=new URLSearchParams(this.getAttribute("params")||[]);return e.append("autoplay","1"),e.append("playsinline","1"),e}async activate(){if(this.classList.contains("lyt-activated"))return;if(this.classList.add("lyt-activated"),this.needsYTApi)return this.addYTPlayerIframe(this.getParams());const e=this.createBasicIframe();this.append(e),e.focus()}createBasicIframe(){const e=document.createElement("iframe");return e.width=560,e.height=315,e.title=this.playLabel,e.allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture",e.allowFullscreen=!0,e.src=`https://www.youtube-nocookie.com/embed/${encodeURIComponent(this.videoId)}?${this.getParams().toString()}`,e}upgradePosterImage(){setTimeout((()=>{const e=`https://i.ytimg.com/vi_webp/${this.videoId}/sddefault.webp`,t=new Image;t.fetchPriority="low",t.referrerpolicy="origin",t.src=e,t.onload=t=>{90==t.target.naturalHeight&&120==t.target.naturalWidth||(this.style.backgroundImage=`url("${e}")`)}}),100)}}customElements.define("lite-youtube",e)}},t={};function n(i){var a=t[i];if(void 0!==a)return a.exports;var o=t[i]={exports:{}};return e[i](o,o.exports,n),o.exports}(()=>{"use strict";document.head.appendChild(document.createElement("style")).textContent="\n\n  lite-vimeo {\n    aspect-ratio: 16 / 9;\n    background-color: #000;\n    position: relative;\n    display: block;\n    contain: content;\n    background-position: center center;\n    background-size: cover;\n    cursor: pointer;\n  }\n\n  lite-vimeo > iframe {\n    width: 100%;\n    height: 100%;\n    position: absolute;\n    top: 0;\n    left: 0;\n    border: 0;\n  }\n\n  lite-vimeo > .ltv-playbtn {\n    font-size: 10px;\n    padding: 0;\n    width: 6.5em;\n    height: 4em;\n    background: rgba(23, 35, 34, .75);\n    z-index: 1;\n    opacity: .8;\n    border-radius: .5em;\n    transition: opacity .2s ease-out, background .2s ease-out;\n    outline: 0;\n    border: 0;\n    cursor: pointer;\n  }\n\n  lite-vimeo:hover > .ltv-playbtn {\n    background-color: rgb(0, 173, 239);\n    opacity: 1;\n  }\n\n  /* play button triangle */\n  lite-vimeo > .ltv-playbtn::before {\n    content: '';\n    border-style: solid;\n    border-width: 10px 0 10px 20px;\n    border-color: transparent transparent transparent #fff;\n  }\n\n  lite-vimeo > .ltv-playbtn,\n  lite-vimeo > .ltv-playbtn::before {\n    position: absolute;\n    top: 50%;\n    left: 50%;\n    transform: translate3d(-50%, -50%, 0);\n  }\n\n  /* Post-click styles */\n  lite-vimeo.ltv-activated {\n    cursor: unset;\n  }\n\n  lite-vimeo.ltv-activated::before,\n  lite-vimeo.ltv-activated > .ltv-playbtn {\n    opacity: 0;\n    pointer-events: none;\n  }\n";class e extends(globalThis.HTMLElement??class{}){static _warmConnections(){e.preconnected||(e.preconnected=!0,t("preconnect","https://player.vimeo.com"),t("preconnect","https://i.vimeocdn.com"),t("preconnect","https://f.vimeocdn.com"),t("preconnect","https://fresnel.vimeocdn.com"))}connectedCallback(){this.videoId=this.getAttribute("videoid");let{width:t,height:n}=function({width:e,height:t}){let n=e,i=t;return n%320!=0&&(n=100*Math.ceil(e/100),i=Math.round(n/e*t)),{width:n,height:i}}(this.getBoundingClientRect()),i=window.devicePixelRatio||1;i>=2&&(i*=.75),t=Math.round(t*i),n=Math.round(n*i),fetch(`https://vimeo.com/api/v2/video/${this.videoId}.json`).then((e=>e.json())).then((e=>{let i=e[0].thumbnail_large;i=i.replace(/-d_[\dx]+$/i,`-d_${t}x${n}`),this.style.backgroundImage=`url("${i}")`}));let a=this.querySelector(".ltv-playbtn");this.playLabel=a&&a.textContent.trim()||this.getAttribute("playlabel")||"Play video",a||(a=document.createElement("button"),a.type="button",a.setAttribute("aria-label",this.playLabel),a.classList.add("ltv-playbtn"),this.append(a)),a.removeAttribute("href"),this.addEventListener("pointerover",e._warmConnections,{once:!0}),this.addEventListener("click",this.addIframe)}addIframe(){if(this.classList.contains("ltv-activated"))return;this.classList.add("ltv-activated");const e=document.createElement("iframe");e.width=640,e.height=360,e.title=this.playLabel,e.allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture",e.src=`https://player.vimeo.com/video/${encodeURIComponent(this.videoId)}?autoplay=1`,this.append(e),e.addEventListener("load",e.focus,{once:!0})}}function t(e,t,n){const i=document.createElement("link");i.rel=e,i.href=t,n&&(i.as=n),i.crossorigin=!0,document.head.append(i)}globalThis.customElements&&!globalThis.customElements.get("lite-vimeo")&&globalThis.customElements.define("lite-vimeo",e),n(374)})()})();