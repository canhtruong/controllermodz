/*
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_GiftCard
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

define([
    'Magento_Ui/js/view/messages',
    'Bss_GiftCard/js/model/payment/giftcard-messages'
], function (Component, messageContainer) {
    'use strict';

    return Component.extend({

        initialize: function (config) {
            return this._super(config, messageContainer);
        }
    });
});


