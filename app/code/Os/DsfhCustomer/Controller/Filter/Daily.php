<?php

namespace Os\DsfhCustomer\Controller\Filter;

use Magento\Framework\Controller\ResultFactory;

class Daily extends \Magento\Framework\App\Action\Action

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

        $onHandDate = $this->getRequest()->getParam('onhanddate');

        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        if ($onHandDate != "" && isset($onHandDate)) {
            $fromDate = date('Y-m-d', strtotime($onHandDate));
            $toDate = date('Y-m-d', strtotime($onHandDate));
        }

        $dailyBlock = $objectManager->get('\Os\DsfhCustomer\Block\Daily');
        $dailys = $dailyBlock->getDailyCollection();
        
        $dailys->addFieldToFilter('ONHAND_DATE', array('like' => $fromDate.'%'));
        
        $html = "";
		$result = array();
        $result['count_data'] = count($dailys);

        foreach ($dailys as $daily) {

			$currentOnhandDate = date($daily->getData('ONHAND_DATE'));
            $currentExpiryDate = date($daily->getData('EXPIRY_DATE'));
            $newOnhandDate = date("m/d/Y", strtotime($currentOnhandDate));
            $newExDate = date("m/d/Y", strtotime($currentExpiryDate));

			$html .= "<tr>";
			$html .= "<td>". $currentOnhandDate ."</td>";
			$html .= "<td>". $daily->getData('CUSTOMER_ITEM_ID') ."</td>";
			$html .= "<td>". $daily->getData('CUSTOMER_ITEM_NUMBER') ."</td>";
            $html .= "<td>". $daily->getData('CUSTOMER_ITEM_DESCRIPTIOA') ."</td>";
            $html .= "<td>". $daily->getData('PRIMARY_UOM_CODE') ."</td>";
            $html .= "<td>". $daily->getData('LOT') ."</td>";
            $html .= "<td>". $newExDate ."</td>";
			$html .="<td>". $daily->getData('QTY') ."</td>";
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