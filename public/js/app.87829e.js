"use strict";(self.webpackChunk_roots_bud_sage=self.webpackChunk_roots_bud_sage||[]).push([[524],{"./styles/app.scss":()=>{},"../node_modules/@roots/bud-client/lib/dom-ready.js":(s,e,t)=>{t.d(e,{A:()=>o});const o=s=>{window.requestAnimationFrame((async function e(){document.body?await s():window.requestAnimationFrame(e)}))}},"./scripts/app.js":(s,e,t)=>{(0,t("../node_modules/@roots/bud-client/lib/dom-ready.js").A)((async()=>{document.body.classList.add="visible",document.body.classList.remove="hidden";const s=document.querySelectorAll(".horizontal-tabs .tabs li"),e=document.querySelectorAll(".horizontal-tabs .tab-content");s.forEach((s=>{s.addEventListener("click",(e=>{e.preventDefault(),t(),o(s)}))}));const t=()=>{s.forEach((s=>{s.classList.remove("is-active")})),e.forEach((s=>{s.classList.remove("is-active")}))},o=s=>{s.classList.add("is-active");const e=s.querySelector("a").getAttribute("href");document.querySelector(e).classList.add("is-active")};!function(s){s(document).on("facetwp-loaded",(function(){FWP.loaded||""!=FWP.buildQueryString()&&s("html, body").animate({scrollTop:s(".properties-container").offset().top},500)})),s(".hamb").click((function(){s(this).toggleClass("is-active"),s(".nav-mobile").toggleClass("is-active"),s("body").toggleClass("is-active")}));var e=s("body");s(window).scroll((function(){s(window).scrollTop()>=1?e.addClass("scrolling-active"):e.removeClass("scrolling-active")}))}(jQuery)}))}},s=>{var e=e=>s(s.s=e);e("./scripts/app.js"),e("./styles/app.scss")}]);