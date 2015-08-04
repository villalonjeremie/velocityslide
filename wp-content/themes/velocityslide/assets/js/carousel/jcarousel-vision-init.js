var _delay = 800;

$(document).ready(function() {

    //
    // init jcarousel if any
    //
    if ($('.jcarousel').length){
        // initialize
        initializeJcarousel({
            auto: false,
            interval: 600,
            duration : _delay
        });
    }

    visonOfStyleAjaxReturn();

    //
    // listen to thumbnail changes
    //
    $(document)
        .on("braid-ready", function() {
            visonOfStyleAjaxReturn();
    });

});

/**
 * {function} visonOfStyleAjaxReturn - click handler of the fake link in .jcarousel.oneperone
 */
function visonOfStyleAjaxReturn() {


        $('.jcarousel-control-prev, .jcarousel-control-next')
            .unbind('click.jcarousel-control-arrow')
            .bind('click.jcarousel-control-arrow', function() {

                var _p = $(this).parents('.vision-page');

                setTimeout(function() {
                    if (_p.find('.look-slides-item-first').hasClass('active')) {
                        _p.find('.jcarousel-control-prev, .jcarousel-control-next').hide();
                    }
                }, (_delay+50));

        });

    $('.link-to-steps')
        .unbind('click.link-to-steps')
        .bind('click.link-to-steps', function() {

            var _parent = $(this).parents('.jcarousel.oneperone').parent();

            initializeJcarousel({
                auto: false,
                interval: 600,
                duration : _delay
            });
            _parent.find('.jcarousel-control-next').click();
            _parent.find('.jcarousel-control-prev, .jcarousel-control-next').show();
    });

}
