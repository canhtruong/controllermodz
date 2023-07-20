<?php

namespace Os\DsfhCustomer\Ui\Component\Listing\Column;



class Input extends \Magento\Ui\Component\Form\Element\Input
{
    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        parent::prepare();

        $config = $this->getData('config');

        if(isset($config['dataScope']) && $config['dataScope']=='customcheckboxdf'){
            $config['default']= 'testcanh';
            $this->setData('config', (array)$config);
        }
    }
}

