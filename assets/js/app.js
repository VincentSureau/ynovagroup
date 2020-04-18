/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
require('bootstrap');

import bsCustomFileInput from 'bs-custom-file-input';

// SCROLL TO the TOP BUTTON
window.onscroll = function () { scrollFunction() };

function topFunction() {
    window.scrollTo(0, 0);
};


function scrollFunction() {
    if (document.body.scrollTop > 1800 || document.documentElement.scrollTop > 1800) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}


// // SCROLL FN NAV
// window.onscroll = function () { scrollFunction() };

// function scrollFunction() {
//     if (document.body.scrollTop > 900 || document.documentElement.scrollTop > 900) {
//         document.getElementById("ephemeride").style.width = "220px";
//         document.getElementById("ephemeride").style.height = "80px";
//     }
// }


// PARALLAX IMAGE ON HOMEPAGE
// function parallax() {
//     var $slider = document.getElementById("parallax");
//     var yPos = window.pageYOffset / $slider.dataset.speed;
//     yPos = -yPos;
//     var coords = '0% '+ yPos + 'px';
//     $slider.style.backgroundPosition = coords;
// }
// window.addEventListener("scroll", function(){
//     parallax();	
// });

const ratio = .1;
const options = {
    root: null,
    rootMargin: `0px`,
    threshold: ratio
}

const handleIntersect = function (entries, observer) {
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.add('reveal-visible')
            observer.unobserve(entry.target)
        }
    })
}

const observer = new IntersectionObserver(handleIntersect, options)
document.querySelectorAll('[class*="reveal-"]').forEach(function (r) {
    observer.observe(r)
})

$(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() > $(document).height() - 130) {
        $('.footer-slim-link').hide()
    } else {
        $('.footer-slim-link').show()
    }
});

console.log(window.onscroll);
$('.carousel').carousel()

// display file name in bootstrap file inputs
$(document).ready(function () {
    bsCustomFileInput.init()
})