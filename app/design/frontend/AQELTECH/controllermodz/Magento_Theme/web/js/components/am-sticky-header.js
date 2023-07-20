/**
 *   Sticky header behavior
 */

 define([
    'jquery',
    'matchMedia',
    'domReady!'
], function ($, mediaCheck) {
    'use strict';

    $.widget('mage.amStickyHeader', {
        options: {
            classes: {
                navToggle: '-desktop-navigation-toggle',
                show: '-show',
                sticky: '-sticky-header'
            },
            headerHeight: 0,
            isDisplayOnScrollDown: false,
            hideOnScrollDown: true,
            hideOnScrollDownOffset: null,
            isAlwaysDisplay: false,
            isDesktop: false,
            isDisplayOnScrollUp: false,
            isStickyHeaderEnabled: false,
            mediaBreakpoint: '(min-width: 768px)',
            showDesktopNavToggle: true,
            selectors: {
                headerElement: null,
                navSection: '.sections.nav-sections',
                pageContentElement: '.page-wrapper',
                toggleNavSelector: '[data-action="toggle-nav"]'
            }
        },
        lastPageYOffset: window.pageYOffset,

        /**
         * Widget initialization
         * @private
         * @returns {Object}
         */
        _create: function () {
            if (this.options.isStickyHeaderEnabled) {
                this.pageContentElement = $(this.options.selectors.pageContentElement);
                this._scrollOffset = 0;
                this.isHeaderShown = false;
                this.pageContentElementPaddingTop = this.pageContentElement.css('padding-top');
                this.headerElement = this.options.selectors.headerElement
                    ? $(this.options.selectors.headerElement)
                    : this.element;

                this._isVisibleOnScrollDown();
                this._initResponsive();
                this._setHeaderHeight();
                this._initListeners();
            }

            return this;
        },

        /**
         * @returns {void}
         */
        _initResponsive: function () {
            var self = this;

            mediaCheck({
                media: self.options.mediaBreakpoint,
                entry: function () {
                    self.isDesktop = true;
                },
                exit: function () {
                    self.isDesktop = false;
                }
            });
        },

        /**
         * @returns {void}
         */
        _initListeners: function () {
            $(window).on('scroll resize', this._handleHeaderOnScroll.bind(this));
        },

        /**
         * Sticky header handler on scroll event
         * @returns {void}
         */
        _handleHeaderOnScroll: function () {
            var pageYOffset = window.pageYOffset;

            if (pageYOffset <= this.headerHeight) {
                this.lastPageYOffset = 0;
                this._destroyStickyMenu();
                this._setHeaderHeight();
            } else {
                this._appendStickyMenu();

                if (this.options.hideOnScrollDown) {
                    this._showHideOnScroll(pageYOffset);
                } else if (!this.isHeaderShown && !this.options.hideOnScrollDown) {
                    this.showHeader();
                }

                this.lastPageYOffset = pageYOffset;
            }
        },

        /**
         * Handle sticky header based on 'isDisplayOnScrollUp' config
         * @param {Number} currentPageOffset
         * @returns {void}
         */
        _showHideOnScroll: function (currentPageOffset) {
            if (currentPageOffset > this.lastPageYOffset && this.options.isDisplayOnScrollDown) {
                this._getPositionOffsetToHide(currentPageOffset);

                // eslint-disable-next-line no-unused-expressions
                currentPageOffset > this._scrollDownOffset ? this.hideHeader() : this.displayOnScroll();

                return;
            }

            if (currentPageOffset < this.lastPageYOffset) {
                // eslint-disable-next-line no-unused-expressions
                this.options.isDisplayOnScrollUp ? this.displayOnScroll() : this.hideHeader();
            }
        },

        /**
         * Check if sticky header should be hidden on scroll down
         * @returns {void}
         */
        _isVisibleOnScrollDown: function () {
            if (this.options.isAlwaysDisplay) {
                this.options.hideOnScrollDown = false;
            }
        },

        /**
         * Get document offset when header should be hide
         * @param {Number} currentPageOffset
         * @returns {void}
         */
        _getPositionOffsetToHide: function (currentPageOffset) {
            if (!this.options.hideOnScrollDownOffset) {
                this._scrollDownOffset = currentPageOffset;
            } else {
                this._scrollDownOffset = this._calcOffsetPosition();
            }
        },

        /**
         * Calculate offset
         * @returns {*}
         * */
        _calcOffsetPosition: function () {
            var body = document.body,
                html = document.documentElement,
                // eslint-disable-next-line max-len
                docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);

            return (Number.parseInt(this.options.hideOnScrollDownOffset, 10) * docHeight) / 100;
        },

        /**
         * Set height when the sticky header should be triggered
         * @returns {void}
         */
        _setHeaderHeight: function () {
            this.headerHeight = $(this.headerElement).outerHeight();
        },

        /**
         * @returns {void}
         */
        _showDesktopMenu: function () {
            this.headerElement.removeClass(this.options.classes.navToggle);
        },

        /**
         * @returns {void}
         */
        _hideDesktopMenu: function () {
            var $html = $('html');

            this.headerElement.addClass(this.options.classes.navToggle);

            if ($html.hasClass('nav-open')) {
                $html.removeClass('nav-open nav-before-open');
            }
        },

        /**
         * Show sticky header on scroll up
         * @returns {void}
         */
        displayOnScroll: function () {
            if (!this.isHeaderShown) {
                this.showHeader();
            }
        },

        /**
         * Destroy sticky header & return initial state
         * @returns {void}
         */
        _destroyStickyMenu: function () {
            this.headerElement.removeClass(this.options.classes.sticky);
            this.pageContentElement.css('padding-top', this.pageContentElementPaddingTop);
            this._showDesktopMenu();
            this.hideHeader();
        },

        /**
         * Initialize sticky header
         * @returns {void}
         */
        _appendStickyMenu: function () {
            if (!this.headerElement.hasClass(this.options.classes.sticky)) {
                this.pageContentElement.css('padding-top', this.headerHeight);
                this.headerElement.addClass(this.options.classes.sticky);

                if (this.options.showDesktopNavToggle && this.isDesktop) {
                    this._hideDesktopMenu();
                }
            }
        },

        /**
         * @returns {void}
         */
        showHeader: function () {
            this.headerElement.addClass(this.options.classes.show);
            this.isHeaderShown = true;
        },

        /**
         * @returns {void}
         */
        hideHeader: function () {
            this.headerElement.removeClass(this.options.classes.show);
            this.isHeaderShown = false;
            this.hideOnScrollDownOffset = null;
        }
    });

    return $.mage.amStickyHeader;
});
