<?php

namespace Os\DsfhCustomer\Model\ResourceModel;

class Daily extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function _construct()
    {
        $this->_init('dsfh_customer', 'DSFH_CUSTOMER_ID');
    }

}

