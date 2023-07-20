<?php

namespace MODZ\BuilderCart\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MODZ\BuilderCart\Block\Catalog\Product\View;

class ReplaceAddToCart implements ObserverInterface
{

    public function __construct(
        View $_product
    ) {
        $this->product = $_product;
    }

    public function execute(Observer $observer)
    {
        if ($observer->getFullActionName() == 'catalog_product_view') {
            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $observer->getLayout();
            if ($this->product->getAttributeSetName() == "Build Your Own") {
                //you can apply or add you condition here.
                $layout->unsetElement('product.info');
            } else {
                $layout->unsetElement('product.info.builder');
            }
        }
    }
}
