const e=document.querySelector(".home-panel-gallery .screenshot");document.querySelectorAll(".home-panel-gallery-links a").forEach((function(r){r.addEventListener("click",(function(t){t.preventDefault();for(document.querySelector(".home-panel-gallery-links a[aria-current]").removeAttribute("aria-current");e.firstChild;)e.removeChild(e.firstChild);e.appendChild(this.querySelector(".intrinsic").cloneNode(!0)),r.setAttribute("aria-current","true")}),!0)}));