<?php

namespace Os\DsfhCustomer\Controller\Filter;

use Magento\Framework\Controller\ResultFactory;

class Approved extends \Magento\Framework\App\Action\Action

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

        $from_date = $this->getRequest()->getParam('from_date');
        $to_date = $this->getRequest()->getParam('to_date');
        $release = $this->getRequest()->getParam('release');
        $request = $this->getRequest()->getParam('request');

        $approvedBlock = $objectManager->get('\Os\DsfhCustomer\Block\Approved');

        $approveds = $approvedBlock->getApprovedDefaultCollection();

        $fromDate = "1970-01-01 00:00:00";
        $toDate = date('Y-m-d 23:59:59');
        
        if ($from_date != "" && $to_date == "") {
			$fromDate = $from_date;
		} elseif ($to_date != "" && $from_date == "") {
			$toDate = $to_date;
		} elseif ($from_date != "" && $to_date != "") {
			$fromDate = $from_date;
			$toDate = $to_date;
        }

        $fromDate = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $fromDate)));
        $toDate = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $toDate)));
        
        $approveds->addFieldToFilter('DATE', array(
            'from' => $fromDate,
            'to' => $toDate,
            'date' => true,
        ));

        if ($release != "") {
			$approveds->addFieldToFilter('RELEASE_NUMBER', trim($release));
		}

		if ($request != "") {
			$approveds->addFieldToFilter('REQUEST_ID', trim($request));
        }
        
        
        $html = "";
		$result = array();
        $result['count_data'] = count($approveds);

        foreach ($approveds as $approved) {

			$currentDate = date($approved->getData('DATE'));
			$dateToStr = strtotime($currentDate);
			$newDate = date("m/d/Y H:i:s", $dateToStr);

			$html .= "<tr>";
			$html .= "<td>". $newDate ."</td>";
			$html .= "<td>". $approved->getData('REQUEST_ID') ."</td>";
			$html .= "<td>". $approved->getData('RELEASE_NUMBER') ."</td>";
			$html .= "<td>". $approved->getData('TOTAL') ."</td>";
			$html .="<td><a href='" . $urlInterface->getUrl('dsfhcustomer/approved/detail') . "release/" .$approved->getData('RELEASE_NUMBER')."'>"."View"."</a></td>";
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