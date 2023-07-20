<?php
namespace Os\DsfhCustomer\Controller\Adminhtml\Receipts;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Prince\Faq\Model\FaqGroup
     */
    private $receiptModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Os\DsfhCustomer\Model\Receipt $receiptModel,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->receiptModel = $receiptModel;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {

            $id="";

            if (isset($data['DSFH_CUSTOMER_RECEIPT_ID'])) {

                $id = $data['DSFH_CUSTOMER_RECEIPT_ID'];
                $model = $this->receiptModel->load($id);

            } else {
                $model = $this->receiptModel;
            }

            
            if (!$model->getId() && isset($data['DSFH_CUSTOMER_RECEIPT_ID'])) {
            
                $this->messageManager->addErrorMessage(__('This Item no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {

                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved receipt'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');

            } catch (LocalizedException $e) {

                $this->messageManager->addErrorMessage($e->getMessage());

            } catch (\Exception $e) {

                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the receipt.'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

   
}
