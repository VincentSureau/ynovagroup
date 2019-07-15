// SCROLL TO the TOP BUTTON
    window.onscroll = function() {scrollFunction()};

     function topFunction() {
        window.scrollTo(0,0);
    };


    function scrollFunction() {
        if (document.body.scrollTop > 1800 || document.documentElement.scrollTop > 1800) {
        document.getElementById("myBtn").style.display = "block";
        } else {
        document.getElementById("myBtn").style.display = "none";
        }
    }


// SCROLL FN NAV
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
    if (document.body.scrollTop > 900 || document.documentElement.scrollTop > 900) {
        document.getElementById("ephemeride").style.width = "220px";
        document.getElementById("ephemeride").style.height = "80px";  
    } 
}


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

    const handleIntersectFooter = function (entries, observer) {
        entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.add('hide-sticky-slim-footer')
            observer.unobserve(entry.target)
            }
        })
    }

    const observerFooter = new IntersectionObserver(handleIntersect2, options)
    document.querySelectorAll('footer-slim-link"]').forEach(function (r) {
        observer.observe(r)
    })



    $('.carousel').carousel()
