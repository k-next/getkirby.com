!function(t){var e={};function n(c){if(e[c])return e[c].exports;var i=e[c]={i:c,l:!1,exports:{}};return t[c].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,c){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:c})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/assets/",n(n.s=5)}({5:function(t,e,n){t.exports=n("Bf0w")},Bf0w:function(t,e,n){"use strict";(0,n("YI4z").focusWithin)(document.querySelector(".js-search-field"))},YI4z:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var c=function(){try{return document.querySelector(":focus-within"),function(){}}catch(t){return function(t){document.activeElement&&t.contains(document.activeElement)&&t.classList.add("is-focus-within"),t.addEventListener("focus",function(){t.classList.add("is-focus-within")},!0),t.addEventListener("blur",function(){t.contains(document.activeElement)||t.classList.remove("is-focus-within")},!0)}}}();e.focusWithin=c}});
//# sourceMappingURL=search.js.map