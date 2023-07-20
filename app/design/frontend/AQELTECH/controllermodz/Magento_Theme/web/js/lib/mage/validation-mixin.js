/**
 * Extend listenFormValidateHandler to add amCaret dependency
 */

 define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.validation', widget, {
            /**
             * @inheritDoc
             */
            listenFormValidateHandler: function (event, validation) {
                var firstActive = $(validation.errorList[0].element || []),
                    lastActive = $(validation.findLastActive()
                      // eslint-disable-next-line no-mixed-operators
                      || validation.errorList.length
                      // eslint-disable-next-line no-mixed-operators
                      && validation.errorList[0].element || []),
                    windowHeight = $(window).height(),
                    parent,
                    successList,
                    isAmCaret = $('.product-add-form.-amcaret-active').length;

                if (lastActive.is(':hidden')) {
                    parent = lastActive.parent();

                    $('html, body').animate({
                        scrollTop: parent.offset().top - windowHeight / 2
                    });
                }

                // ARIA (removing aria attributes if success)
                successList = validation.successList;

                if (successList.length) {
                    $.each(successList, function () {
                        $(this).removeAttr('aria-describedby').removeAttr('aria-invalid');
                    });
                }

                // Exclude focus on error for JetTheme product caret
                if (firstActive.length && !isAmCaret) {
                    $('body').stop().animate({
                        scrollTop: firstActive.offset().top - windowHeight / 2
                    });
                    firstActive.focus();
                }
            }
        });

        return $.mage.validation;
    };
});
