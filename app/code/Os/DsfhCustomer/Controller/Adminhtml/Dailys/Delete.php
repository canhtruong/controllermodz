<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Dailys;

use Os\DsfhCustomer\Model\Daily as Daily;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($daily = $this->_objectManager->create(Daily::class)->load($id))) {

            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        try {

            $daily->delete();
            $this->messageManager->addSuccess(__('Your daily has been deleted !'));

        } catch (Exception $e) {

            $this->messageManager->addError(__('Error while trying to delete daily: '));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}