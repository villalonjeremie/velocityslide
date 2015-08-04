var $  = jQuery.noConflict();
var $effect = $('.effect');
var $windowOpen = $('.js-window-open');
var $windowClose = $('.window-close');
var $slider = $('.slider');
var containerClass = 'container-inner';
var windowClass = 'window';
var hidewindowClass = 'hide-window';
var $powerslide = $('.powerslide');
var $sliderWrapper = $('.slider-wrapper');
var $slider = $('.slider');
var $slide = $('.slide');
var totalSlidesNbr = $slide.length;
var slideHeight = $('body').height();
var slideStartIndex = 0;
var $sliderBreadcrumb;
var slidePrevIndex = null;
var slideCurrentIndex = null;
var timerScroll;
var timerAnim;
var alreadyTouch = false;
var scrolling = false;
var breadcrumbCurrentClass = 'is-current';
var slides = new Array();
var optionsScrolling = $.extend({
    'scrollingSpeed': 700,
    'easing': 'easeInOutSine',
    'breadcrumb': true
}, optionsScrolling);

function setPage(){
    $windowOpen.on('click', function (event) {
        event.preventDefault();
    });
    $windowClose.on('click', function (event) {
        event.preventDefault();
    });

    $windowOpen.on('tap', function (event) { 
        event.preventDefault();
        $($(this).attr('href')).removeClass(hidewindowClass);
    });

    $windowClose.on('tap', function (event) {
        event.preventDefault();
        $($(this).closest('.' + windowClass)).addClass(hidewindowClass);
    });

    $slider.on('slider.startAnimation', function () {
    	$('.' + windowClass).addClass(hidewindowClass);
    });
}

function setSlider(){
        for ( var i = 0 ; i < totalSlidesNbr ; i++ ) {
            var slide = $slide.get(i);
            slides.push({
                'element' : $(slide),
                'transition' : $(slide).attr('data-slideTransition')
            });
        }

        if($slider.length > 0) {
            setSliderinit();
            setBreadcrumb();
            setEvents();
            setNavigation();
            $('.slide--0').css({'z-index': 2});
        }
}

function setSliderinit(){
    $slide.each(function () {
    $(this).css({'top' : ( slideHeight * $(this).index() ) + 'px'});
    });
    var $currentSlide = $slide.get( slideStartIndex );
    $($currentSlide).css({'top' : '0px'});
    slideCurrentIndex = slideStartIndex;
}

function updateBreadcrumb(slideCurrentIndex){

    $('li', $sliderBreadcrumb).removeClass(breadcrumbCurrentClass);
    $('li:eq(' + slideCurrentIndex + ')', $sliderBreadcrumb).addClass(breadcrumbCurrentClass);
}

function setBreadcrumb(){
    $sliderWrapper.append('<nav class="slider__nav"><div><ul></ul></div></nav>');
    $sliderBreadcrumb = $('.slider__nav ul');

    $slide.each(function () {
        $sliderBreadcrumb.append('<li><a href="#" class="goto goto--slide"><span>' + $(this).attr('data-slideTitle') + '</span></a></li>');
        var i = $(this).index();
        // attach navigation event
        $('li a:eq(' + i + ')', $sliderBreadcrumb).on('tap', function (event) {
            event.preventDefault();
            goToSlide(i);
        });
        $('li a:eq(' + i + ')', $sliderBreadcrumb).on('click', function (event) {
            event.preventDefault();
        });
    });
    updateBreadcrumb(slideStartIndex);
}

function setEvents() {
    $( window ).resize(function() {
        slideHeight = $('body').height();
        $slide.stop().css({'top' : slideHeight +'px'});
        $('.slide:eq(' + slideCurrentIndex + ')').stop().css({'top' : 0});
    });
}

function startSliding() {
    $slider.trigger('slider.startAnimation');
    $('.goto--slide-next, .goto--slide-prev').velocity({ opacity: 0 }, { display: 'none' });
    $('li', $sliderBreadcrumb).removeClass(breadcrumbCurrentClass);
}

function finishSliding(slideCurrentIndex) {
            $slider.trigger('slider.endAnimation');
            scrolling = false;
            if( slideCurrentIndex == 0 ) {
                $('.goto--slide-next').velocity({ opacity: 1 }, { display: 'block' });
            } else
            if( slideCurrentIndex == (totalSlidesNbr - 1) ) {
                $('.goto--slide-prev').velocity({ opacity: 1 }, { display: 'block' });
            } else {
                $('.goto--slide-next, .goto--slide-prev').velocity({ opacity: 1 }, { display: 'block' });
            }
            updateBreadcrumb(slideCurrentIndex);
}


function goToSlide(slideIndex) {
            if( (slideIndex !== slideCurrentIndex) && (slideIndex >= 0) && (slideIndex < totalSlidesNbr) ) {
                
                startSliding();
                slidePrevIndex = slideCurrentIndex;
                slideCurrentIndex = slideIndex;
                var $currentSlide = $slide.get(slideCurrentIndex);
                var $slidePrevIndex = $slide.get(slidePrevIndex);
                var slideFrom = slidePrevIndex;
                var slideTo = slideCurrentIndex;

                if( slideCurrentIndex > slidePrevIndex ) {
                    var directionForward = true;
                    var slideCountFrom = slidePrevIndex;
                    var slideCountTo = slideCurrentIndex;
                    var currentSpeed = optionsScrolling.scrollingSpeed + ( optionsScrolling.scrollingSpeed * ((slideTo - slideFrom - 1) * .5) );
                } else {
                    var directionForward = false;
                    var slideCountFrom = slideCurrentIndex;
                    var slideCountTo = slidePrevIndex;
                    var currentSpeed = optionsScrolling.scrollingSpeed + ( optionsScrolling.scrollingSpeed * ((slideFrom - slideTo - 1) * .5) );
                }
                
                for ( var i = slideCountFrom ; i <= slideCountTo ; i++ ) {                    
                    var slideAnimCurrentIndex = i;
                    var $slideAnimCurrent = slides[slideAnimCurrentIndex]['element'];
                    var slideAnimStartPos = -( slideHeight * ( slideFrom - slideAnimCurrentIndex ) );
                    var slideAnimEndPos = -( slideHeight * ( slideTo - slideAnimCurrentIndex ) );
                    if ( slides[slideAnimCurrentIndex]['transition'] == 'split') {
                        
                        $($slideAnimCurrent).css({'top': '0'});

                        $('.slide--split__left', $slideAnimCurrent).stop()
                                            .css({'top' : slideAnimStartPos + 'px'})
                                            .velocity({'top' : slideAnimEndPos + 'px'}, currentSpeed, optionsScrolling.easing );

                        $('.slide--split__right', $slideAnimCurrent).stop()
                                            .css({'top' : -slideAnimStartPos + 'px'})
                                            .velocity({'top' : -slideAnimEndPos + 'px'}, currentSpeed, optionsScrolling.easing, function() {
                                                // si c'est la dernière slide
                                                if( slideAnimCurrentIndex === slideCountTo) {
                                                    finishSliding(slideCurrentIndex);
                                                }
                                                $($slideAnimCurrent).css({'top': -slideAnimEndPos + 'px'});
                                            });
                    } else {
                        $($slideAnimCurrent).stop()
                                            .css({'top' : slideAnimStartPos + 'px'})
                                            .velocity({'top' : slideAnimEndPos + 'px'}, currentSpeed, optionsScrolling.easing, function() {
                                                // si c'est la dernière slide
                                                if( $(this).index() === slideCountTo) {
                                                    finishSliding(slideCurrentIndex);
                                                }
                                            });   
                      }
                   
                } 

            } else {
                scrolling = false;
            }

}


function  setNavigation() {

    $powerslide.on('mousewheel', function(event) {
        event.preventDefault();
        var deltaY = event.originalEvent.deltaY;

        if ( alreadyTouch !== false || scrolling !== false || typeof deltaY === 'undefined') {
            return false;
        }
        alreadyTouch = true;
        scrolling = true;


        clearTimeout(timerScroll);
        timerScroll = setTimeout(function() {
           alreadyTouch = false;
        }, 1200);


        if(deltaY < 0){
            goToSlide( (slideCurrentIndex - 1) );
        } else if(deltaY > 0){
            goToSlide( (slideCurrentIndex + 1) );
        }
    });


    $(window).keyup(function(event) {
        event.preventDefault();

        if ( alreadyTouch !== false || scrolling !== false) {
            return false;
        }
        scrolling = true;

        if ( event.which == 38 ) {
            goToSlide( (slideCurrentIndex - 1) );
        } else if ( event.which == 40 ) {
            goToSlide( (slideCurrentIndex + 1) );
        }
    });

    var hammerOptions = {
        preventDefault: true
    };

    $('.brand-home').hammer(hammerOptions).bind('tap', function (event) {
        event.preventDefault();
        window.location = $(this).attr('href');
    });

    $powerslide.hammer(hammerOptions).bind('swipeup', function (event) {
        goToSlide((slideCurrentIndex + 1));
    });

    $powerslide.hammer(hammerOptions).bind('swipedown', function (event) {
        goToSlide((slideCurrentIndex -1));
    });
    $('.goto--slide-next, .goto--slide-prev').on('click', function (event) {
        event.preventDefault();
    });
    $('.goto--slide-next').on('tap', function(event){
        event.preventDefault();
        goToSlide((slideCurrentIndex + 1));
    });
    $('.goto--slide-prev').on('tap', function(event){
        event.preventDefault();
        goToSlide((slideCurrentIndex - 1));
    });
}


function Application() { 
      setPage();
      setSlider();
};

jQuery( document ).ready(function() {
  Application(); 
});