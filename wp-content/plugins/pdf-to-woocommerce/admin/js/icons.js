import { library, dom } from './lib/node_modules/@fortawesome/fontawesome-svg-core/index.es.js';
import { fas } from './lib/node_modules/@fortawesome/free-solid-svg-icons/index.es.js';

library.add(fas);

console.log('library added!', library);

// MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

// var observer = new MutationObserver(function (mutations, observer) {
//     // fired when a mutation occurs
//     // console.log(mutations, observer);
//     console.log('observer ->', observer);
//     mutations.forEach((mutation, i) => {
//         console.log(`mutation ${i}`, mutation);
//     });

//     // ...
// });

// // define what element should be observed by the observer
// // and what types of mutations trigger the callback
// observer.observe(document, {
//     subtree: true,
//     attributes: true
//     //...
// });

window.updateIcons = () => dom.i2svg();

