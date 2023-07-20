<?php
namespace MODZ\ProductSortOption\Plugin\Elasticsearch\Model\Adapter;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface;

class AdditionalFieldMapper
{
    /**
     * Sets `created_at` type keyword to enable sorting
     *
     * @param \Magento\Elasticsearch\Elasticsearch5\Model\Adapter\FieldMapper\ProductFieldMapperProxy $subject
     * @param array $allAttributes
     * @return array
     * @see \Magento\Elasticsearch\Elasticsearch5\Model\Adapter\FieldMapper\ProductFieldMapper::getAllAttributesTypes
     */
    public function afterGetAllAttributesTypes($subject, array $allAttributes)
    {
        $allAttributes['created_at']['type'] = ConverterInterface::INTERNAL_DATA_TYPE_KEYWORD;

        return $allAttributes;
    }
}
