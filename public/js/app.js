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


// SCROLL FN NAV
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
    if (document.body.scrollTop > 900 || document.documentElement.scrollTop > 900) {

        console.log("tu as scrollé au delà de 900px");
        
        document.getElementById("logo").style.width = "200px";
        document.getElementById("logo").style.height = "70px";


    } else {
        // console.log("salut");
       
        // document.getElementById("logo").style.width = "400px";
        // document.getElementById("logo").style.height = "140px";
    }
}
    //     document.getElementById("nav").style.padding = "20px 0px";
        // document.getElementById("logo").style.width = "400px";
        // document.getElementById("logo").style.height = "140px";
    //     document.getElementsByClassName("nav-item").style.fontSize = "0.9em";
        // } else {
        //     document.getElementById("logo").style.width = "300px";
        //     document.getElementById("logo").style.height = "105px";
    //         document.getElementById("nav").style.padding = "0px 0px";
    //         document.getElementById("logo").style.width = "200px";
    //         document.getElementById("logo").style.height = "70px";
    //         document.getElementsByClassName("nav-item").style.fontSize = "0.9em";
    //     }
    // }

   
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