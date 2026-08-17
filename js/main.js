(function ($) {
    "use strict";
    
    // // Initialize WOW.js animations
    new WOW().init();


    $(window).scroll(function () {
        // Sticky Navbar
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px'); //sticky navbar
            $('.back-to-top').fadeIn('slow'); // back to top button
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-100px'); // sticky navbar
            $('.back-to-top').fadeOut('slow'); // back to top button
        }
    });
    
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });
    
})(jQuery);

