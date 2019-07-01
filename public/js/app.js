console.log("app.js is loaded");

// MODAL DISPLAY ON PAGE LOAD
    // window.addEventListener('DOMContentLoaded', function() {
    //     let popup = document.getElementById('popup');
    //     popup.style.display = "block";
    // });
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });

    $(window).on('click', function(){
        $('#myModal').modal('hide');
    });
   

// SCROLL TOP BUTTON
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

   
// PARALLAX IMAGE ON HOMEPAGE
    function parallax() {
        var $slider = document.getElementById("parallax");
        var yPos = window.pageYOffset / $slider.dataset.speed;
        yPos = -yPos;
        var coords = '0% '+ yPos + 'px';
        $slider.style.backgroundPosition = coords;
    }
    window.addEventListener("scroll", function(){
        parallax();	
    });
    

