<?php

namespace Os\DsfhCustomer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Os\DsfhCustomer\Model\ApprovedFactory;

class Approved extends Template

{

    public $store;

    protected $_request;

    protected $_date;

    public $approvedFactory;
    
    protected $_customerRepositoryInterface;

    public function __construct(

        Template\Context $context,

        RequestInterface $request,

        DateTime $dateTime,

		\Magento\Store\Model\StoreManagerInterface $storeManager,

        ApprovedFactory $approvedFactory,
        
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,

        array $data = []

    )

    {
        $this->_request = $request;

        $this->store = $context->getStoreManager();

        $this->_date = $dateTime;

		$this->_storeManager = $storeManager;

        $this->approvedFactory = $approvedFactory;
        
        $this->_customerRepositoryInterface = $customerRepositoryInterface;

        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */

    public function getApprovedCollection()
    {
		$collection = $this->approvedFactory->create()->getCollection();
        
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

    public function getApprovedDefaultCollection()
    {
        $approveds = $this->getApprovedCollection();
        $approveds->getSelect()->columns(['TOTAL' => new \Zend_Db_Expr('SUM(AMOUNT)')])
            ->group('RELEASE_NUMBER')
            ->order('DATE DESC');
        
        return $approveds;
    }

    public function getApprovedByRelease()
    {
        $release = $this->_request->getParam('release');
        $approveds = $this->getApprovedCollection()->addFieldToSelect('*');
        $approveds->addFieldToFilter('RELEASE_NUMBER', $release)->setOrder('DATE','desc');
        return $approveds;
    }

}