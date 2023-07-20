<?php

namespace Os\DsfhCustomer\Model\ResourceModel;

class Receipt extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function _construct()
    {
        $this->_init('dsfh_customer_receipt', 'DSFH_CUSTOMER_RECEIPT_ID');
    }

}

