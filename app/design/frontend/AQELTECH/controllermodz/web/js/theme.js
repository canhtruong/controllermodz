define(["jquery", "matchMedia", "jquery/jquery.cookie", "domReady!", 'accordion'], function ($, mediaCheck) {
    "use strict";

    function waitForElm(selector) {
        return new Promise(resolve => {
            if (document.querySelector(selector)) {
                return resolve(document.querySelector(selector));
            }

            const observer = new MutationObserver(mutations => {
                if (document.querySelector(selector)) {
                    resolve(document.querySelector(selector));
                    observer.disconnect();
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    }
    $(document).ready(function(){
        waitForElm('#VideoWorker-0').then(() => {
            document.getElementById('VideoWorker-0').addEventListener('play', function(){
                $('.banner-img-loading').hide()
            });
        });
        const BYO_SKUS = ['byops5', 'build-your-own-ps4', 'byoxbx', 'buildyourownELITExb1', 'byops5led', 'byoxbxled']

        if ($('#shopping-cart-table'))
        {
            $('#shopping-cart-table > tbody').each(function () {
                if ($(this).find('input.edit_cart_redirect_url').val() !== undefined)
                {
                    $(this).find('a.action-edit').attr('href', $(this).find('input.edit_cart_redirect_url').val())
                }
            });
        }
        $('a.showcart').on("click",function(e) {

            $('#mini-cart li').each(function () {
                console.log('$(this).find(\'a.product-item-photo\'):', $(this).find('a.product-item-photo'))
                let sku = $(this).find('a.product-item-photo').attr('sku')
                if (sku && sku.includes('-'))
                {
                    sku = sku.split('-')[0]
                }
                if (sku && BYO_SKUS.includes(sku))
                {
                    $(this).find('a.product-item-photo img').attr('src', $.cookie('prev-img-path'))
                }
            });
        })

        if (window.location.pathname === '/checkout/' || window.location.pathname === '/onestepcheckout/index/index/')
        {
            waitForElm('.payment-group #paypal_express').then((ele) => {
                $(ele).parent().find('img').attr('src', '/media/wysiwyg/paypalbutton.png')
            })

            waitForElm('.payment-group #braintree').then((ele) => {
                $(ele).parent().find('label').append('<img class="payment-icon" src="/media/wysiwyg/braintree.png" alt="Brain tree">')
            })

            waitForElm('#shipping-new-address-form .street .input-text').then(() => {
                $('.amtheme-checkout-contact').append('<span class="checkout-required-label">Required Fields *</span>')
                $('#shipping-new-address-form :input:text').each(function(){
                    if (($(this).attr('aria-required') && $(this).is('input:text')) || $(this).attr('name') === 'postcode')
                    {
                        if (!$(this).attr('placeholder'))
                        {
                            $(this).attr('placeholder', 'Street Address: Line 1')
                        }
                        $(this).attr('placeholder', `${$(this).attr('placeholder')} *`)
                    }

                    const placeHolder = $(this).attr('placeholder')
                    $(this).attr('required', true)
                    $(this).parent().append(`<span class="address-floating-label">${placeHolder}</span>`)
                })
            })
            waitForElm('#customer-email-fieldset #customer-email').then(() => {
                const emailNode = $('#customer-email-fieldset #customer-email')
                emailNode.attr('required', true)
                emailNode.parent().append('<span class="address-floating-label">Email Address *</span>')
            })
            waitForElm('#billing-new-address-form-billing-address-form-shared .control .input-text').then(() => {
                $('#billing-new-address-form-billing-address-form-shared :input').each(function(){
                    if (($(this).attr('aria-required') && $(this).is('input:text')) || $(this).attr('name') === 'postcode')
                    {
                        if (!$(this).attr('placeholder'))
                        {
                            $(this).attr('placeholder', 'Street Address: Line 1')
                        }
                        $(this).attr('placeholder', `${$(this).attr('placeholder')} *`)
                    }

                    const placeHolder = $(this).attr('placeholder')
                    $(this).attr('required', true)
                    $(this).parent().append(`<span class="address-floating-label">${placeHolder}</span>`)
                })
            })
            waitForElm('#klarna-pay-over-time-container iframe').then(() => {
                $('#klarna-pay-over-time-container').attr('style', 'display: none !important;')
                $('<div style="display: inline-block; position: relative; overflow: hidden; max-width: 600px; min-width: 280px; width: 100%;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding: 0px 10px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; width: 100%;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding-bottom: 3px; padding-top: 2px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: absolute; z-index: 0; min-height: 0px; min-width: 0px; top: 3px; left: 0px;"><div style="box-sizing: border-box;display: flex;align-items: stretch;flex-direction: column;flex-shrink: 0;border-style: solid;border-width: 0px;position: relative;z-index: 0;min-height: 0px;min-width: 0px;font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif;font-weight: bold;font-size: 16px;color: aliceblue;line-height: 25px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; margin-left: -4px; margin-top: 2px;"><svg focusable="false" height="20" width="20"><g transform="translate(0, 0) scale(1)"><path d="M14.2148,5 L8.2918,12.558 L5.4138,9.68 L3.9998,11.094 L7.6758,14.77 C7.8638,14.958 8.1178,15.063 8.3828,15.063 C8.4028,15.063 8.4228,15.062 8.4428,15.061 C8.7288,15.043 8.9928,14.904 9.1698,14.68 L15.7888,6.234 L14.2148,5 Z" fill="#ffffff" style="transition: fill 0.2s ease 0s;"></path></g></svg></div></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding-left: 20px;"><span style="max-width: 100%; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; font-size: 16px; line-height: 25px; color: rgb(23, 23, 23); letter-spacing: 0px; -webkit-font-smoothing: antialiased; text-rendering: geometricprecision; text-size-adjust: none;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: row; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px;"><span style="max-width: 100%; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 500; font-size: 14px; letter-spacing: 0px; -webkit-font-smoothing: antialiased; text-rendering: geometricprecision; text-size-adjust: none; color: #ffffff;">Pay in 3. Interest-free.</span></div></span></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding-bottom: 3px; padding-top: 2px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: absolute; z-index: 0; min-height: 0px; min-width: 0px; top: 3px; left: 0px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: bold; font-size: 16px; color: rgb(23, 23, 23); line-height: 25px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; margin-left: -4px; margin-top: 2px;"><svg focusable="false" height="20" width="20"><g transform="translate(0, 0) scale(1)"><path d="M14.2148,5 L8.2918,12.558 L5.4138,9.68 L3.9998,11.094 L7.6758,14.77 C7.8638,14.958 8.1178,15.063 8.3828,15.063 C8.4028,15.063 8.4228,15.062 8.4428,15.061 C8.7288,15.043 8.9928,14.904 9.1698,14.68 L15.7888,6.234 L14.2148,5 Z" fill="#ffffff" style="transition: fill 0.2s ease 0s;"></path></g></svg></div></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding-left: 20px;"><span style="max-width: 100%; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; font-size: 16px; line-height: 25px; color: rgb(23, 23, 23); letter-spacing: 0px; -webkit-font-smoothing: antialiased; text-rendering: geometricprecision; text-size-adjust: none;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: row; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px;"><span style="max-width: 100%; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 500; font-size: 14px; letter-spacing: 0px; -webkit-font-smoothing: antialiased; text-rendering: geometricprecision; text-size-adjust: none; color: #ffffff;">Pay now or Pay in 30 days.</span></div></span></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding-bottom: 3px; padding-top: 2px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: absolute; z-index: 0; min-height: 0px; min-width: 0px; top: 3px; left: 0px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: bold; font-size: 16px; color: rgb(23, 23, 23); line-height: 25px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; margin-left: -4px; margin-top: 2px;"><svg focusable="false" height="20" width="20"><g transform="translate(0, 0) scale(1)"><path d="M14.2148,5 L8.2918,12.558 L5.4138,9.68 L3.9998,11.094 L7.6758,14.77 C7.8638,14.958 8.1178,15.063 8.3828,15.063 C8.4028,15.063 8.4228,15.062 8.4428,15.061 C8.7288,15.043 8.9928,14.904 9.1698,14.68 L15.7888,6.234 L14.2148,5 Z" fill="#ffffff" style="transition: fill 0.2s ease 0s;"></path></g></svg></div></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding-left: 20px;"><span style="max-width: 100%; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; font-size: 16px; line-height: 25px; color: rgb(23, 23, 23); letter-spacing: 0px; -webkit-font-smoothing: antialiased; text-rendering: geometricprecision; text-size-adjust: none;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: row; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px;"><span style="max-width: 100%; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 500; font-size: 14px; letter-spacing: 0px; -webkit-font-smoothing: antialiased; text-rendering: geometricprecision; text-size-adjust: none; color: #ffffff;">Buyer protection included.</span></div></span></div></div></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; background-color: rgba(255, 255, 255, 0); width: 100%; height: 10px;"></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: row; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding: 0px 8px;"><svg focusable="false" height="20" width="20"><g transform="translate(0, 0) scale(1)"><path d="M0 10C0 4.486 4.486 0 10 0s10 4.486 10 10-4.486 10-10 10S0 15.514 0 10zm2 0c0 4.411 3.589 8 8 8s8-3.589 8-8-3.589-8-8-8-8 3.589-8 8zm9.5-3.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM11 9v5H9V9h2z" fill="#ffffff" style="transition: fill 0.2s ease 0s;"></path></g></svg><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 10px; min-width: 0px; background-color: rgba(255, 255, 255, 0); width: 5px;"></div><p style="max-width: 100%; color: rgb(23, 23, 23); font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; line-height: 20px; text-rendering: geometricprecision; -webkit-font-smoothing: antialiased; text-align: left; word-break: break-word; margin-bottom: 0px; margin-top: 0px; padding-bottom: 4px; padding-top: 1px; letter-spacing: 0px; text-size-adjust: none; color: #ffffff"><a tabindex="0" aria-label="Learn more (Opens in a modal dialog)" href="#" target="_blank" style="border-radius: 2px; color: rgb(23, 23, 23); cursor: pointer; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; outline: 0px; text-decoration: underline rgb(23, 23, 23); text-rendering: geometricprecision; transition: color 0.2s ease 0s; -webkit-font-smoothing: antialiased; color: #03cf62;">Learn more</a> about your payment options</p></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; background-color: rgba(255, 255, 255, 0); width: 100%; height: 10px;"></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 1px 0px 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; border-top-color: rgb(232, 232, 232); margin-left: 8px; margin-right: 8px;"><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; background-color: rgba(255, 255, 255, 0); width: 100%; height: 15px;"></div></div><div style="box-sizing: border-box; display: flex; align-items: stretch; flex-direction: column; flex-shrink: 0; border-style: solid; border-width: 0px; position: relative; z-index: 0; min-height: 0px; min-width: 0px; padding: 0px 8px;"><p style="max-width: 100%; color: rgb(120, 117, 115); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; font-size: 12px; line-height: 20px; text-rendering: geometricprecision; -webkit-font-smoothing: antialiased; text-align: left; word-break: break-word; margin-bottom: -10px; margin-top: -5px; padding-bottom: 4px; padding-top: 1px; letter-spacing: 0px; text-size-adjust: none; color: #ffffff;">By continuing, I accept the terms of the <a tabindex="0" aria-label="Klarna Shopping Service (Opens in a new window)" href="https://cdn.klarna.com/1.0/shared/content/legal/terms/0/en_gb/user" target="_blank" style="border-radius: 2px; color: rgb(23, 23, 23); cursor: pointer; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; outline: 0px; text-decoration: underline rgb(23, 23, 23); text-rendering: geometricprecision; transition: color 0.2s ease 0s; -webkit-font-smoothing: antialiased; color: #03cf62;">Klarna Shopping Service</a> and confirm that I have read the <a tabindex="0" aria-label="Privacy Notice (Opens in a new window)" href="https://cdn.klarna.com/1.0/shared/content/legal/terms/0/en_gb/privacy" target="_blank" style="border-radius: 2px; color: rgb(23, 23, 23); cursor: pointer; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; outline: 0px; text-decoration: underline rgb(23, 23, 23); text-rendering: geometricprecision; transition: color 0.2s ease 0s; -webkit-font-smoothing: antialiased; color: #03cf62;">Privacy Notice</a> and the <a tabindex="0" aria-label="Cookie Notice (Opens in a new window)" href="https://cdn.klarna.com/1.0/shared/content/legal/terms/0/en_gb/cookie_purchase" target="_blank" style="border-radius: 2px; color: rgb(23, 23, 23); cursor: pointer; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Arial, sans-serif; font-weight: 400; outline: 0px; text-decoration: underline rgb(23, 23, 23); text-rendering: geometricprecision; transition: color 0.2s ease 0s; -webkit-font-smoothing: antialiased; color: #03cf62;">Cookie Notice</a>.</p></div></div></div>').insertAfter('#klarna-pay-over-time-container')
            })

            waitForElm('#btn-clone-placeorder').then(() => {
                waitForElm('#opc-sidebar .action.primary.checkout').then(() => {
                    setInterval(function () {
                        $( "#btn-clone-placeorder").attr('disabled', $('#opc-sidebar .action.primary.checkout').is(':disabled'))
                        if ($('#opc-sidebar .action.primary.checkout').attr('style') && $('#opc-sidebar .action.primary.checkout').attr('style') === 'display: none;')
                        {
                            $( "#btn-clone-placeorder").attr('style', 'display: none;')
                        }
                        else
                        {
                            $( "#btn-clone-placeorder").attr('style', 'display: block;')
                        }
                    }, 500);
                    $( "#btn-clone-placeorder").on("click", function() {
                        $('#opc-sidebar .action.primary.checkout').click()
                    });
                })
            })

           
        }
        else if(window.location.pathname === '/paypal/express/review/')
        {
            waitForElm('#maincontent').then(() => {
                $('#maincontent .page-title-wrapper').append('<p style="margin-top: -42px;">Select shipping method and then scroll down to complete your order</p>')
            })
        }
        else if (window.location.pathname === '/controller-modz-e-gift-card.html' || window.location.pathname === '/egiftcard.html')
        {
            if ($('.product-info-stock-sku'))
            {
                $('.product-info-stock-sku div.stock').remove()
                waitForElm('#bss-giftcard-expires').then((elm) => {
                    $('#bss-giftcard-expires').html('<span>Expires After 365 day(s)</span>')
                })
                waitForElm('.bss-giftcard-timezone').then((elm) => {
                    $('.bss-giftcard-timezone').attr('style', 'display: none !important;')
                })
                //$('#bss-giftcard-expires').children().slice(2).detach();
                $('.estimatedDeliveryDate').attr('style', 'display: none !important;')

            }
        }
        if ($('.prev-img-path'))
        {
            if ($('.prev-img-path').val() !== undefined)
            {
                const date = new Date();
                date.setTime(date.getTime() + (10 * 60 * 1000));
                $.cookie("prev-img-path", $('.prev-img-path').val(), { path: '/' });
            }
        }

        if($('.amtheme-menu-language')) {
            const cloned = '<div class="section-item-content nav-sections-item-content amtheme-item -bottom" data-role="content"> ' +
                '<a href="/contact" style="color: #fff;" class="section-item-title amtheme-item -bottom amtheme-contact-title" data-role="title" aria-selected="false" aria-expanded="false" tabindex="0">\n' +
                '    <svg style="width: 25px;" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="#ffffff" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2" class="stroke-000000"><path d="M7 25H6a5 5 0 0 1 0-10h1v10zM26 25h-1V15h1a5 5 0 0 1 0 10zM5 15v-3C5 5.925 9.925 1 16 1s11 4.925 11 11v3M27 25v4a2 2 0 0 1-2 2h-3M18 31h2"></path></g></svg>\n'+
                '    <span style="margin-left: 10px !important;" class="title">Contact Us</span>\n' +
                '        </a>' +
                '</div>'
            $(cloned).insertBefore('.amtheme-menu-language')
        }

        waitForElm('#maincontent .amtheme-productinfo-wrap .amtheme-item-title').then(() => {
            $('#maincontent .amtheme-productinfo-wrap .amtheme-item-title').on('click', function(event){
                event.preventDefault()
                const ariaControl = $(this).attr('aria-controls')
                const style = $(this).parent().find('div[id="'+ariaControl+'"]').attr('style')
                if (style === 'display: block !important;' || typeof style === 'undefined')
                {
                    $(this).parent().find('div[id="'+ariaControl+'"]').attr('style', 'display: none !important;')
                }
                else
                {
                    $(this).parent().find('div[id="'+ariaControl+'"]').attr('style', 'display: block !important;')
                }
            })
        })


    });
    mediaCheck({
        media: "(min-width: 768px)",
        // Switch to Desktop Version
        entry: function () {},
        // Switch to Mobile Version
        exit: function () {
            $('.footer.content [data-content-type="heading"]').click(function () {
                $(this).parent().toggleClass("active");
            });
            // $(".amtheme-productaside-wrap .product.data.items").accordion({
            //     active: [0, 0], //optional - option from accordion
            //     collapsible: true, //optional - option from accordion
            //     collapsibleElement: "[data-role=collapsible]", // - option from tabs
            //     content: "[data-role=content]", // - option from tabs
            //     trigger: "[data-role=trigger]" // - option from tabs
            // });
        },
    });
});
