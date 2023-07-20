<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Suggesteds;

use Os\DsfhCustomer\Model\Suggested as Suggested;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($suggested = $this->_objectManager->create(Suggested::class)->load($id))) {

            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        try {

            $suggested->delete();
            $this->messageManager->addSuccess(__('Your suggested has been deleted !'));

        } catch (Exception $e) {

            $this->messageManager->addError(__('Error while trying to delete suggested: '));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}