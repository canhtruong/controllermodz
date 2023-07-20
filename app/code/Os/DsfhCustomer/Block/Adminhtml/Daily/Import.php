<?php

namespace Os\DsfhCustomer\Block\Adminhtml\Daily;

class Import extends \Magento\Backend\Block\Template
{

    public function __construct(\Magento\Backend\Block\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function sayHello()
    {
        $txt = 'Hello World';
        return $txt;
    }
}
