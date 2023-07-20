<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Approveds;

use Os\DsfhCustomer\Model\Approved as Approved;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($approved = $this->_objectManager->create(Approved::class)->load($id))) {

            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        try {

            $approved->delete();
            $this->messageManager->addSuccess(__('Your approved has been deleted !'));

        } catch (Exception $e) {

            $this->messageManager->addError(__('Error while trying to delete approved: '));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}