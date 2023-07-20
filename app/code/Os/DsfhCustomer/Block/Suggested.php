<?php

namespace Os\DsfhCustomer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Os\DsfhCustomer\Model\SuggestedFactory;

class Suggested extends Template
{
    public $store;

    protected $_request;

    protected $_date;

    public $suggestedFactory;
    
    protected $_customerRepositoryInterface;

    public function __construct(
        Template\Context $context,
        RequestInterface $request,
        DateTime $dateTime,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
        SuggestedFactory $suggestedFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    )
    {
        $this->_request = $request;
        $this->store = $context->getStoreManager();
        $this->_date = $dateTime;
		$this->_storeManager = $storeManager;
        $this->suggestedFactory = $suggestedFactory;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context, $data);
    }

    public function getSuggestedCollection()
    {
		$collection = $this->suggestedFactory->create()->getCollection();
		
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerFactory = $objectManager->create('Magento\Customer\Model\CustomerFactory')->create();
        $om = \Magento\Framework\App\ObjectManager::getInstance(); 
        $customerSession = $om->get('Magento\Customer\Model\Session'); 
        $customerCollection = $customerFactory->getCollection()
                ->addAttributeToSelect("*")
                    ->addFieldToFilter('email', $customerSession->getCustomer()->getEmail())->getFirstItem();
        //if($customerCollection->getOrgid() == "")
            //echo 1;
        if($customerCollection->getOrgid() == '362'){
            $collection->addFieldToFilter('ORG_ID', array('in' => array($customerCollection->getOrgid(),'',null)));
        } else {
            if($customerCollection->getOrgid() == "" || $customerCollection->getOrgid() == null){
                $collection->addFieldToFilter('ORG_ID', 'dadadadada'.$customerCollection->getOrgid());
            } else {
                $collection->addFieldToFilter('ORG_ID', $customerCollection->getOrgid());
            }
        }    
        return $collection;
    }

    public function getSuggestedByRequest()
    {
        $request = $this->_request->getParam('request');

        $suggesteds = $this->getSuggestedCollection()->addFieldToSelect('*');
        $suggesteds->addFieldToFilter('REQUEST_ID', $request)->setOrder('TRX_DATE','desc');
        
        return $suggesteds;
    }

}