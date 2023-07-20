<?php
namespace Os\DsfhCustomer\Model\ResourceModel\Daily;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public $_idFieldName = 'DSFH_CUSTOMER_ID';
   
    public function _construct()
    {
        $this->_init(
            'Os\DsfhCustomer\Model\Daily',
            'Os\DsfhCustomer\Model\ResourceModel\Daily'
        );
    }
    
    public function toOptionArray()
    {
        return parent::_toOptionArray('DSFH_CUSTOMER_ID', 'DSFH_REQUEST_ID');
    }
}
