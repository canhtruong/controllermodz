<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Approveds;

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

            $approvedItems = $this->getRequest()->getParam('items', []);

            if (!count($approvedItems)) {

                $messages[] = __('Please correct the data sent.');

                $error = true;

            } else {

                foreach (array_keys($approvedItems) as $model_id) {

                    /** @var \Magento\Cms\Model\Block $block */

                    $model = $this->_objectManager->create('Os\DsfhCustomer\Model\Approved')->load($model_id);

                    try {

                        $model->setData(array_merge($model->getData(), $approvedItems[$model_id]));
                        $model->save();

                    } catch (\Exception $e) {

                        $messages[] = "[Approved ID: {$model_id}]  {$e->getMessage()}";

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