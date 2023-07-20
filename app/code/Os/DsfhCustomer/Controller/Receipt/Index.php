<?php

namespace Os\DsfhCustomer\Controller\Receipt;

use Magento\Framework\App\Http\Context as AuthContext;
use Magento\Framework\UrlInterface;

class Index extends \Magento\Framework\App\Action\Action

{

    protected $_pageFactory;

    private $authContext;

    protected $_urlInterface; 

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        AuthContext $authContext,
        UrlInterface $urlInterface
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->authContext = $authContext;
        $this->_urlInterface = $urlInterface;
        return parent::__construct($context);
    }

    public function execute()
    {
        $isLoggedIn = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

        if ($isLoggedIn) {
            $resultPage = $this->_pageFactory->create();
            $resultPage->getConfig()->getTitle()->set('DSFH Customer receipt confirmation');
            return $resultPage;
        } else {
            return $this->_redirect('customer/account/login');
        }

    }

}