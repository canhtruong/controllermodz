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
 * @copyright  Copyright (c) BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\GiftCard\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\CreditmemoExtensionInterfaceFactory;
use Bss\GiftCard\Helper\Data as GiftCardHelper;

class CreditmemoLoadAfter implements ObserverInterface
{
    /**
     * @var CreditmemoExtensionInterfaceFactory
     */
    private $creditmemoExtensionFactory;

    /**
     * @var GiftCardHelper
     */
    private $giftCardHelper;

    /**
     * @param CreditmemoExtensionInterfaceFactory $creditmemoExtensionFactory
     * @param GiftCardHelper $giftCardHelper
     */
    public function __construct(
        CreditmemoExtensionInterfaceFactory $creditmemoExtensionFactory,
        GiftCardHelper $giftCardHelper
    ) {
        $this->creditmemoExtensionFactory = $creditmemoExtensionFactory;
        $this->giftCardHelper = $giftCardHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $observer->getCreditmemo();
        $extensionAttributes = $creditmemo->getExtensionAttributes();

        if (!$extensionAttributes) {
            $extensionAttributes = $this->creditmemoExtensionFactory->create();
        }

        $giftCardAmount = $creditmemo->getOrder()->getData('bss_giftcard_amount');
        $storeId = $creditmemo->getStoreId();

        if ($this->giftCardHelper->isEnabled($storeId) &&
            $giftCardAmount &&
            $giftCardAmount > 0
        ) {
            $extensionAttributes->setGiftCardAmount($giftCardAmount);
            $creditmemo->setExtensionAttributes($extensionAttributes);
        }
    }
}
