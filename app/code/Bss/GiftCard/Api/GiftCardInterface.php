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

namespace Bss\GiftCard\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface GiftCardInterface
 *
 * @package Bss\GiftCard\Api
 */
interface GiftCardInterface
{
    /**
     * @param string $code
     *
     * @return void
     *
     * @throws NoSuchEntityException
     */
    public function checkCode($code);

    /**
     * @param string $cartId
     * @param int $giftCardQuoteId
     *
     * @return void
     *
     * @throws NoSuchEntityException
     */
    public function guestRemove($cartId, $giftCardQuoteId);

    /**
     * @param int $customerId
     * @param int $giftCardQuoteId
     *
     * @return void
     *
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function removeGiftCodeByCustomer($customerId, $giftCardQuoteId);

    /**
     * @param string $cartId
     * @param string $giftCardCode
     *
     * @return void
     *
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function apply($cartId, $giftCardCode);

    /**
     * @param int $customerId
     * @param string $giftCardCode
     *
     * @return void
     *
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function applyGiftCodeByCustomer($customerId, $giftCardCode);
}