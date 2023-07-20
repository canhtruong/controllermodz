<?php
/**
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

namespace Bss\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;
use Bss\GiftCard\Model\ResourceModel\Amounts as AmountsResourceModel;

/**
 * Class Amounts
 *
 * @package Bss\GiftCard\Model
 */
class Amounts extends AbstractModel
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(AmountsResourceModel::class);
    }
}