<?php
namespace MODZ\ProductSortOption\Plugin\Model\Config\Source;
use Magento\Catalog\Model\Config\Source\ListSort;

class ListSource extends ListSort
{
    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->_getCatalogConfig()->getAttributeUsedForSortByArray() as $code => $label) {
            $options = ['label' => __($label), 'value' => $code];
        }
        return $options;
    }

}
