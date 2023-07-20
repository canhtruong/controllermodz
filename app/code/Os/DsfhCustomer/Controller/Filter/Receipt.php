<?php

namespace Os\DsfhCustomer\Controller\Filter;

use Magento\Framework\Controller\ResultFactory;

class Receipt extends \Magento\Framework\App\Action\Action

{
    protected $_resultFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ResultFactory $resultFactory
    )
    {
        $this->_resultFactory = $resultFactory;
        return parent::__construct($context);
    }

    public function execute()
    {   
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $urlInterface = $objectManager->get('Magento\Framework\UrlInterface');

        $receiptBlock = $objectManager->get('\Os\DsfhCustomer\Block\Receipt');
        $receipts = $receiptBlock->getReceiptCollection();
        $receipts->getSelect()->group('REQUEST_ID')->order('DATE DESC');

        $fromDateRequest = $this->getRequest()->getParam('from_date');
        $toDateRequest = $this->getRequest()->getParam('to_date');
        $requestFilter = $this->getRequest()->getParam('request');

        $fromDate = "1970-01-01 00:00:00";
        $toDate = date('Y-m-d 23:59:59');

        if (isset($fromDateRequest) && $fromDateRequest != "") {
            $fromDate = $fromDateRequest;
        }

        if (isset($toDateRequest) && $toDateRequest != "") {
            $toDate = $toDateRequest;
        }
        
        $receipts->addFieldToFilter('DATE', array(
            'from' => $fromDate,
            'to' => $toDate,
            'date' => true,
        ));

        if (isset($requestFilter) && $requestFilter != "") {
			$receipts->addFieldToFilter('REQUEST_ID', trim($requestFilter));
		}
        
        $html = "";
		$result = array();
        $result['count_data'] = count($receipts);

        foreach ($receipts as $receipt) {

			$currentDate = date($receipt->getData('DATE'));
            $newDate = date("d/m/Y", strtotime($currentDate));

			$html .= "<tr>";
			$html .= "<td>". $newDate ."</td>";
			$html .= "<td>". $receipt->getData('REQUEST_ID') ."</td>";
			$html .= "<td>". $receipt->getData('ORDER_QTY') ."</td>";
			$html .="<td><a href='". $urlInterface->getUrl('dsfhcustomer/receipt/detail')."request/".$receipt->getData('REQUEST_ID') ."'>" .__('View'). "</a></td>";
			$html .= "</tr>";
		}
        
        $result['content'] = $html;

        $response = $this->_resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
            ->setData([
                'data' => $result
            ]);

        return $response;
    }
}