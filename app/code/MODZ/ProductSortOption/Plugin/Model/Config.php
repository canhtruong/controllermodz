<?php
namespace MODZ\ProductSortOption\Plugin\Model;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    protected $_storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    public function afterGetAttributeUsedForSortByArray(\Magento\Catalog\Model\Config $catalogConfig, $options)
    {
//        $customOption['created_at'] = __('Newesst');
//        if ($options['position'])
//        {
//            unset($options['position']);
////            $options = array_values($options);
//        }
//
//        $options = array_merge($options, $customOption);
        //var_dump('<pre>',$options);die;
        return $options;
    }
}
