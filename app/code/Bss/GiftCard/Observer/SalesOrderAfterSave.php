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
 * @copyright  Copyright (c) 2022 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
declare(strict_types=1);

namespace Bss\GiftCard\Observer;

use Magento\Framework\Event\ObserverInterface;
use Bss\GiftCard\Model\Pattern\Code;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class SalesOrderAfterSave implements ObserverInterface
{
    /**
     * @var Code
     */
    protected $code;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * SalesOrderAfterPlace constructor.
     *
     * @param Code $code
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        Code                    $code,
        MessageManagerInterface $messageManager
    ) {
        $this->code = $code;
        $this->messageManager = $messageManager;
    }

    /**
     * GenerateCode after sales order saved
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();
        if ($orderId) {
            $patterns = $order->getPatterns();
            $datas = $order->getCustomDatas();
            $varses = $order->getVarses();
            if ($patterns && $datas && $varses) {
                foreach ($datas as $key => $data) {
                    try {
                        $data['order_id'] = $orderId;
                        $this->code->generateCodes($patterns[$key], $data, $varses[$key]);
                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage($e->getMessage());
                    }
                }
            }
        }
        return $this;
    }
}
