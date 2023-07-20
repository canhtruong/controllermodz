<?php

namespace Os\DsfhCustomer\Controller\Index;

use Magento\Framework\App\Http\Context as AuthContext;

use Magento\Framework\UrlInterface;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Index extends \Magento\Framework\App\Action\Action

{
    
    protected $_pageFactory;

    private $authContext;

    protected $_urlInterface; 
    
    protected $storeManagerInterface;

	protected $_transportBuilder;

	protected $scopeConfig;
	
	protected $_customerFactory;

	const XML_PATH_REGISTER_EMAIL_IDENTITY = 'customer/create_account/email_identity';
	
    public function __construct(

        \Magento\Framework\App\Action\Context $context,

        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
		TransportBuilder $transportBuilder,
		ScopeConfigInterface $scopeConfig,
		
        AuthContext $authContext,

        UrlInterface $urlInterface
    )
    {

        $this->_pageFactory = $pageFactory;

        $this->authContext = $authContext;

        $this->_urlInterface = $urlInterface;
        $this->_customerFactory = $customerFactory;
        $this->storeManagerInterface = $storeManager;
		$this->scopeConfig = $scopeConfig;
		$this->_transportBuilder = $transportBuilder;

        return parent::__construct($context);

    }



    public function execute()

    {
          
        
            return $this->_redirect('customer/account/login');
       

    }

}