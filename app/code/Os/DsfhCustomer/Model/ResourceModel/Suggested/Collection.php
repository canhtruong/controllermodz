<?php
namespace Os\DsfhCustomer\Model\ResourceModel\Suggested;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public $_idFieldName = 'DSFH_CUSTOMER_SUGGESTED_ID';
   
    public function _construct()
    {
        $this->_init(
            'Os\DsfhCustomer\Model\Suggested',
            'Os\DsfhCustomer\Model\ResourceModel\Suggested'
        );
    }
    
    public function toOptionArray()
    {
        return parent::_toOptionArray('DSFH_CUSTOMER_SUGGESTED_ID', 'REQUEST_ID');
    }
}
