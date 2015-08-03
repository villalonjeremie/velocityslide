/**
 * initializeJcarousel.js
 *
 * @version 0.0.5
 *
 * @requires jquery.js
 * @requires jquery.jcarousel.min.js
 * @requires jcarousel.css
 *
 * @author Liva Henri Jacques CASTANET (LIV)
 *
 * @history
 * ------------------------------------------------------------------------
 * 2015-07-15 | LIV | 0.0.1 | Creation of the file.
 * ------------------------------------------------------------------------
 * 2015-07-16 | LIV | 0.0.2 | Add capability to configure all jcarousel instances (default/common/specific)
 * ------------------------------------------------------------------------
 * 2015-07-16 | LIV | 0.0.3 | Add new transition effect [fade]
 * ------------------------------------------------------------------------
 * 2015-07-20 | LIV | 0.0.4 | Add margin left item managment in this.reload()
 * ------------------------------------------------------------------------
 * 2015-07-21 | LIV | 0.0.5 | Add class on the current item with animate && animateend event
 * ------------------------------------------------------------------------
 */


/**
 * {function} initializeJcarousel : initialize all jcarousel on the page with their corresponding options
 *
 * @param {Object} options - a list of accepted options to pass
 *
 * @example
 * <div class="jcarousel-wrapper">
 *    <div class="jcarousel" data-options="{'auto' : false, 'fx': 'fade'}">
 *         <ul class="jcarousel-list">
 *             <li class="item"><img src="./footer-social-media-hover-details.png" /></li>
 *             <li class="item"><img src="./footer-social-media-hover-details-2.png" /></li>
 *             <li class="item"><img src="./footer-social-media-hover-details.png" /></li>
 *             <li class="item"><img src="./footer-social-media-hover-details-2.png" /></li>
 *         </ul>
 *     </div>
 *     <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
 *     <a href="#" class="jcarousel-control-next">&rsaquo;</a>
 *     <p class="jcarousel-pagination" data-jcarouselpagination="true"></p>
 * </div>
 */
var initializeJcarousel = function(options)
{
    var _this = this;

    this.width = 0;
    this.jcarouselStatus = 0;

    /**
     * {object} defaultOptions : list of all optinos. Taken by default, override by a jcarousel common config {options}, and then override by a specific data-options attribute {JSON formatted}
     */
    this.defaultOptions = {
        auto: false,
        interval: 3000,
        fx: 'slide',
        itemPerPage : [4, 3, 2, 1],
        breakpoints : [959, 641, 481, 481],
        listItemClass : 'jcarousel-list',
        itemClass : 'item',
        duration : 1000,
        control: {
            activeClass: 'active', // if changed, you will need to CSS it
            target: 1,
            itemActiveClass: 'active'
        }
    };

    /**
     * {object} options : merge options into defaultOptions recursively, keeping non declared default options
     */
    this.options = jQuery.extend(true, {}, this.defaultOptions, options);

    this.reload = function(jc)
    {
        if ( jc.options.itemPerPage instanceof Array ) {
            for (var n = 0; n < jc.options.itemPerPage.length; n++) {
                if (jc.options.itemPerPage.length > 1) {
                    if ( ! jc.options.breakpoints instanceof Array ) {
                        return console.debug('Error : options.breakpoints instanceof Array == ' + (jc.options.breakpoints instanceof Array) + '. Must be array.');
                    }
                    if ( jc.options.breakpoints.length !== jc.options.itemPerPage.length ) {
                        return console.debug('Error : options.breakpoints.length !== options.itemPerPage.length. There : ' + jc.options.breakpoints.length + ' !== ' + jc.options.itemPerPage.length + '. Must be equal.');
                    }

                    var _marginLeft = parseInt(jc.find('li:eq(1)').css('margin-left'));

                    // begin media queries
                    if (n == 0)
                    {
                        if (window.innerWidth > jc.options.breakpoints[n]) {
                            _this.width = (_this.width - (_marginLeft*jc.options.itemPerPage[n])) / jc.options.itemPerPage[n];
                            break;
                        }
                    }
                    else if ( n > 0 && n+1 !== jc.options.itemPerPage.length)
                    {
                        if (window.innerWidth >= jc.options.breakpoints[n]) {
                            _this.width = (_this.width - (_marginLeft*jc.options.itemPerPage[n])) / jc.options.itemPerPage[n];
                            break;
                        }
                    }
                    else if (n+1 === jc.options.itemPerPage.length)
                    {
                        if (window.innerWidth < jc.options.breakpoints[n]) {
                            _this.width = (_this.width - (_marginLeft*jc.options.itemPerPage[n])) / jc.options.itemPerPage[n];
                            break;
                        }
                    }
                }
                // if only one value is set
                else {
                    _this.width = _this.width / jc.options.itemPerPage[n];
                }
            } // end for
        } else {
            console.debug('Error : options.itemPerPage instanceof Array == ' + (jc.options.itemPerPage instanceof Array) + '. Must be array.')
        }
    };

    this.init = function()
    {
        jQuery('.jcarousel')
            .each(function( i ) {

                // implements for each jcarousel
                var _me = jQuery(this);

                _me.options = _this.options;

                // add ID to easy select a particular jCarousel.
                if(this.parentNode.id == '') {
                    this.parentNode.id = "jcarousel-" + i;
                }

                // override for a specific jcarousel configuration // add data-options="{jsonObject}"
                if (jQuery(this).attr('data-options')) {
                    _me.options = JSON.parse( JSON.stringify(jQuery(this).attr('data-options')) );
                    // if data-options is written with double quote, replace to get a right JSON format, ex : data-options="{'fx' : 'slide'}" => '{"fx" : "slide"}'
                    if (typeof _me.options === "string") {
                        _me.options = _me.options.replace(/\s/g,'');
                        _me.options = _me.options.replace(/\{'/g, '{"').replace(/\'\}/g, '"}');
                        _me.options = _me.options.replace(/\':/g, '":').replace(/:\'/g, ':"');
                        _me.options = _me.options.replace(/\',\'/g, '","').replace(/,\'/g, ',"');
                        _me.options = JSON.parse(_me.options);
                    }
                    // merge data-options into options recursively, keeping non declared default data-options
                    _me.options = jQuery.extend(true, {}, _this.options, _me.options);
                }

                // debug options list end output
                // console.log(_me.options);

                // initialize jcarousel plugins
                _me.jcarousel();

                // jcarousel effects options
                switch (_me.options.fx) {

                    case 'slide' :
                        _me.addClass('jcarousel-slide-fx');

                        // responsiveness part /* IE 8 does not support jcarousel:reload */
                        _me
                            .on('jcarousel:reload jcarousel:create', function () {
                                _this.width = _me.innerWidth();

                                _this.reload(_me);

                                _me.jcarousel('items').css('width', _this.width + 'px');
                            });
                        break;
                    case 'fade' :
                        // at this time, only single transition available
                        _me.options.itemPerPage = [1];
                        var fadingDuration = _me.options.duration;
                        _me.options.duration = 0;
                        _me.addClass('jcarousel-fade-fx');

                        // resetSliderHeight();
                        _me.find('li').css({ position: 'absolute' }); // replace by CSS .jcarousel.jcarousel-fade-fx li { position: absolute; }

                        // fading hack
                        _me.jcarousel('items').hide();
                        jQuery(_me.jcarousel('first')).show();

                        _me.on('jcarousel:visiblein', function(event, carousel) {
                            _me.find(event.target).fadeIn( fadingDuration );
                            setTimeout(function() {
                                _this.jcarouselStatus = 0;
                            }, 0);
                        });

                        _me.on('jcarousel:visibleout', function(event, carousel) {
                            _this.jcarouselStatus = 1;
                            _me.find(event.target).fadeOut( fadingDuration );
                            carousel._trigger('animateend'); // the event doesn't fire when items are positioned absolutely (so if autoscroll, it wouldn't work), fire manually
                        });

                        // responsiveness part /* IE 8 does not support jcarousel:reload */
                        _me
                            .on('jcarousel:reload jcarousel:create', function () {
                                _this.width = _me.innerWidth();

                                _this.reload(_me);

                                _me.jcarousel('items').css('width', _this.width + 'px');
                                setTimeout(function() {
                                    var _list = _me.find('~ .jcarousel-pagination a');
                                    var _class = _me.options.control.activeClass ? '.' + _me.options.control.activeClass : '.active';
                                    var _index = _me.find('~ .jcarousel-pagination a' + _class).length ? _me.find('~ .jcarousel-pagination a' + _class) : 0;
                                    _me.find('ul').css({
                                        height: _me.find('li:eq(' + _list.index(_index) + ') img').height() + 'px',
                                        transition: "height 200ms"
                                    });

                                    // manage absolute position of item // tricky :
                                    // if (_me.options.itemPerPage.length > 1) {
                                    //     for (var n = 0; n < _me.options.itemPerPage.length; n++) {
                                    //         // if (n%)
                                    //         _me.find('li:eq(' + n + ')').css({
                                    //             left: n * _this.width + 'px'
                                    //         })
                                    //     }
                                    // }
                                }, 0);
                            });
                    default:
                        _me.addClass('jcarousel-slide-fx');
                        break;
                }

                // force a jcarousel reload to apply all changes
                _me.jcarousel('reload', {
                    wrap: 'circular',
                    animation: {
                        duration: _me.options.duration
                    }
                });

                // active auto slide
                _me.jcarouselAutoscroll({
                    interval: _me.options.interval,
                    autostart: _me.options.auto,
                    target: _me.options.control.target
                });

                // pagination handlers
                _me.parent().find('.jcarousel-pagination')
                    .on('jcarouselpagination:active', 'a', function() { jQuery(this).addClass( _me.options.control.activeClass ); })
                    .on('jcarouselpagination:inactive', 'a', function() { jQuery(this).removeClass( _me.options.control.activeClass ); })
                    .on('click', function(e) { e.preventDefault(); })
                    .jcarouselPagination({
                        perPage: 1,
                        item: function(page) {
                            return '<a href="#' + page + '">' + page + '</a>';
                        }
                    });

                _me.parent().find('.jcarousel-control-prev').jcarouselControl({ target: '-=' + _me.options.control.target });

                _me.parent().find('.jcarousel-control-next').jcarouselControl({ target: '+=' + _me.options.control.target });

                /**
                 * on animation fallback
                 */
                 _me.on('jcarousel:animate', function(event, carousel) {
                    if (_me.options.control.itemActiveClass !== '') {
                        _me.jcarousel('items').removeClass( _me.options.control.itemActiveClass );
                    }
                 });
                /**
                 * after animation fallback
                 */
                 _me.on('jcarousel:animateend', function(event, carousel) {
                    var fullyvisible = _me.jcarousel('fullyvisible');
                    if (_me.options.control.itemActiveClass !== '') {
                        fullyvisible.addClass( _me.options.control.itemActiveClass );
                    }
                 });
            });
    }; // end init()

    // initialize
    this.init();
}
