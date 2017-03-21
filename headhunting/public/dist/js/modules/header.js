var theme = window.theme || {};
theme.HeaderModule = (function(window, $) {
    // public methods
    var init,

        // private methods
        _privateMethod,

        // properties
        property = null;
    _privateMethod = function() {
        // defined Selectors So that this will not get called on orientation change and remain in scope.
        var $headerCounter = $('header .container'),
            $headerListElements = $('header li > a'),
            $headerContainerWidthDependendents = $('header .navbar .navbar-nav, header .navbar .navbar-nav > .dropdown .dropdown-menu, header .navbar .navbar-nav > .dropdown .dropdown-menu .dropdown-menu-child'),
            $navbar = $('nav.navbar'),
            $thirdLevelContactTab = $('.dropdown-child.open .dropdown-menu-child ul .contact-tab'),
            $thirdLevelMenuItem = $(".dropdown-menu .dropdown.dropdown-child > a"),
            $homeBodyAndFooter = $('.ofBody, body > div > footer'),
            $firstLevelMenuItem = $('.navbar-nav > .dropdown > a'),
            $firstLevelMenu = $('.navbar-nav > .dropdown'),
            $secondLevelMenu = $(".dropdown-menu .dropdown.dropdown-child > a"),
            $secondLevelMenuItem = $('.navbar-nav .dropdown-child > a'),
            $hamburger = $('.navbar-hamburg'),
            $firstLevelFooter = $('.navbar > .contact-tab > footer'),
            $footer = $('body > div > footer'),
            $firstLevelContactTab = $('.navbar > .contact-tab'),
            $firstLevelChildMenu = $('.navbar-nav .dropdown-child'),
            $searchContainer = $('.search-container > .search-icon'),
            $topHeaderWrapper = $('.top-header-wrapper'),
            $cancleSearchBtn = $('.btn-cancel'),
            $firstLevelFirstChild = $('.navbar-nav > .dropdown:first-child'),
            $thirdLevelCloseTag = $('.navbar-nav .dropdown-child > .dropdown-menu-child .close-me');

        if ($firstLevelFooter.length === 0 && $footer.length > 0 && !$('#authorcheck').length) {
            $firstLevelContactTab.append($footer[0].outerHTML);
        } 
        /**
         *  @method: LargeScreenJS
         *  @description : To apply events on navbar Menu.
         */
        var largeScreenJS = function() {
            var headerPadding = ($(window).width() - $headerCounter.innerWidth()) / 2;
            $headerContainerWidthDependendents.css({
                'padding-left': headerPadding + 'px',
                'padding-right': headerPadding + 'px'
            });
            $navbar.removeClass('pos-abs').addClass('hidden-moblet');

            if (!$firstLevelMenu.hasClass('open')) {
                $firstLevelFirstChild.addClass('open');
            }

            if ($thirdLevelContactTab.length > 0) {
                $thirdLevelContactTab.remove();
            }
            $thirdLevelMenuItem.off();
            $homeBodyAndFooter.show();
            $firstLevelMenuItem.off();

            $firstLevelMenu.off().click(function(e) {
                var $this = $(this);
                if ($this.is('a')) {
                    $this = $this.parent();
                }
                $this.siblings().removeClass('open').children('.dropdown-menu').find('.open').removeClass('open');
                $this.children().removeClass('open');
                $this.addClass('open');
                e.stopPropagation();
            });

            $secondLevelMenu.off().click(function(e) {
                e.preventDefault();
                var $this = $(this),
                a = e.target;
                if (!$(a).hasClass('dropdown-menu-child') &&
                    !$(a).parents('dropdown-menu-child').length > 0) {
                    $this.parent().siblings().removeClass('open');
                    $this.parent().toggleClass('open');
                }
                e.stopPropagation();
            });
            $(document).on("click",":not('.dropdown.dropdown-child.open .dropdown-child')",function(){
                $('.dropdown.dropdown-child.open').removeClass("open");
            });
        };
        

        /**
         *  @method: smallScreenJS
         *  @description : To apply events on navbar Menu in mobile and ipad potrait.
         */
        var smallScreenJS = function() {
            $secondLevelMenu.off();
            $firstLevelMenu.off();
            if ($navbar.hasClass('hidden-moblet')) {
                $hamburger.removeClass('open');
            }
            if ($thirdLevelContactTab.length > 0) {
                $thirdLevelContactTab.remove();
            }
            $hamburger.off().click(function(e) {
                e.preventDefault();
                var $this = $(this);
                $this.toggleClass('open');
                if ($this.hasClass('open')) {
                    $navbar.removeClass('hidden-moblet').removeClass('pos-abs');
                    $firstLevelChildMenu.removeClass('open');
                    $homeBodyAndFooter.hide();
                    $firstLevelMenu.each(function(){
                        if($(this).hasClass('open')){
                            $(this).removeClass("open");
                        }
                    });
                } else {
                    $navbar.addClass('hidden-moblet');
                    $homeBodyAndFooter.show();
                }
            });

            $searchContainer.off().click(function(e) {
                e.preventDefault();
                var $this = $(this);
                $this.parent().addClass('open');
                $topHeaderWrapper.addClass('open-search');
                e.stopPropagation();
            });

            $cancleSearchBtn.off().click(function(e) {
                e.preventDefault();
                $searchContainer.removeClass('open');
                $topHeaderWrapper.removeClass('open-search');
                e.stopPropagation();
            });

            $firstLevelMenuItem.off().click(function(e) {
                e.preventDefault();
                var $this = $(this);
                $this = $this.parent();
                if (!$this.hasClass('open')) {
                    $firstLevelMenu.removeClass('open');
                }
                $this.toggleClass('open');
                $("html,body,document").animate({ scrollTop: $this.offset().top }, "slow");

                e.stopPropagation();
            });

            $secondLevelMenuItem.off().click(function(e) {
                e.preventDefault();
                $("html,body,document").animate({ scrollTop: 100 }, "slow");
                var $this = $(this);
                $this.parent().addClass('open');
                $this.parents('nav').addClass('pos-abs');
                e.stopPropagation();
                if ($this.parent().find('.dropdown-menu-child ul .contact-tab').length === 0) {
                    $this.parent().find('.dropdown-menu-child ul:last-child').append($('.contact-tab')[0].outerHTML);
                }
            });

            $thirdLevelCloseTag.off().click(function(e) {


                e.preventDefault();
                var $this = $(this);
                $this.parents('.dropdown-child').removeClass('open');
                $this.parents('nav').removeClass('pos-abs');
                if ($this.parent().find('.dropdown-menu-child ul .contact-tab').length > 0) {
                    $this.parent().find('.dropdown-menu-child ul .contact-tab').remove();
                }
                e.stopPropagation();
            });
        };

        function sharedStart(array) {
            var A = array.concat().sort(),
                a1 = A[0],
                a2 = A[A.length - 1],
                L = a1.length,
                i = 0;
            while (i < L && a1.charAt(i) === a2.charAt(i)){ 
                i++ ;
            }
            return a1.substring(0, i);
        }
        var activateElement = function() {
            var pathName = window.location.pathname,
                isEqual = false;
            $headerListElements.each(function(key, obj) {
                var $currentLocation, $this = $(obj);
                if($(obj).attr("href")){
                    $currentLocation = $this.attr('href').trim().split('000')[1];
                }else{
                    $currentLocation = "";
                }
                if ($currentLocation !== "#" &&
                    $currentLocation === pathName.trim()) {
                    isEqual = true;
                    var $secondLevelMenu = $this.parents('.dropdown-child'),
                        $firstLevelMenu = $this.parents('.dropdown-menu');
                    if ($this.parents('.logout-container').length || $this.parents('li').hasClass('btn') || $this.parents('.login-container').length) {
                        // Do Nothing
                    } else {
                        $this.parent().addClass('active');
                        if ($secondLevelMenu.length > 0) {
                            $secondLevelMenu.addClass('active');
                        }
                        if ($firstLevelMenu.length > 0) {
                            $firstLevelMenu.prev().click();
                        }
                        return false;
                    }
                }
            });
            if (!isEqual) {
                $("footer").find(".theme-link").each(function(){
                   var pathname = window.location.pathname;
                   if($(this).attr('href').trim() === pathname){
                      $(this).css("color","#fff");
                      return false;
                   }
                });
                var setLink, $secondLevelMenu, $firstLevelMenu, maxCommonLength = 0;
                $headerListElements.each(function(key, obj) {
                    var self = $(obj);
                    var temp = sharedStart([self.attr('href').trim(), pathName]).length;
                    if (temp > maxCommonLength) {
                        maxCommonLength = temp;
                        setLink = self;
                    }
                });
                if(setLink && setLink.parents('.dropdown-child')) {
                    $secondLevelMenu = setLink.parents('.dropdown-child'),
                    $firstLevelMenu = setLink.parents('.dropdown-menu');
                }
                if (setLink && (setLink.parents('.logout-container').length || setLink.parents('li').hasClass('btn') || setLink.parents('.login-container').length)) {
                        // Do Nothing
                } else if($secondLevelMenu != undefined) {
                    setLink.parent().addClass('active');
                    if ($secondLevelMenu.length > 0) {
                        $secondLevelMenu.addClass('active');
                    }
                    if ($firstLevelMenu.length > 0) {
                        $firstLevelMenu.prev().click();
                    }
                }
            }

            return false;
        };

        //if large screen
        if ($(window).width() >= 1024) {
            $(".dropdown:not('.logout-container'), .dropdown-menu,.dropdown:not('.logout-container') a").on('click', function() {
                if ($(window).width() >= 1024) {
                    $('.logout-container').trigger('click');
                }
            });
            largeScreenJS();
            activateElement();
        } else {
            //mobile navigation menu JS updates
            smallScreenJS();
        }

        $(window).resize(function() {

            if ($(window).width() >= 1024) {
                largeScreenJS();
            } else {
                //mobile navigation menu JS updates
                smallScreenJS();
            }
        });

        return property;
    };

    init = function() {
        return _privateMethod();
    };

    // Public API
    return {
        init: init
    };

}(this, jQuery));

jQuery(theme.HeaderModule.init());
