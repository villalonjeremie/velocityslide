// START for file : /assets/kerastase/js/global/intro.js

/*jshint curly: true, eqeqeq: true, forin: true, immed: true, noarg: true, quotmark: single, undef: true, unused: true, strict: false, trailing: true, regexdash: true, smarttabs: true, browser: true, jquery: true*/
/*global box:true*/
box.define('kerastase/ui/intro', function(require, module) {
    var bind = require('function/bind');
    var debounce = require('function/debounce');

    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/element' ],

        DEFAULTS: {
            resizeDebounce: 200
        },

        setup: function(cfg) {
            cfg.firstDisplay = true;
            this.callModule('ui/element@setup', cfg);

            // dont change order of the following declarations
            this._cfgSequence(cfg);
            this._cfgAdapter(cfg);
            this._cfgMask(cfg);
            this._cfgVisual(cfg);
            this._cfgPackshot(cfg);
            this._cfgVideo(cfg);
            this._cfgContent(cfg);
            this._cfgSwipes(cfg);

            var adapter = this.adapter;
            if(adapter && adapter.introReady) {
                adapter.introReady(this);
            }

            this._cfgResize(cfg);

            if(cfg.image) {
                this.nextData = {
                    image: cfg.image,
                    bgcolor: cfg.bgcolor,
                    videoFile: cfg.videoFile,
                    videoPoster: cfg.videoPoster,
                    videoLabel: cfg.videoLabel
                };
                this.loading.play(this);
            }
        },

        _cfgSequence: function() {
            this.loading = require('kerastase/ui/introSequence').create();
        },

        _cfgAdapter: function(cfg) {
            if(cfg.adapter) {
                this.adapter = require(cfg.adapter);
            }
        },

        _cfgMask: function(cfg) {
            this.mask = require('kerastase/ui/introMask').create({
                $core: cfg.$core.find('.intro-visual-mask'),
                fxShowDuration: cfg.fxShowMaskDuration,
                fxHideDuration: cfg.fxHideMaskDuration
            });
        },

        _cfgVisual: function(cfg) {
            this.visual = require('kerastase/ui/introVisual').create({
                $core: cfg.$core.find('.intro-visual'),
                objectFit: cfg.objectFit
            });
        },

        _cfgPackshot: function(cfg) {
            var $pack = cfg.$core.find('.intro-pack');
            this.packshot = require('kerastase/ui/introPackshot').create({
                $core: $pack.length ? $pack : null,
                insertTarget: cfg.$core.find('.wrapper'),
                insertMethod: 'prependTo',
                fxShowDuration: cfg.fxShowPackshotDuration,
                fxHideDuration: cfg.fxHidePackshotDuration
            });
        },

        _cfgContent: function(cfg) {
            this.content = require('kerastase/ui/introContent').create({
                $core: cfg.$core.find('.intro-content-outer'),
                listeners: require('events/listeners').create().add('beforeChange', this, '_handleBeforePageChange')
            });
            if(this.content.resize) {
                $j(window).load(bind(function() {
                    this.resizedBy  = this.content.resize();
                    this.packshot.move(this.resizedBy);
                }, this));
            }

            if(this.adapter && this.adapter.handlePageChange) {
                this.content.on('change', bind(function() {
                    this.adapter.handlePageChange(this);
                }, this));
            }
        },

        _cfgVideo: function(cfg) {
            this.video = require('kerastase/ui/introVideo').create({
                insertTarget: cfg.$core.find('.intro-inner')
            });
            this.video
                .on('addVideo', bind(function() {
                    this.stopAutoplay();
                    this.packshot.hideImmediatly();
                }, this))
                .on('removeVideo', bind(function(evt) {
                    this.packshot.showImmediatly();
                    this.startAutoplay(evt.details.fromEnd ? { reduced: true } : null);
                }, this));
        },

        _cfgResize: function(cfg) {
            if(this.adapter && this.adapter.handleResize) {
                this.adapter.handleResize(this);
            } else {
                this.setHeight(cfg.$core.height());
            }
            cfg.debounced = debounce(bind(this.updateSize, this), cfg.resizeDebounce);
            require('dom/viewResizeManager').add(cfg.debounced);
        },

        _cfgSwipes: function(cfg) {
            require('kerastase/swipes').start(cfg.$core, {
                left: bind(this._handleSwipeLeft, this),
                right: bind(this._handleSwipeRight, this)
            });
        },

        _handleSwipeLeft: function() {
            if(this.content && !this.loading.cfg.isPlaying) {
                this.content.next();
            }
        },

        _handleSwipeRight: function() {
            if(this.content && !this.loading.cfg.isPlaying) {
                this.content.previous();
            }
        },

        _handleBeforePageChange: function(evt) {
            if(this.loading.cfg.isPlaying) {
                evt.preventDefault();
            } else {
                this.nextData = evt.details.itemData;
                this.loading.play(this);
            }
        },

        updateSize: function() {
            if(this.adapter && this.adapter.handleResize) {
                this.adapter.handleResize(this);
            }
            this.visual.position();
            this.packshot.setSize(this.visual.getSize());
        },

        setHeight: function(height) {
            this.cfg.$core
                .height(height)
                .find('.wrapper')
                .height(height);
            this.content.setHeight(height);
            this.visual.setHeight(height);
            this.video.setHeight(height);
        },

        changeBackground: function(color) {
            if(color) {
                this.cfg.$core.css('backgroundColor', color);
            }
        },

        setDarkVisual: function() {
            this.cfg.$core.addClass('intro-item-dark');
        },

        unsetDarkVisual: function() {
            this.cfg.$core.removeClass('intro-item-dark');
        },

        startAutoplay: function(settings) {
            var cfg = this.cfg;
            if(cfg.autoplay && this.content.pagination) {
                var delay = settings && settings.reduced ? cfg.autoplayReduced : cfg.autoplay;
                cfg.timerId = setTimeout(bind(function() {
                    this.next();
                }, this.content), delay);
            }
        },

        stopAutoplay: function() {
            var cfg = this.cfg;
            if(cfg.timerId) {
                clearTimeout(cfg.timerId);
                cfg.timerId = null;
            }
        }
    });
});

box.define('kerastase/ui/introSequence', function(require, module) {
    module.exports = {
        create: function() {
            return require('sequence/manager').create({
                name: 'loading'
            }).describe('prepare', {
                main: function(intro) {
                    intro.stopAutoplay();
                    intro.cfg.mainActionsDone = false;
                },
                leave: 'hide packshot'
            }).describe('hide packshot', {
                enter: function(intro) {
                    if(!intro.cfg.firstDisplay && intro.content.pagination && intro.packshot.cfg.packshotElm) {
                        this.proceed('main');
                    } else {
                        this.proceed('show mask');
                    }
                },
                main: function(intro) {
                    intro.packshot.hide();
                },
                leave: function(intro) {
                    if(intro.packshot.cfg.isFilter) {
                        this.proceed('show mask');
                    } else {
                        this.when(intro.packshot.cfg.fxHide).emit('end').proceed('show mask');
                    }
                }
            }).describe('show mask', {
                enter: function(intro) {
                    this.proceed(intro.cfg.firstDisplay ? 'visual and content' : 'main');
                },
                main: function(intro) {
                    intro.mask.show();
                },
                leave: function(intro) {
                    this.when(intro.mask.cfg.fxShow).emit('end').proceed('visual and content');
                }
            }).describe('visual and content', {
                main: function(intro) {
                    if(intro.content) {
                        if(intro.content.isAnimatable()) {
                            intro.content.hide(); // hide is not an animation here
                        }
                    }
                    var data = intro.nextData;
                    intro.changeBackground(data.bgcolor);
                    intro.visual.load(data.image);
                    if(data.packshot) {
                        intro.packshot.load(data.packshot);
                    } else {
                        intro.packshot.empty();
                    }
                    if(data.videoFile) {
                        intro.video.addLink(data);
                    } else {
                        intro.video.removeLink();
                    }
                    if(data.dark) {
                        intro.setDarkVisual();
                    } else {
                        intro.unsetDarkVisual();
                    }
                },
                leave: function(intro) {
                    if(!intro.visual.cfg.isLoading) {
                        this.proceed('inject slide');
                    } else {
                        this.when(intro.visual).emit('complete').proceed('inject slide');
                    }
                    if(intro.nextData.packshot) {
                        this.when(intro.packshot).emit('complete').proceed('manage packshot');
                    }
                }
            }).describe('inject slide', {
                enter: function(intro) {
                    this.proceed(intro.content.confirmPageRequest ? 'main' : 'hide mask');
                },
                main: function(intro) {
                    intro.content.confirmPageRequest();
                },
                leave: 'hide mask'
            }).describe('hide mask', {
                main: function(intro) {
                    intro.cfg.firstDisplay = false;
                    intro.mask.hide();
                },
                leave: function(intro) {
                    this.when(intro.mask.cfg.fxHide).emit('end').proceed('show content');
                }
            }).describe('show content', {
                enter: function(intro) {
                    this.proceed(intro.content.isAnimatable() ? 'main' : 'primary actions done');
                },
                main: function(intro) {
                    intro.content.show();
                },
                leave: function(intro) {
                    this.when(intro.content.cfg.fxShow).emit('end').proceed('primary actions done');
                }
            }).describe('primary actions done', {
                main: function(intro) {
                    intro.cfg.mainActionsDone = true;
                    intro.startAutoplay();
                },
                leave: function(intro) {
                    var cfg = intro.packshot.cfg;
                    if(cfg.isLoading && cfg.wasFirstInDom) {
                        this.when(intro.packshot).emit('complete').proceed('show packshot');
                    } else {
                        this.proceed(intro.packshot.cfg.packshotElm ? 'manage packshot' : 'allow interactions');
                    }
                }
            }).describe('manage packshot', {
                enter: function(intro) {
                    this.proceed(intro.cfg.mainActionsDone && !intro.packshot.cfg.isLoading ? 'show packshot' : 'wait');
                },
                main: function() {},
                leave: 'show packshot'
            }).describe('show packshot', {
                enter: function(intro) {
                    this.proceed(intro.packshot.cfg.isError ? 'allow interactions' : 'main');
                },
                main: function(intro) {
                    intro.packshot.show(intro.nextData.packshot ? intro.visual.getSize() : null);
                },
                leave: function(intro) {
                    if(intro.packshot.cfg.isFilter) {
                        this.proceed('allow interactions');
                    } else {
                        this.when(intro.packshot.cfg.fxShow).emit('end').proceed('allow interactions');
                    }
                }
            }).describe('allow interactions', {
                main: function(intro) {
                    intro.mask.setUnderContent();
                },
                leave: 'exit'
            }).freeze();
        }
    };
});

box.define('kerastase/ui/introMask', function(require, module) {
    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/element' ],

        DEFAULTS: {
            fxShowDuration: 500,
            fxHideDuration: 750
        },

        setup: function(cfg) {
            this.callModule('ui/element@setup', cfg);
            this._cfgFx(cfg);
        },

        _cfgFx: function(cfg) {
            var fxCss = require('fx/css');
            cfg.fxShow = fxCss.create({
                $elm: cfg.$core,
                reverseAtEnd: false,
                fx: [ { type: 'cssOpacity', from: 0, to: 1, duration: cfg.fxShowDuration } ]
            });
            cfg.fxHide = fxCss.create({
                $elm: cfg.$core,
                reverseAtEnd: false,
                fx: [ { type: 'cssOpacity', from: 1, to: 0, duration: cfg.fxHideDuration } ]
            });
        },

        setUnderContent: function() {
            this.cfg.$core.addClass('intro-visual-mask-under');
        },

        show: function() {
            var cfg = this.cfg;
            cfg.$core.removeClass('intro-visual-mask-under');
            cfg.fxShow.reset().play();
        },

        hide: function() {
            this.cfg.fxHide.reset().play();
        }
    });
});

box.define('kerastase/ui/introVisual', function(require, module) {
    var bind = require('function/bind');

    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/element' ],

        DEFAULTS: {
            objectFit: 'none'
        },

        setup: function(cfg) {
            this.callModule('ui/element@setup', cfg);
            if(cfg.image) {
                this.load(cfg.image);
            }
        },

        load: function(src) {
            if(!src || typeof src !== 'string') {
                throw new Error('cannot preload image as src is missing');
            }
            var cfg = this.cfg;
            if(!cfg.isLoading) {
                cfg.isLoading = true;
                cfg.isError = false;
                var img = cfg.visualElm = document.createElement('img');
                cfg.$core.empty().append(img);
                img.onload = bind(this._loadComplete, this);
                img.onerror = bind(this._loadFail, this);
                img.src = src;
            }
        },

        _loadComplete: function() {
            var cfg = this.cfg;
            cfg.isLoading = false;
            cfg.visualElm.onload = cfg.visualElm.onerror = null;
            cfg.naturalWidth = cfg.visualElm.width;
            cfg.naturalHeight = cfg.visualElm.height;
            this.position();
            this.dispatch('complete', { success: true });
        },

        _loadFail: function() {
            var cfg = this.cfg;
            cfg.isLoading = false;
            cfg.isError = true;
            cfg.visualElm.onload = cfg.visualElm.onerror = null;
            cfg.naturalWidth = 0;
            cfg.naturalHeight = 0;
            this.dispatch('complete', { success: false });
        },

        position: function() {
            var cfg = this.cfg;
            if(!cfg.isLoading) {
                var coreWidth = cfg.$core.outerWidth();
                var coreHeight = cfg.$core.outerHeight();
                if(cfg.objectFit === 'none') {
                    cfg.visualElm.height = cfg.naturalHeight;
                    cfg.visualElm.style.top = Math.round((coreHeight - cfg.naturalHeight) / 2) + 'px';
                    cfg.visualElm.style.left = Math.round((coreWidth - cfg.naturalWidth) / 2) + 'px';
                } else if(cfg.objectFit === 'containY') {
                    cfg.visualElm.height = coreHeight;
                    cfg.visualElm.style.top = '0px';
                    cfg.visualElm.style.left = Math.round((coreWidth - cfg.visualElm.width) / 2) + 'px';
                }
            }
        },

        getSize: function() {
            var cfg = this.cfg;
            if(cfg.visualElm && !cfg.isLoading) {
                return {
                    naturalWidth: cfg.naturalWidth,
                    naturalHeight: cfg.naturalHeight,
                    width: cfg.visualElm.width,
                    height: cfg.visualElm.height
                };
            }
            return null;
        },

        setHeight: function(height) {
            this.cfg.$core.css({ height: height, marginBottom: -height });
        }
    });
});

box.define('kerastase/ui/introPackshot', function(require, module) {
    var bind = require('function/bind');

    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/element' ],

        DEFAULTS: {
            coreHtml: '<div class="intro-pack"></div>',
            fxShowDuration: 1200,
            fxHideDuration: 300,
            isLoading: false,
            isError: false,
            isFilter: require('fx/cssOpacity').Component.prototype.IS_FILTER
        },

        setup: function(cfg) {
            this.callModule('ui/element@setup', cfg);
            this._cfgLoading(cfg);
            this._cfgPackshot(cfg);
        },

        _cfgLoading: function(cfg) {
            if(cfg.isInDom) {
                var imgElm = cfg.$core.find('img')[0],
                    $imgElm = $j(imgElm);
                if(imgElm) {
                    cfg.wasFirstInDom = true;
                    cfg.packshotElm = imgElm;
                    if(!imgElm.complete || imgElm.height === 0) {
                        $imgElm.attr('src',$imgElm.attr('src')+ "?" + new Date().getTime()); //bug IE : if img is in cache, load evt doesnt fire
                        cfg.isLoading = true;
                        imgElm.onload = bind(this._loadComplete, this);
                    }else{
                        //if img already loaded
                        this._loadComplete();
                    }
                }
            }
        },

        _cfgPackshot: function(cfg) {
            if(cfg.isInDom) {
                // packshot is a PNG, so avoid animating when dealing with
                // IE with filters (v8) as it would display
                // a black background on the image
                if(cfg.isFilter) {
                    cfg.$core.css('visibility', 'hidden');
                } else {
                    this._cfgCore(cfg);
                    this._cfgFx(cfg);
                }
            }
        },

        _cfgCore: function(cfg) {
            cfg.$core.css('opacity', '0');
        },

        _cfgFx: function(cfg) {
            var fxCss = require('fx/css');
            cfg.fxShow = fxCss.create({
                $elm: cfg.$core,
                reverseAtEnd: false,
                fx: [ { type: 'cssOpacity', from: 0, to: 1, duration: cfg.fxShowDuration } ]
            });
            cfg.fxHide = fxCss.create({
                $elm: cfg.$core,
                reverseAtEnd: false,
                fx: [ { type: 'cssOpacity', from: 1, to: 0, duration: cfg.fxHideDuration } ]
            });
        },

        load: function(src) {
            var cfg = this.cfg;
            if(!cfg.isLoading) {
                cfg.isLoading = true;
                cfg.isError = false;
                if(!cfg.isInDom) {
                    this.insert();
                    this._cfgPackshot(cfg);
                } else {
                    this.empty();
                }
                var imgElm = cfg.packshotElm = document.createElement('img');
                cfg.$core.append(imgElm);
                imgElm.onload = bind(this._loadComplete, this);
                imgElm.onerror = bind(this._loadError, this);
                imgElm.src = src;
            }
        },

        _loadComplete: function() {
            var cfg = this.cfg;
            cfg.isLoading = false;
            cfg.isError = false;
            cfg.naturalWidth = cfg.packshotElm.width;
            cfg.naturalHeight = cfg.packshotElm.height;
            cfg.packshotElm.onload = cfg.packshotElm.onerror = null;
            this.dispatch('complete', { success: true });
        },

        _loadError: function() {
            var cfg = this.cfg;
            cfg.isLoading = false;
            cfg.isError = true;
            cfg.naturalWidth = cfg.naturalHeight = 0;
            cfg.packshotElm.onload = cfg.packshotElm.onerror = null;
            this.dispatch('complete', { success: false });
        },

        empty: function() {
            var cfg = this.cfg;
            if(cfg.isInDom && !cfg.wasFirstInDom) {
                if(cfg.isFilter) {
                    cfg.$core.empty().css('visibility', 'hidden');
                } else {
                    cfg.$core.empty().css('opacity', '0');
                }
                cfg.packshotElm = null;
            }
        },

        setSize: function(visualSize) {
            var cfg = this.cfg;

            if(cfg.isInDom && !cfg.isLoading && !cfg.isError) {
                cfg.packshotElm.height = cfg.naturalHeight * visualSize.height / visualSize.naturalHeight;
            }
        },

        show: function(visualSize) {
            var cfg = this.cfg;
            if(cfg.isInDom) {
                if(visualSize) {
                    this.setSize(visualSize);
                }
                if(cfg.fxShow) {
                    cfg.fxShow.reset().play();
                } else {
                    cfg.$core.css('visibility', 'visible');
                }
            }
        },

        hide: function() {
            var cfg = this.cfg;
            if(cfg.isInDom) {
                if(cfg.fxHide) {
                    cfg.fxHide.reset().play();
                } else {
                    cfg.$core.css('visibility', 'hidden');
                }
            }
        },

        showImmediatly: function() {
            var cfg = this.cfg;
            if(cfg.isInDom) {
                cfg.$core.css('visibility', 'visible');
            }
        },

        hideImmediatly: function() {
            var cfg = this.cfg;
            if(cfg.isInDom) {
                cfg.$core.css('visibility', 'hidden');
            }
        },

        move: function(moveBy) {
            if(moveBy) {
                var $core = this.cfg.$core;
                var offsetRight = parseInt($core.css('right'), 10);
                $core.css('right', (moveBy + offsetRight) + 'px');
            }
        }
    });
});

box.define('kerastase/ui/introContent', function(require, module) {
    module.exports = {
        create: function(cfg) {
            var $slides = cfg.$core.find('.intro-slideshow-listing');
            if($slides.length) {
                return this._createSlideshow(cfg, $slides);
            }
            return require('kerastase/ui/introContentBasic').create(cfg);
        },

        _createSlideshow: function(cfg, $slides) {
            cfg.$animate = cfg.$core.find('.intro-content-animate');
            cfg.$content = cfg.$core;
            cfg.$core = $slides;
            cfg.model = { items: '.intro-slideshow-item' };
            cfg.pagination = {
                perPage: 1,
                circular: true,
                insertMethod: 'insertAfter'
            };
            return require('kerastase/ui/introContentSlideshow').create(cfg);
        }
    };
});

box.define('kerastase/ui/introContentBasic', function(require, module) {
    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/element' ],

        DEFAULTS: {
            fxShowDuration: 1000
        },

        setup: function(cfg) {
            this.callModule('ui/element@setup', cfg);
            this._cfgAnimate(cfg);
        },

        _cfgAnimate: function(cfg) {
            cfg.$animate = cfg.$core.find('.intro-content-animate');
        },

        setHeight: function(height) {
            this.cfg.$core.height(height);
        },

        isAnimatable: function() {
            return this.cfg.$animate.length === 1;
        },

        show: function() {
            var cfg = this.cfg;
            cfg.fxShow = require('fx/css').create({
                $elm: cfg.$animate,
                reverseAtEnd: false,
                fx: [ { type: 'cssHeight', from: 0, to: cfg.$animate[0].scrollHeight, duration: cfg.fxShowDuration } ]
            });
            cfg.fxShow.play();
        },

        hide: function() {
            this.cfg.$animate.height(0);
        },

        resize: function() {
            var $content = this.cfg.$core.find('.intro-content');
            var $title = $content.find('.h-tgs');
            var titleElm = $title[0];
            if(titleElm) {
                var scrollWidth = titleElm.scrollWidth;
                var difference = scrollWidth - titleElm.offsetWidth;
                if(difference > 0) {
                    $title.parents('.intro-content').animate({'width': scrollWidth});
                    return difference;
                }
            }
            return 0;
        }
    });
});

box.define('kerastase/ui/introContentSlideshow', function(require, module) {
    var isObject = require('lang/isObject');

    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/listing' ],

        DEFAULTS: {
            fxShowDuration: 1000
        },

        setup: function(cfg) {
            this.callModule('ui/listing@setup', cfg);
        },

        _cfgPagination: function(cfg) {
            if(isObject(cfg.pagination)) {
                var cfgPagination = cfg.pagination;
                var insertMethod = cfgPagination.insertMethod || 'appendTo';
                if(!cfgPagination.$target) {
                    cfgPagination.$target = cfg.$core;
                }
                if(cfgPagination.$target[0] === cfg.model.$core[0] && cfg.reRiskyInsertMethod.test(insertMethod)) {
                    throw new Error('the listing pagination will be erased with the current config (same insertion target)');
                }
                if(!cfgPagination.listeners) {
                    cfgPagination.listeners = require('events/listeners').create();
                }
                cfgPagination.listeners.add('beforeChange', this, '_handleBeforePageChange').add('change', this, '_handlePageChange');
                cfgPagination.total = this.model.dataLive.length;
                this._createPagination(cfgPagination);
            }
        },

        _handleBeforePageChange: function(evt) {
            var cfg = this.cfg;
            if(!cfg.requestPage) {
                evt.preventDefault();
                cfg.requestPage = evt.details.page;
                this.dispatch('beforeChange', {
                    itemData: this.model.dataLive[evt.details.page - 1]
                });
            }
        },

        _handlePageChange: function(evt) {
            var offset = evt.source.getOffset();
            var html = this.model.getHtml(offset.from, offset.to);
            this.model.cfg.$core.html(html);
            this.dispatch('change');
        },

        confirmPageRequest: function() {
            var cfg = this.cfg;
            var requestPage = cfg.requestPage;
            if(requestPage) {
                this.nth(requestPage);
                cfg.requestPage = null;
            }
        },

        setHeight: function(height) {
            this.cfg.$content.height(height);
        },

        isAnimatable: function() {
            return this.cfg.$animate.length === 1;
        },

        show: function() {
            var cfg = this.cfg;
            cfg.fxShow = require('fx/css').create({
                $elm: cfg.$animate,
                reverseAtEnd: false,
                fx: [ { type: 'cssHeight', from: 0, to: cfg.$animate[0].scrollHeight, duration: cfg.fxShowDuration } ]
            });
            cfg.fxShow.play();
        },

        hide: function() {
            this.cfg.$animate.height(0);
        }
    });
});

box.define('kerastase/ui/introVideo', function(require, module) {
    var l10n = window.l10n ? window.l10n : { videoPlayer: {} };

    module.exports = require('component/factory').create({
        MIXINS: [ 'ui/element' ],

        DEFAULTS: {
            coreHtml: (
            '<div class="intro-video" style="top:{$paddingTop};">' +
            '<div class="btn btn-popin-close">' +
            '<button type="button" data-boxjs="onclick:{$module}@_handleClick; action:close;">' +
            '<span class="i"></span>' +
            '<span class="offscreen">' + l10n.close + '</span>' +
            '</button>' +
            '</div>' +
            '</div>'
            ),
            linkHtml: (
            '<a href="#" class="btn btn-video" data-boxjs="onclick:{$module}@_handleClick; action:launch;">' +
            '<span class="i"></span>' +
            '<span class="btn-video-label h-tnr">' + l10n.videoPlayer.launch + '</span>' +
            '</a>'
            ),
            closeBtnOnItsOwnLineClass: 'intro-video-btn-own-line',
            reIphoneIpod: /ip(?:hone|od)/i
        },

        setup: function(cfg) {
            this.callModule('ui/element@setup', cfg);
            cfg.delegateId = require('dom/evtDelegateManager').registerModule(this);
            cfg.coreHtml = cfg.coreHtml.replace('{$module}', cfg.delegateId).replace('{$paddingTop}', cfg.insertTarget.css('paddingTop'));
            cfg.linkHtml = cfg.linkHtml.replace('{$module}', cfg.delegateId);
        },

        addLink: function(data) {
            this.data = data;
            if(!this.link) {
                var cfg = this.cfg;
                this.link = require('ui/element').create({
                    coreHtml: cfg.linkHtml,
                    insertTarget: cfg.insertTarget.find('.wrapper')
                }).insert();
            }
        },

        removeLink: function() {
            if(this.link) {
                this.link.remove();
                this.link = null;
            }
        },

        addVideo: function() {
            if(!this.cfg.isInDom) {
                this.insert();
                this.addMediaPlayer();
                this.dispatch('addVideo');
            }
        },

        removeVideo: function(settings) {
            if(this.cfg.isInDom) {
                this.removeMediaPlayer();
                this.remove();
                this.dispatch('removeVideo', { fromEnd: settings && settings.fromEnd === true });
            }
        },

        addMediaPlayer: function() {
            var $core = this.cfg.$core;
            var height = $core.height();
            if(this.isIphoneOrIpod()) {
                $core.addClass(this.cfg.closeBtnOnItsOwnLineClass);
                height -= parseInt($core.css('paddingTop'), 10);
            }
            var listeners = require('events/listeners').create();
            listeners
                .add('play', this, '_handlePlay')
                .add('pause', this, '_handlePause')
                .add('ended', this, '_handleEnded');
            this.player = require('ui/mediaplayer').create({
                listeners: listeners,
                insertTarget: $core,
                width: $core.width(),
                height: height,
                type: 'video',
                playlist: [
                    { media: this.data.videoFile, poster: this.data.videoPoster }
                ],
                flashSwfPath: '/skin/frontend/kerastase/default/flash/player.swf',
                flashConfigXmlPath:'/skin/frontend/kerastase/default/flash/config.xml',
                controls: 'play seek elapsed mute fullscreen',
                controlsFxShow: [ { type: 'cssOpacity', to: 1, duration: 500 } ],
                controlsFxHide: [ { type: 'cssOpacity', to: 0, delay: 500, duration: 1000 } ],
                autoplay: true,
                l10n: l10n.videoPlayer
            });
        },

        removeMediaPlayer: function() {
            if(this.player) {
                this.player.off('play').off('pause').off('ended').cleanup();
                this.player = null;
            }
        },

        isIphoneOrIpod: function() {
            return this.cfg.reIphoneIpod.test(navigator.userAgent);
        },

        setHeight: function() {
            if(this.player) {
                var $core = this.cfg.$core;
                var mediaElm = this.player.getMediaElm();
                if(this.isIphoneOrIpod()) {
                    mediaElm.height = $core.height() - parseInt($core.css('paddingTop'), 10);
                } else {
                    mediaElm.height = $core.height();
                }
                mediaElm.width = $core.width();
            }
        },

        _handleClick: function(data) {
            data.event.preventDefault();
            if(data.action === 'launch') {
                this.addVideo();
            } else if(data.action === 'close') {
                this.removeVideo();
            }
        },

        _handlePlay: function() {
            this._trackEvent('Play');
        },

        _handlePause: function() {
            this._trackEvent('Pause');
        },

        _handleEnded: function() {
            this._trackEvent('End');
            this.removeVideo({ fromEnd: true });
        },

        _trackEvent: function(name) {
            require('kerastase/ga').trackSomething({
                category: 'Videos',
                action: name,
                label: '{$page}/' + this.data.videoLabel
            });
        }
    });
});

// END for file : /assets/kerastase/js/global/intro.js
