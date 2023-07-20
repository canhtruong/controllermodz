<?php

namespace Os\DsfhCustomer\Model;

class Receipt extends \Magento\Framework\Model\AbstractModel
{

    public function _construct()
    {
        $this->_init('Os\DsfhCustomer\Model\ResourceModel\Receipt');
    }   

}

