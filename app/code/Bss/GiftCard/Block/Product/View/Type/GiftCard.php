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

namespace Bss\GiftCard\Block\Product\View\Type;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View\AbstractView;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Framework\Json\Encoder;
use Bss\GiftCard\Model\Product\Type\GiftCard as GiftCardType;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Bss\GiftCard\Model\TemplateFactory;
use Magento\Config\Model\Config\Source\Locale\Timezone;

/**
 * Class GiftCard
 *
 * @package Bss\GiftCard\Block\Product\View\Type
 */
class GiftCard extends AbstractView
{
    /**
     * @var Encoder
     */
    private $jsonEncoder;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var Timezone
     */
    private $timeZone;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param Context $context
     * @param ArrayUtils $arrayUtils
     * @param Encoder $jsonEncoder
     * @param PriceCurrencyInterface $priceCurrency
     * @param TemplateFactory $templateFactory
     * @param Timezone $timeZone
     * @param array $data
     */
    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        Encoder $jsonEncoder,
        PriceCurrencyInterface $priceCurrency,
        TemplateFactory $templateFactory,
        Timezone $timeZone,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $arrayUtils,
            $data
        );
        $this->jsonEncoder = $jsonEncoder;
        $this->priceCurrency = $priceCurrency;
        $this->templateFactory = $templateFactory;
        $this->timeZone = $timeZone;
    }

    /**
     * @return string
     */
    public function getGiftCardJson()
    {
        $data = [];
        $product = $this->getProduct();
        $data['amount'] = $this->getAmountsJson($product);
        $data['template'] = $this->getTemplateJson($product);
        $data['productId'] = $product->getId();
        $data['isPhysical'] = $product->getTypeInstance()->isPhysical($product);
        $data['message'] = (bool) $product->getBssGiftCardMessage();
        $data['timeZone'] = $this->timeZone->toOptionArray();
        return $this->jsonEncoder->encode($data);
    }

    /**
     * @param   \Magento\Catalog\Model\Product $product
     * @return  array
     */
    private function getAmountsJson($product)
    {
        $amounts = [];
        $amountsData = $product->getData(GiftCardType::BSS_GIFT_CARD_AMOUNTS);
        if (!empty($amountsData)) {
            foreach ($amountsData as $amount) {
                $value = $this->priceCurrency->convertAndRound($amount['value']);
                $value = $value ? (float)$value : 0;
                $amounts['amountList'][] = [
                    'price' => $this->priceCurrency->convert($amount['price']),
                    'value' => (int) $amount['amount_id'],
                    'label' => $this->priceCurrency->format($value, false)
                ];
            }
        }
        $dynamicPrice = $product->getData(GiftCardType::BSS_GIFT_CARD_DYNAMIC_PRICE);
        $minAmount = $product->getData(GiftCardType::BSS_GIFT_CARD_OPEN_MIN_AMOUNT);
        $maxAmount = $product->getData(GiftCardType::BSS_GIFT_CARD_OPEN_MAX_AMOUNT);
        if ($dynamicPrice && $maxAmount && $minAmount) {
            $amounts['amountDynamic'] = [
                'minAmount' => $this->priceCurrency->convert($minAmount),
                'maxAmount' => $this->priceCurrency->convert($maxAmount)
            ];
            if ($product->getData(GiftCardType::BSS_GIFT_CARD_PERCENTAGE_TYPE)) {
                $amounts['amountDynamic']['percentageValue'] = $product->getData(
                    GiftCardType::BSS_GIFT_CARD_PERCENTAGE_VALUE
                );
            }
        }

        return $amounts;
    }

    /**
     * @param   \Magento\Catalog\Model\Product $product
     * @return  array
     */
    private function getTemplateJson($product)
    {
        $productId = $product->getId();
        $templateModel = $this->templateFactory->create();
        return $templateModel->loadProductTemplate($productId);
    }
}
