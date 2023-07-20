/**
 * Expand active submenu for mobile
 */

 define([
    'jquery',
    'matchMedia'
], function ($, mediaCheck) {
    'use strict';

    var mixin = {
        options: {
            mediaBreakpoint: '(max-width: 767px)',
            menuStaticWrapperClass: 'amtheme-menustatic-wrapper',
            selectors: {
                itemActiveSelector: 'li.level0.active, li.level0.has-active',
                activeAllCategorySelector: '.level0.active .ui-menu-item.all-category',
                allSelectorsForWrapper: '.header.content .logo, '
                    + '.header.content .amtheme-icons-container, '
                    + '.header.content .amtheme-navigation-icon'
            }
        },

        /**
         * Toggle.
         * @returns {void}
         */
        toggle: function () {
            var html = $('html');

            if (this._checkIsMobile()) {
                if (!html.hasClass('nav-open')) {
                    this._expandActiveSubmenu();
                    /** Disabled the wrapper from being included where it incasuplate
                     header > content elements i.e logo, essential icons and such **/
                    // this._wrapElement();
                } else {
                    // this._unwrapElement();
                }
            }

            this._super();
        },

        /**
         * Private
         * Check mobile device
         * @returns {boolean}
         */
        _checkIsMobile: function () {
            var self = this,
                isMobile = false;

            mediaCheck({
                media: self.options.mediaBreakpoint,
                entry: function () {
                    isMobile = true;
                },
                exit: function () {
                    isMobile = false;

                    if ($('.' + self.options.menuStaticWrapperClass).length) {
                        self._unwrapElement();
                    }
                }
            });

            return isMobile;
        },

        /**
         * Private
         * Expand active submenu.
         * @returns {void}
         */
        _expandActiveSubmenu: function () {
            var activeItemMenu = $(this.options.selectors.itemActiveSelector),
                activeAllCategory = $(this.options.selectors.activeAllCategorySelector);

            if (activeItemMenu.children('a').attr('aria-haspopup')) {
                activeItemMenu.trigger('click');
            }

            if (window.location.href.includes(activeAllCategory.children('a').attr('href'))) {
                activeAllCategory.addClass('active');
            }
        },

        /**
         * Private
         * Wrap elements.
         * @returns {void}
         */
        _wrapElement: function () {
            $(this.options.selectors.allSelectorsForWrapper)
                .wrapAll('<div class=" ' + this.options.menuStaticWrapperClass + ' ">');
        },

        /**
         * Private
         * Unwrap element.
         * @returns {void}
         */
        _unwrapElement: function () {
            $(this.options.selectors.allSelectorsForWrapper).unwrap();
        },

        /**
         * @inheritDoc
         * @returns {void}
         */
        _toggleMobileMode: function () {
            var self = this,
                subMenus;

            $(this.element).off('mouseenter mouseleave');
            this._on({

                /**
                 * @param {jQuery.Event} event
                 * @returns {void}
                 */
                'click .ui-menu-item:has(a)': function (event) {
                    var target;

                    event.preventDefault();
                    target = $(event.target).closest('.ui-menu-item');
                    target.get(0).scrollIntoView();

                    if (self.currentTarget && self.currentTarget[0] === target[0]) {
                        this.collapseAll(event, true);
                        self.currentTarget = null;
                    }

                    if (!target.hasClass('level-top') || !target.has('.ui-menu').length) {
                        window.location.href = target.find('> a').attr('href');
                    }

                    self.currentTarget = target;
                }
            });

            subMenus = this.element.find('.level-top');
            $.each(subMenus, $.proxy(function (index, item) {
                var category = $(item).find('> a span').not('.ui-menu-icon').text(),
                    categoryUrl = $(item).find('> a').attr('href'),
                    menu = $(item).find('> .ui-menu');

                this.categoryLink = $('<a>')
                    .attr('href', categoryUrl)
                    .text($.mage.__('All %1').replace('%1', category));

                this.categoryParent = $('<li>')
                    .addClass('ui-menu-item all-category')
                    .html(this.categoryLink);

                if (menu.find('.all-category').length === 0) {
                    menu.prepend(this.categoryParent);
                }
            }, this));
        }
    };

    return function (MobileMenu) {
        $.widget('mage.menu', MobileMenu.menu, mixin);

        return {
            menu: $.mage.menu,
            navigation: $.mage.navigation
        };
    };
});
