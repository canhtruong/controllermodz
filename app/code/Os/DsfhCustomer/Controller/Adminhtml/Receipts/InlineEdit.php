<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Receipts;

class InlineEdit extends \Magento\Backend\App\Action

{

    protected $jsonFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */

        $resultJson = $this->jsonFactory->create();

        $error = false;

        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {

            $receiptItems = $this->getRequest()->getParam('items', []);

            if (!count($receiptItems)) {

                $messages[] = __('Please correct the data sent.');

                $error = true;

            } else {

                foreach (array_keys($receiptItems) as $model_id) {

                    /** @var \Magento\Cms\Model\Block $block */

                    $model = $this->_objectManager->create('Os\DsfhCustomer\Model\Receipt')->load($model_id);

                    try {

                        $model->setData(array_merge($model->getData(), $receiptItems[$model_id]));
                        $model->save();

                    } catch (\Exception $e) {

                        $messages[] = "[Receipt ID: {$model_id}]  {$e->getMessage()}";

                        $error = true;

                    }

                }

            }

        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);

    }

}