<?php

namespace Os\DsfhCustomer\Model\ResourceModel;

class Suggested extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function _construct()
    {
        $this->_init('dsfh_customer_suggested', 'DSFH_CUSTOMER_SUGGESTED_ID');
    }

}

