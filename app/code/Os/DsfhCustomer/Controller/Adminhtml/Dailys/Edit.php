<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Dailys;

class Edit extends \Magento\Backend\App\Action
{

    /**

     * @var \Magento\Framework\View\Result\PageFactory

     */

    private $resultPageFactory;

    /**

     * @var \Prince\Faq\Model\FaqGroup

     */

    private $dailyModel;

    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\Registry $coreRegistry

     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\Registry $coreRegistry,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory,

        \Os\DsfhCustomer\Model\Daily $dailyModel

    ) {

        $this->resultPageFactory = $resultPageFactory;

        $this->dailyModel = $dailyModel;

        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);

    }



    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');
        $model = $this->dailyModel;

        if ($id) {

            $model->load($id);

            if (!$model->getId()) {

                $this->messageManager->addErrorMessage(__('This daily no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');

            }

        }

        $this->coreRegistry->register('daily', $model);

        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Daily'));

        return $resultPage;

    }

}

