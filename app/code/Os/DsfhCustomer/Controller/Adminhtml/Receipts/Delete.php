<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Receipts;

use Os\DsfhCustomer\Model\Receipt as Receipt;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($receipt = $this->_objectManager->create(Receipt::class)->load($id))) {

            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        try {

            $receipt->delete();
            $this->messageManager->addSuccess(__('Your receipt has been deleted !'));

        } catch (Exception $e) {

            $this->messageManager->addError(__('Error while trying to delete receipt: '));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}