<?php

namespace Os\DsfhCustomer\Controller\Filter;

use Magento\Framework\Controller\ResultFactory;

class Suggested extends \Magento\Framework\App\Action\Action

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

        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');

        $suggestedBlock = $objectManager->get('\Os\DsfhCustomer\Block\Suggested');
        
        $suggesteds = $suggestedBlock->getSuggestedCollection();

        $fromDate = "1970-01-01 00:00:00";
        $toDate = date('2025-01-01 23:59:59');

        if ($this->getRequest()->getParam('from_date')) {
            $fromDate = date('Y-m-d 00:00:00', strtotime($this->getRequest()->getParam('from_date')));
        }

        if ($this->getRequest()->getParam('to_date')) {
            $toDate = date('Y-m-d 23:59:59', strtotime($this->getRequest()->getParam('to_date')));
        }

        $suggesteds->addFieldToFilter('TRX_DATE', array(
            'from' => $fromDate,
            'to' => $toDate,
            'date' => true,
        ));

        if ($this->getRequest()->getParam('request')) {
			$suggesteds->addFieldToFilter('REQUEST_ID', trim($this->getRequest()->getParam('request')));
        }
        
        if ($this->getRequest()->getParam('name')) {
            $suggesteds->addFieldToFilter('CUSTOMER_ITEM_DESCRIPTIOA', array('like'=> '%'.$this->getRequest()->getParam('name').'%'));
        }

        $suggesteds->getSelect()->columns(['TOTAL' => new \Zend_Db_Expr('SUM(UNIT_PRICE*QTY)')])->group('REQUEST_ID')->order('TRX_DATE DESC');
        
        $html = "";
		$result = array();
        $result['count_data'] = count($suggesteds);

        foreach ($suggesteds as $suggested) {

			$newDate = date("d/m/Y", strtotime($suggested->getData('TRX_DATE')));

			$html .= "<tr>";
			$html .= "<td>". $newDate ."</td>";
			$html .= "<td>". $suggested->getData('REQUEST_ID') ."</td>";
			$html .= "<td>". $priceHelper->currency($suggested->getData('TOTAL'), true, false) ."</td>";
			$html .= '<td style="border-color: #dcdcdc;">';
			if($suggested->getData('CONVERTED_TO_SALES_ORDER') == '1'){
			$html .= '<span class="fa fa-check-circle"></span>';
			}else{
			$html .= '<span class="fa fa-times-circle"></span>';
			}
			$html .= "</td>";

// 			$html .= "<td><a href='". $urlInterface->getUrl('dsfhcustomer/suggested/detail')."request/".$suggested->getData('REQUEST_ID')."'>" .__('View'). "</a></td>";

			$html .= "<td><a href='". $urlInterface->getUrl('dsfhcustomer/suggested/detail')."request/".$suggested->getData('REQUEST_ID')."'>" .__('View'). "</a>";
			if($suggested->getData('CONVERTED_TO_SALES_ORDER') <> '1'){
			$html .= "<a href='". $urlInterface->getUrl('dsfhcustomer/suggested/detail')."request/".$suggested->getData('REQUEST_ID')."&org_id=".$suggested->getData('ORG_ID')."'>
										/ Convert to sales order
									</a>";
			}
			$html .= "</td>";
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