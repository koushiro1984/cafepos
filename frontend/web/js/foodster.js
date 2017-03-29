/*=============================================
Booking Form
==============================================*/

$(function() {    

    $('#hero-carousel').on('slide.bs.carousel', function () {
        matchCarouselHeight();
    })
});


/*============================================
Match height of header carousel to window height
==============================================*/
function matchCarouselHeight() {
    // Adjust Header carousel .item height to same as window height
    var wH = $(window).height();
    $('#hero-carousel .item').css("height", wH);
}

/*============================================
Any JS inside the $(document).scroll function is called when the page is scrolled
==============================================*/
$(document).scroll( function() {
    if ( $(this).scrollTop()>=$('header').position().top ) {
        $('nav').addClass('navbar-shrink');
    }

    if ( $(window).scrollTop() < $('header').height() + 1 ) {
        $('nav').removeClass('navbar-shrink');
    }
});

/*====================================================================================================
Any JS inside $(window).load function is called when the window is ready and all assets are downloaded
======================================================================================================*/
$(window).load(function() {

    // Remove loading screen when window is loaded after 1.5 seconds
    setTimeout(function() {
        $('.loading-screen').fadeOut(); // fade out the loading-screen div
    },1500); // 1.5 second delay so that we avoid the 'flicker' of the loading screen showing for a split second and then hiding immediately when its not needed

    // Call function for Google Maps
    $('.restaurantPopUp').on('show.bs.modal', function (e) {
        // Call function for Google Maps when a modal is opened
        setTimeout(function() {
            loadGoogleMap();
        },300);   
    });

});

/*==================================================
Any JS inside $(function() runs when jQuery is ready
====================================================*/

$(window).resize(function() {
    matchCarouselHeight();
});

/*==================================================
Any JS inside $(function() runs when jQuery is ready
====================================================*/
$(function() {
    "use strict";

    matchCarouselHeight();

    //Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-shrink',
        offset: 75
    })

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    // Smooth scrolling links - requires jQuery Easing plugin
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);

        if ( $anchor.hasClass('header-scroll') ) {
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
        }
        else{
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - 75
            }, 1500, 'easeInOutExpo');
        }
        event.preventDefault();
    });

    new WOW().init();
});
