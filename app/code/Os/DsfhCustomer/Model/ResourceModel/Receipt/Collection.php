<?php
namespace Os\DsfhCustomer\Model\ResourceModel\Receipt;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public $_idFieldName = 'DSFH_CUSTOMER_RECEIPT_ID';
   
    public function _construct()
    {
        $this->_init(
            'Os\DsfhCustomer\Model\Receipt',
            'Os\DsfhCustomer\Model\ResourceModel\Receipt'
        );
    }
    
    public function toOptionArray()
    {
        return parent::_toOptionArray('DSFH_CUSTOMER_RECEIPT_ID', 'REQUEST_ID');
    }
}
