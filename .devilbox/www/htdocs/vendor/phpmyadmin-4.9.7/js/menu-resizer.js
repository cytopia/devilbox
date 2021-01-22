/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Handles the resizing of a menu according to the available screen width
 *
 * Uses themes/original/css/resizable-menu.css.php
 *
 * To initialise:
 * $('#myMenu').menuResizer(function () {
 *     // This function will be called to find out how much
 *     // available horizontal space there is for the menu
 *     return $('body').width() - 5; // Some extra margin for good measure
 * });
 *
 * To trigger a resize operation:
 * $('#myMenu').menuResizer('resize'); // Bind this to $(window).resize()
 *
 * To restore the menu to a state like before it was initialized:
 * $('#myMenu').menuResizer('destroy');
 *
 * @package PhpMyAdmin
 */
(function ($) {
    function MenuResizer ($container, widthCalculator) {
        var self = this;
        self.$container = $container;
        self.widthCalculator = widthCalculator;
        var windowWidth = $(window).width();

        if (windowWidth < 768) {
            $('#pma_navigation_resizer').css({ 'width': '0px' });
        }
        // Sets the image for the left and right scroll indicator
        $('.scrollindicator--left').html($(PMA_getImage('b_left').toString()));
        $('.scrollindicator--right').html($(PMA_getImage('b_right').toString()));

        // Set the width of the navigation bar without scroll indicator
        $('.navigationbar').css({ 'width': widthCalculator.call($container) - 60 });

        // Scroll the navigation bar on click
        $('.scrollindicator--right').on('click', function () {
            $('.navigationbar').scrollLeft($('.navigationbar').scrollLeft() + 70);
        });
        $('.scrollindicator--left').on('click', function () {
            $('.navigationbar').scrollLeft($('.navigationbar').scrollLeft() - 70);
        });

        // create submenu container
        var link = $('<a />', { href: '#', 'class': 'tab nowrap' })
            .text(PMA_messages.strMore)
            .on('click', false); // same as event.preventDefault()
        var img = $container.find('li img');
        if (img.length) {
            $(PMA_getImage('b_more').toString()).prependTo(link);
        }
        var $submenu = $('<li />', { 'class': 'submenu' })
            .append(link)
            .append($('<ul />'))
            .on('mouseenter', function () {
                if ($(this).find('ul .tabactive').length === 0) {
                    $(this)
                        .addClass('submenuhover')
                        .find('> a')
                        .addClass('tabactive');
                }
            })
            .on('mouseleave', function () {
                if ($(this).find('ul .tabactive').length === 0) {
                    $(this)
                        .removeClass('submenuhover')
                        .find('> a')
                        .removeClass('tabactive');
                }
            });
        $container.children('.clearfloat').remove();
        $container.append($submenu).append('<div class=\'clearfloat\'></div>');
        setTimeout(function () {
            self.resize();
        }, 4);
    }
    MenuResizer.prototype.resize = function () {
        var wmax = this.widthCalculator.call(this.$container);
        var windowWidth = $(window).width();
        var $submenu = this.$container.find('.submenu:last');
        var submenu_w = $submenu.outerWidth(true);
        var $submenu_ul = $submenu.find('ul');
        var $li = this.$container.find('> li');
        var $li2 = $submenu_ul.find('li');
        var more_shown = $li2.length > 0;
        // Calculate the total width used by all the shown tabs
        var total_len = more_shown ? submenu_w : 0;
        var l = $li.length - 1;
        var i;
        for (i = 0; i < l; i++) {
            total_len += $($li[i]).outerWidth(true);
        }

        var hasVScroll = document.body.scrollHeight > document.body.clientHeight;
        if (hasVScroll) {
            windowWidth += 15;
        }
        var navigationwidth = wmax;
        if (windowWidth < 768) {
            wmax = 2000;
        }

        // Now hide menu elements that don't fit into the menubar
        var hidden = false; // Whether we have hidden any tabs
        while (total_len >= wmax && --l >= 0) { // Process the tabs backwards
            hidden = true;
            var el = $($li[l]);
            var el_width = el.outerWidth(true);
            el.data('width', el_width);
            if (! more_shown) {
                total_len -= el_width;
                el.prependTo($submenu_ul);
                total_len += submenu_w;
                more_shown = true;
            } else {
                total_len -= el_width;
                el.prependTo($submenu_ul);
            }
        }
        // If we didn't hide any tabs, then there might be some space to show some
        if (! hidden) {
            // Show menu elements that do fit into the menubar
            for (i = 0, l = $li2.length; i < l; i++) {
                total_len += $($li2[i]).data('width');
                // item fits or (it is the last item
                // and it would fit if More got removed)
                if (total_len < wmax ||
                    (i === $li2.length - 1 && total_len - submenu_w < wmax)
                ) {
                    $($li2[i]).insertBefore($submenu);
                } else {
                    break;
                }
            }
        }
        // Show/hide the "More" tab as needed
        if (windowWidth < 768) {
            $('.navigationbar').css({ 'width': windowWidth - 80 - $('#pma_navigation').width() });
            $submenu.removeClass('shown');
            $('.navigationbar').css({ 'overflow': 'hidden' });
        } else {
            $('.navigationbar').css({ 'width': 'auto' });
            $('.navigationbar').css({ 'overflow': 'visible' });
            if ($submenu_ul.find('li').length > 0) {
                $submenu.addClass('shown');
            } else {
                $submenu.removeClass('shown');
            }
        }
        if (this.$container.find('> li').length === 1) {
            // If there is only the "More" tab left, then we need
            // to align the submenu to the left edge of the tab
            $submenu_ul.removeClass().addClass('only');
        } else {
            // Otherwise we align the submenu to the right edge of the tab
            $submenu_ul.removeClass().addClass('notonly');
        }
        if ($submenu.find('.tabactive').length) {
            $submenu
                .addClass('active')
                .find('> a')
                .removeClass('tab')
                .addClass('tabactive');
        } else {
            $submenu
                .removeClass('active')
                .find('> a')
                .addClass('tab')
                .removeClass('tabactive');
        }
    };
    MenuResizer.prototype.destroy = function () {
        var $submenu = this.$container.find('li.submenu').removeData();
        $submenu.find('li').appendTo(this.$container);
        $submenu.remove();
    };

    /** Public API */
    var methods = {
        init: function (widthCalculator) {
            return this.each(function () {
                var $this = $(this);
                if (! $this.data('menuResizer')) {
                    $this.data(
                        'menuResizer',
                        new MenuResizer($this, widthCalculator)
                    );
                }
            });
        },
        resize: function () {
            return this.each(function () {
                var self = $(this).data('menuResizer');
                if (self) {
                    self.resize();
                }
            });
        },
        destroy: function () {
            return this.each(function () {
                var self = $(this).data('menuResizer');
                if (self) {
                    self.destroy();
                }
            });
        }
    };

    /** Extend jQuery */
    $.fn.menuResizer = function (method) {
        if (methods[method]) {
            return methods[method].call(this);
        } else if (typeof method === 'function') {
            return methods.init.apply(this, [method]);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.menuResizer');
        }
    };
}(jQuery));
