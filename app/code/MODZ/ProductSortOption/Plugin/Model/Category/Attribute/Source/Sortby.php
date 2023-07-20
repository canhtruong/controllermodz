<?php
namespace MODZ\ProductSortOption\Plugin\Model\Category\Attribute\Source;
class Sortby extends \Magento\Catalog\Model\Category\Attribute\Source\Sortby
{
    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        $options = [];
        foreach ($this->_getCatalogConfig()->getAttributeUsedForSortByArray() as $code => $label) {
            $options[] = ['label' => __($label), 'value' => $code];
        }
        return $options;
    }
}
