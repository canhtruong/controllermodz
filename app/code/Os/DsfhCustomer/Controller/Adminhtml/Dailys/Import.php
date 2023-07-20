<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Dailys;

class Import extends \Magento\Backend\App\Action

{
    private $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create(); // this crete an empty page 
        $resultPage->getConfig()->getTitle()->prepend(__('Import Dailys'));//this is your page heading 
        return $resultPage;// this show page
    }
}