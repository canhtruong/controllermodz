<?php
namespace Os\DsfhCustomer\Controller\Adminhtml\Suggesteds;

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
    private $suggestedModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Os\DsfhCustomer\Model\Suggested $suggestedModel,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->suggestedModel = $suggestedModel;
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

            if (isset($data['DSFH_CUSTOMER_SUGGESTED_ID'])) {

                $id = $data['DSFH_CUSTOMER_SUGGESTED_ID'];
                $model = $this->suggestedModel->load($id);

            } else {
                $model = $this->suggestedModel;
            }

            if (isset($data['UNIT_PRICE'])) {
                $data['UNIT_PRICE'] = (float) $data['UNIT_PRICE'];
            }
            
            if (!$model->getId() && isset($data['DSFH_CUSTOMER_SUGGESTED_ID'])) {
            
                $this->messageManager->addErrorMessage(__('This Item no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {

                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved suggested'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');

            } catch (LocalizedException $e) {

                $this->messageManager->addErrorMessage($e->getMessage());

            } catch (\Exception $e) {

                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the suggested.'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

   
}
