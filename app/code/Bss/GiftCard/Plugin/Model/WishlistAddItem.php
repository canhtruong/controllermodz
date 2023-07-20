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
namespace Bss\GiftCard\Plugin\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;

class WishlistAddItem
{
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * WishlistAddItem constructor.
     * @param DataObjectFactory $dataObjectFactory
     * @param RequestInterface $request
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory,
        RequestInterface $request
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->request = $request;
    }

    /**
     * @param \Magento\Wishlist\Model\Wishlist $wishlist
     * @param \Magento\Catalog\Model\Product $product
     * @param null|\Magento\Framework\DataObject $buyRequest
     * @param bool $forciblySetQty
     * @return array
     */
    public function beforeAddNewItem(
        \Magento\Wishlist\Model\Wishlist $wishlist,
        $product,
        $buyRequest = null,
        $forciblySetQty = false
    ) {
        //!is_string($product) &&
        if ($product->getTypeId() === 'bss_giftcard') {
            if (!$buyRequest) {
                $buyRequest = $this->dataObjectFactory->create()->setData('product_action', 'wishlist');
            } elseif ($buyRequest instanceof \Magento\Framework\DataObject) {
                $buyRequest->setData('product_action', 'wishlist');
            } elseif (is_array($buyRequest)) {
                $buyRequest = array_merge(
                    $buyRequest,
                    [
                        'product_action' => 'wishlist'
                    ]
                );
                $buyRequest = $this->dataObjectFactory->create()->setData($buyRequest);
            }

            $fields = [
                'bss_giftcard_amount',
                'bss_giftcard_amount_dynamic',
                'bss_giftcard_template',
                'bss_giftcard_sender_name',
                'bss_giftcard_recipient_name',
                'bss_giftcard_sender_email',
                'bss_giftcard_recipient_email',
                'bss_giftcard_message_email',
                'bss_giftcard_delivery_date',
                'bss_giftcard_timezone'
            ];
            foreach ($fields as $field) {
                if ($this->request->getParam($field)) {
                    $buyRequest->setData($field, $this->request->getParam($field));
                }
            }
        }
        $qty = $buyRequest->getData('qty') ?? 1;
        $this->setCurrentConfig($buyRequest, $qty);
        return [
            $product,
            $buyRequest,
            $forciblySetQty
        ];
    }

    /**
     * @param \Magento\Framework\DataObject $buyRequest
     * @param int $qty
     */
    protected function setCurrentConfig($buyRequest, $qty)
    {
        $processingParams = $buyRequest->getData('_processing_params');
        if (!$processingParams || !$processingParams instanceof \Magento\Framework\DataObject) {
            $processingParams = $this->dataObjectFactory->create();
            $currentConfig = $this->dataObjectFactory->create();
            $processingParams->setData('current_config', $currentConfig);
            $buyRequest->setData('_processing_params', $processingParams);
            return;
        }
        $currentConfig = $processingParams->getData('current_config');
        if (!$currentConfig || !$currentConfig instanceof \Magento\Framework\DataObject) {
            $currentConfig = $this->dataObjectFactory->create();
            $processingParams->setData('current_config', $currentConfig);
            $buyRequest->setData('_processing_params', $processingParams);
            return;
        }
        $currentConfig->setData('qty', $qty);
        $processingParams->setData('current_config', $currentConfig);
        $buyRequest->setData('_processing_params', $processingParams);
        return;
    }
}
