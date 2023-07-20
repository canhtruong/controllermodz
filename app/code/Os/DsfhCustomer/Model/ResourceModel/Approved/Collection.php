<?php
namespace Os\DsfhCustomer\Model\ResourceModel\Approved;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public $_idFieldName = 'DSFH_CUSTOMER_APPROVED_ID';
   
    public function _construct()
    {
        $this->_init(
            'Os\DsfhCustomer\Model\Approved',
            'Os\DsfhCustomer\Model\ResourceModel\Approved'
        );
    }
    
    public function toOptionArray()
    {
        return parent::_toOptionArray('DSFH_CUSTOMER_APPROVED_ID', 'ROW_ID');
    }
}
