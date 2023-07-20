<?php

namespace MODZ\BuilderCart\Block\Catalog\Product;


use Magento\Quote\Model\QuoteIdToMaskedQuoteId;

class View extends \Magento\Catalog\Block\Product\View
{
    protected $attributeSet;
    protected $helperData;
    protected $checkoutCart;
    protected $quoteIdToMaskedQuoteId;


    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
        \MODZ\BuilderCart\Helper\Data $helperData,
        \Magento\Checkout\Block\Cart $checkoutCart,
        QuoteIdToMaskedQuoteId $quoteIdToMaskedQuoteId,
        array $data = []
    ) {
        $this->attributeSet = $attributeSet;
        $this->helperData = $helperData;
        $this->checkoutCart = $checkoutCart;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;

        //Extend the original functionality.
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }


    /**
     * Method to to fetch Attribute Set Name
     **/
    public function getAttributeSetName()
    {

        $_product = $this->getProduct();
        $attributeSetRepository = $this->attributeSet->get($_product->getAttributeSetId());
        return $attributeSetRepository->getAttributeSetName();
    }

    /**
     * Define if the special price should be shown
     *
     * @return bool
     */
    public function hasSpecialPrice()
    {
        $_product = $this->getProduct();
        $displayRegularPrice = $_product->getPrice();
        $displayFinalPrice = $_product->getFinalPrice();
        return $displayFinalPrice < $displayRegularPrice;
    }

    /**
     * Define if the special price should be shown
     *
     * @return bool
     */
    public function getBuilderAppUrl()
    {
        return $this->helperData->getBuilderAppUrl();
    }

    /**
     * Checkout Quote Data
     */
    public function getCheckoutSession()
    {
        $quote = $this->checkoutCart->getQuote();
        $quoteId = $quote->getId();
        $quoteMaskedId = null;

        if ($quoteId) {
            try {
                $quoteMaskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);
            } catch (NoSuchEntityException $exception) {
                throw new LocalizedException(__('Current user does not have an active cart.'));
            }
        }

        return $quoteMaskedId;
    }
}
