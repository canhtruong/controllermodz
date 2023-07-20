<?php

namespace Os\DsfhCustomer\Model\ResourceModel;



class Approved extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb

{

    public function _construct()

    {

        $this->_init('dsfh_customer_approved', 'DSFH_CUSTOMER_APPROVED_ID');

    }

}

