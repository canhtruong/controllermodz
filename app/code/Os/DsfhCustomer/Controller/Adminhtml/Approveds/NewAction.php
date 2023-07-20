<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Approveds;

class NewAction extends \Magento\Backend\App\Action

{

    /**

     * @var \Magento\Backend\Model\View\Result\ForwardFactory

     */

    private $resultForwardFactory;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\Registry $coreRegistry

     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\Registry $coreRegistry,

        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

    ) {

        $this->coreRegistry = $coreRegistry;

        $this->resultForwardFactory = $resultForwardFactory;

        parent::__construct($context);

    }



    /**

     * New action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        $resultForward = $this->resultForwardFactory->create();

        return $resultForward->forward('edit');

    }

}

