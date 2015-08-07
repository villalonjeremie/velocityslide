var _delay = 800;

jQuery(document).ready(function() {

    //
    // init jcarousel if any
    //
    if (jQuery('.jcarousel').length){
        // initialize
        initializeJcarousel({
            auto: false,
            interval: 600,
            duration : _delay
        });
    }

});