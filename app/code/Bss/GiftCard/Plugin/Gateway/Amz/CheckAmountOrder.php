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
namespace Bss\GiftCard\Plugin\Gateway\Amz;

use Bss\GiftCard\Helper\Data as GiftCardHelper;
use Bss\GiftCard\Model\ResourceModel\GiftCard\Quote;
use Magento\Checkout\Model\Session;

class CheckAmountOrder
{
    /**
     * @var GiftCardHelper
     */
    protected $giftCardHelper;

    /**
     * @var Quote
     */
    protected $quote;

    /**
     * @var Session
     */
    protected $session;

    /**
     * CheckAmountOrder constructor.
     * @param GiftCardHelper $giftCardHelper
     * @param Quote $quote
     * @param Session $session
     */
    public function __construct(
        GiftCardHelper $giftCardHelper,
        Quote $quote,
        Session $session
    ) {
        $this->giftCardHelper = $giftCardHelper;
        $this->quote = $quote;
        $this->session = $session;
    }

    /**
     * @param \Amazon\Payment\Gateway\Command\AmazonAuthCommand $amazonAuthCommand
     * @param $commandSubject
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeExecute(
        \Amazon\Payment\Gateway\Command\AmazonAuthCommand $amazonAuthCommand,
        $commandSubject
    ) {
        if ($this->giftCardHelper->isEnabled()) {
            foreach ($commandSubject as $key => $item) {
                if (is_object($item) && $item instanceof \Magento\Payment\Gateway\Data\PaymentDataObject) {
                    $orderAmount = $item->getOrder()->getGrandTotalAmount();
                    $orderId = $item->getOrder()->getId();
                    $quote = $this->session->getQuote();
                    $giftCode = $this->quote->getGiftCardCode($quote);
                    if (!empty($giftCode) && $giftCode) {
                        if (isset($commandSubject['amount']) &&
                            (float)$commandSubject['amount'] !== (float)$orderAmount) {
                            $commandSubject['amount'] = $orderAmount;
                        }
                    }
                }
            }
        }
        return [$commandSubject];
    }
}
