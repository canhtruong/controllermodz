<?php 
namespace Os\DsfhCustomer\Model\Api;

use Psr\Log\LoggerInterface;
use Os\DsfhCustomer\Api\SuggestedManagementInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Integration\Model\Oauth\TokenFactory as TokenModelFactory;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Math\Random;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Intl\DateTimeFactory;
class SuggestedManagement implements SuggestedManagementInterface
{
	protected $_logger;
	
	protected $storeManagerInterface;

	protected $_transportBuilder;

	protected $scopeConfig;
	protected $inlineTranslation;
	protected $_customerFactory;
    private $tokenModelFactory;
    private $customerRepository;
    private $customerRegistry;
    private $dateTimeFactory;
    private $mathRandom;
	const XML_PATH_REGISTER_EMAIL_IDENTITY = 'customer/create_account/email_identity';
	
	public function __construct(
		LoggerInterface $logger,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
		TransportBuilder $transportBuilder,
		ScopeConfigInterface $scopeConfig,
		\Os\DsfhCustomer\Model\Suggested $suggestedModel,
		CustomerRepositoryInterface $customerRepository,
        Random $mathRandom,
        TokenModelFactory $tokenModelFactory,
        CustomerRegistry $customerRegistry,
        StateInterface $state,
        DateTimeFactory $dateTimeFactory
    ) {
		$this->logger = $logger;
		$this->suggestedModel = $suggestedModel;
		$this->storeManagerInterface = $storeManager;
		$this->scopeConfig = $scopeConfig;
		$this->_transportBuilder = $transportBuilder;

		$this->tokenModelFactory = $tokenModelFactory;
        $this->mathRandom = $mathRandom;
        $this->customerRepository = $customerRepository;
        $this->customerRegistry = $customerRegistry;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->_customerFactory = $customerFactory;
        $this->inlineTranslation = $state;
    }

	public function createSuggested($suggested){
        
		$data = json_decode($suggested,true);
		   
        if($data['CHECK_FLA'] == 0){
            try {
        		$model = $this->suggestedModel;
        		$model->setData($data);
        		$model->save();
        		return $model->getId();
            } catch (Mage_Core_Exception $e) {
            	return 'Suggested Order data. Details in error message.';
        	}
        } else {
            try {
                
    			$orgriva = trim($data['customerid']);
    			$orgriva = str_replace('"', '', $orgriva);
                $orgriva = str_replace("'", "", $orgriva);
    			$orgriva = str_replace("md", "", $orgriva);
    			$customera = $this->_customerFactory->create()->getCollection()
                    ->addAttributeToSelect("*")
                    ->addFieldToFilter('orgid', $orgriva)->getFirstItem();
                $cusdata = $customera->getData();
    			
                
                if($cusdata['email']){
    				
    				$from = ['email' => 'support@tamerstore.com', 'name' => 'Tamer Group'];
                    $this->inlineTranslation->suspend();
                    $toEmail = "loran.s@live.com";
                    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                    $templateOptions = [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => 1
                    ]; 
                    
                    $allitems = $data['valueemail'];
                    $allitemsnew = array();
                    foreach ($allitems as $itemid) {
                        $itemid = str_replace('"', '', $itemid);
                        $itemid = str_replace("'", "", $itemid);
                        $allitemsnew[] = $itemid;
                    }
                    $allitems = $allitemsnew;
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $firstItem = $objectManager->create('\Os\DsfhCustomer\Model\Suggested')->load($allitems[0]);
                    $requestidss = $firstItem->getData('REQUEST_ID');
                    $currentDate = date($firstItem->getData('TRX_DATE'));
            		$DateToStr = strtotime($currentDate);
            		$newDate = date("d/m/Y", $DateToStr);
                    $total = 0;
                    $datalistt = array();
                    foreach ($allitems as $itemid) {
							
							$suggested = $objectManager->create('\Os\DsfhCustomer\Model\Suggested')->load($itemid);
							$amount = $suggested->getData('UNIT_PRICE')*$suggested->getData('QTY');
							$total = $total + $amount;
							$itemdetail = array();
							$itemdetail['sku'] = $suggested->getData('TAMER_ITEM_NO');
							$itemdetail['customeritem'] = $suggested->getData('CUSTOMER_ITEM_NUMBER');
							$itemdetail['description'] = $suggested->getData('CUSTOMER_ITEM_DESCRIPTIOA');
							$itemdetail['uom'] = $suggested->getData('UOM_CODE');
							$itemdetail['qty'] = $suggested->getData('QTY');
							$itemdetail['price'] = $suggested->getData('UNIT_PRICE');
							$itemdetail['amount'] = $objectManager->create('Magento\Framework\Pricing\Helper\Data')->currency($amount,true,false);
							$datalistt[] = $itemdetail;
                    }
                    $total = $objectManager->create('Magento\Framework\Pricing\Helper\Data')->currency($total,true,false);
                    $templateVars = [
                            'customername' => $cusdata['firstname'].' '.$cusdata['lastname'],
                            'reuqestvalue' => $requestidss,
                            'newDate' => $newDate,
                            'dataitems' => $datalistt,
                            'total' => $total
                        ]; 
                    $transport = $this->_transportBuilder->setTemplateIdentifier('suggestitem', $storeScope)
                        ->setTemplateOptions($templateOptions)
                        ->setTemplateVars($templateVars)
                        ->setFrom($from)
                        ->addTo($toEmail)
                        ->getTransport();
                    $transport->sendMessage();
                    $this->inlineTranslation->resume();
                    
    				if($cusdata['email2']){
    					$listemail2 = $cusdata['email2'];
    					$listemail2 = explode(",", $listemail2);
    					
    					$textt = 'Sent an email to '.$cusdata['email'];
    					foreach ($listemail2 as $listemai) {
    						
    						$from = ['email' => 'support@tamerstore.com', 'name' => 'Tamer Group'];
                            $this->inlineTranslation->suspend();
                            $toEmail = "loran.s@live.com";
                            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                            $templateOptions = [
                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                'store' => 1
                            ];
                            
                            $templateVars = [
                                    'customername' => $cusdata['firstname'].' '.$cusdata['lastname'],
                                    'reuqestvalue' => $requestidss,
                                    'newDate' => $newDate,
                                    'dataitems' => $datalistt,
                                    'total' => $total
                                ]; 
                            
                            $transport = $this->_transportBuilder->setTemplateIdentifier('suggestitem', $storeScope)
                                ->setTemplateOptions($templateOptions)
                                ->setTemplateVars($templateVars)
                                ->setFrom($from)
                                ->addTo($toEmail)
                                ->getTransport();
                            $transport->sendMessage();
                            $this->inlineTranslation->resume();
                             
    						$textt = $textt.', '.$listemai;
    					}
    					

                        if($cusdata['payment_cbd1']){
                            $listemail2 = $cusdata['payment_cbd1'];
                            $listemail2 = explode(",", $listemail2);
                            
                            foreach ($listemail2 as $listemai) {
                                
                                $from = ['email' => 'support@tamerstore.com', 'name' => 'Tamer Group'];
                                $this->inlineTranslation->suspend();
                                $toEmail = "loran.s@live.com";
                                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                                $templateOptions = [
                                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                    'store' => 1
                                ];
                                
                                $templateVars = [
                                        'customername' => $cusdata['firstname'].' '.$cusdata['lastname'],
                                        'reuqestvalue' => $requestidss,
                                        'newDate' => $newDate,
                                        'dataitems' => $datalistt,
                                        'total' => $total
                                    ]; 
                                
                                $transport = $this->_transportBuilder->setTemplateIdentifier('suggestitem', $storeScope)
                                    ->setTemplateOptions($templateOptions)
                                    ->setTemplateVars($templateVars)
                                    ->setFrom($from)
                                    ->addTo($toEmail)
                                    ->getTransport();
                                $transport->sendMessage();
                                $this->inlineTranslation->resume();
                                 
                                $textt = $textt.', '.$listemai;
                            }
                            
                        }

    					return $textt;
    				} else {
    					return 'Sent an email to '.$cusdata['email'];	
    				}
    			} else {
    				return 'No customer has same ORG_ID value: '.trim($data['customerid']);
    			}
    			
            } catch (Mage_Core_Exception $e) {
            	return 'can not send an email to the customer has ORG_ID value'.trim($data['customerid']);
        	}    
        }		
		
	}

	public function updateSuggested($suggested)
	{
		$suggested = json_decode($suggested,true);
		
		if($suggested['flag'] === 'paytabs-sync-flag'){
    
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
	        $connection = $resource->getConnection();

	        $tableNamesuggested = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY_STATUS');

	        $query = "UPDATE " . $tableNamesuggested . " SET `flag` = '1' WHERE `id` = ".$suggested['id'];
		    $connection->query($query);
		    
	        return 1;

		} else if($suggested['flag'] === 'paytabs-sync'){

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
	        $connection = $resource->getConnection();

	        $tableNamesuggested = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY_STATUS');
	        $sqlselect_suggested = "Select * FROM " . $tableNamesuggested . " WHERE flag = 0;";
	        $suggesteds = $connection->fetchAll($sqlselect_suggested);
	        $totalpay_amount = 0;
	        $listreul = array();
	        foreach($suggesteds as $suggestItem){
	            
	            $datetime1 = Date('Y-m-d h:i:s');
                $datetime1 = (string)$datetime1;
	        	$suggestItem['entry_date'] = $datetime1;
	        	
	        	$ordernumber = $suggestItem['merchant_reference'];
            	$ordernumber = explode("-", $ordernumber);
            	$ordernumber = $ordernumber[1];
            	
            	$tableNamesuggesteda = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY');
                $sqlselect_suggestedaa = "Select * FROM " . $tableNamesuggesteda . " WHERE ORDER_NUMBER ='". $ordernumber ."';";
                $suggestedsaa = $connection->fetchAll($sqlselect_suggestedaa);
                
                foreach($suggestedsaa as $suggestItemda){
                    $suggestItem['org_id'] = $suggestItemda['ORG_ID'];
                    $suggestItem['cust_account_id'] = $suggestItemda['CUST_ACCOUNT_ID'];
                    $suggestItem['cust_name'] = $suggestItemda['CUST_NAME'];
                }
                
                $listreul[] = $suggestItem;
	        }
	        return json_encode($listreul);

		} else if($suggested['flag'] === 'paytabs'){

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
	        $connection = $resource->getConnection();

			$query = "INSERT INTO `TMR_INVC_CM_PAY_GATEWAY` (`ROW_ID`, `CUSTOMER_TRX_ID`, `TRX_NUMBER`, `TRX_DATE`, `SET_OF_BOOKS_ID`, `ORG_ID`, `BILL_TO_CUSTOMER_ID`, `BILL_TO_SITE_USE_ID`, `SHIP_TO_CUSTOMER_ID`, `SHIP_TO_SITE_USE_ID`, `TERM_ID`, `TERM_DESC`, `DUE_DATE`, `CUST_TRX_TYPE_ID`, `CUST_TRX_TYPE_DESC`, `CUST_TRX_CLASS`, `BATCH_ID`, `BATCH_SOURCE_ID`, `BATCH_SOURCE_DESC`, `PRIMARY_SALESREP_ID`, `ORDER_NUMBER`, `INVOICE_AMOUNT`, `INVOICE_ADJUSTMENT`, `INVOICE_BALANCE`, `CURRENCY_CODE`, `EXCHANGE_RATE`, `INTG_STATUS`, `LAST_UPDATE_DATE`, `LAST_UPDATED_BY`, `CREATION_DATE`, `CREATED_BY`, `TRX_RECEIPT_ID`, `TRX_RECEIPT_DATE`, `CUST_ACCOUNT_ID`, `CUST_NAME`, `KBD_TRX_ID`, `KBD_TRX_NUMBER`, `KBD_TRX_AMOUNT`) VALUES (NULL,'".$suggested['CUSTOMER_TRX_ID']."','".$suggested['TRX_NUMBER']."','".$suggested['TRX_DATE']."','".$suggested['SET_OF_BOOKS_ID']."','".$suggested['ORG_ID']."','".$suggested['BILL_TO_CUSTOMER_ID']."','".$suggested['BILL_TO_SITE_USE_ID']."','".$suggested['SHIP_TO_CUSTOMER_ID']."','".$suggested['SHIP_TO_SITE_USE_ID']."','".$suggested['TERM_ID']."','".$suggested['TERM_DESC']."','".$suggested['DUE_DATE']."','".$suggested['CUST_TRX_TYPE_ID']."','".$suggested['CUST_TRX_TYPE_DESC']."','".$suggested['CUST_TRX_CLASS']."','".$suggested['BATCH_ID']."','".$suggested['BATCH_SOURCE_ID']."','".$suggested['BATCH_SOURCE_DESC']."','".$suggested['PRIMARY_SALESREP_ID']."','".$suggested['ORDER_NUMBER']."','".$suggested['INVOICE_AMOUNT']."','".$suggested['INVOICE_ADJUSTMENT']."','".$suggested['INVOICE_BALANCE']."','".$suggested['CURRENCY_CODE']."','".$suggested['EXCHANGE_RATE']."','".$suggested['INTG_STATUS']."','".$suggested['LAST_UPDATE_DATE']."','".$suggested['LAST_UPDATED_BY']."','".$suggested['CREATION_DATE']."','".$suggested['CREATED_BY']."','".$suggested['TRX_RECEIPT_ID']."','".$suggested['TRX_RECEIPT_DATE']."','".$suggested['CUST_ACCOUNT_ID']."','".$suggested['CUST_NAME']."','".$suggested['KBD_TRX_ID']."','".$suggested['KBD_TRX_NUMBER']."','".$suggested['KBD_TRX_AMOUNT']."');";
        	$connection->query($query);
        	return 1;

		} else if($suggested['flag'] == 10){
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
            $quoteche = $objectManager->create('Magento\Quote\Api\CartRepositoryInterface')->getActive($suggested['cartid']);
            if ($quoteche->hasItems()) {
                $quoteche->removeAllItems();
            }
            $objectManager->create('Magento\Quote\Api\CartRepositoryInterface')->save($quoteche->collectTotals());
            return "Removed all";
        } else if($suggested['flag'] == 3){
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($suggested['customer']);
            $textd1 = "newval";
            $textd2 = "newval";
            foreach ($customerObj->getAddresses() as $address)
            {
                $customerAddress = $address->toArray();
                if($suggested['shipto'] == $customerAddress['tamino_id']){
                    $textd1 = $textd1.$customerAddress['address_label'];
                }
                if($suggested['billto'] == $customerAddress['tamino_id']){
                    $textd2 = $textd2.$customerAddress['address_label'];
                }
            }
            $finaltxt = $textd1.'canhtn'.$textd2;
            
            return $finaltxt;
            
        } else if($suggested['flag'] == 2){
            $customeraf = $this->_customerFactory->create()->getCollection()
                ->addAttributeToSelect("*")
                ->addFieldToFilter('tmaino_oracleid', $suggested['tmaino_id'])->getFirstItem();
            $cusdatad = $customeraf->getId();
            return $cusdatad;
        } else if($suggested['flag'] == 0){
	    	$newtorken = $this->tokenModelFactory->create()->createCustomerToken($suggested['customer'])->getToken();

	        $customer = $this->customerRepository->getById($suggested['customer']);
	        $passwordLinkToken = $this->mathRandom->getUniqueHash();

 
	        if (!is_string($passwordLinkToken) || empty($passwordLinkToken)) {
	            throw new InputException(
	                __(
	                    'Invalid value of "%value" provided for the %fieldName field.',
	                    ['value' => $passwordLinkToken, 'fieldName' => 'password reset token']
	                )
	            );
	        }
	        if (is_string($passwordLinkToken) && !empty($passwordLinkToken)) {
	            $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
	            $customerSecure->setRpToken($passwordLinkToken);
	            
	            $customerSecure->setRpTokenCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));

	            $customer->setData('ignore_validation_flag', true);
	            $this->customerRepository->save($customer);
	        }

	        return $newtorken;
	    } else {


	    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$customerObj = $objectManager->create('Magento\Customer\Model\Customer')
			->load($suggested['customer']);

	    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
		    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		    $connection = $resource->getConnection();
		    $table = $connection->getTableName('sale_order');

		     
            $tamino_id_ship = "";
            $address_label_ship = "";
            $tamino_id_bill = "";
            $address_label_bill = "";
            foreach ($customerObj->getAddresses() as $address)
            {
                $customerAddress = $address->toArray();
                if($suggested['shipto'] == $customerAddress['tamino_id']){
                    $tamino_id_ship = $customerAddress['tamino_id'];
                    $address_label_ship = $customerAddress['address_label'];
                }
                if($suggested['billto'] == $customerAddress['tamino_id']){
                    $tamino_id_bill = $customerAddress['tamino_id'];
                    $address_label_bill = $customerAddress['address_label'];
                }
            } 
            
		    $orderid = $suggested['orderid'];

		    $query = "UPDATE `sales_order` SET `tm_field1` = '".$suggested['customerpo']."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $query = "UPDATE `sales_order` SET `orderrequesid` = '".$suggested['requestid']."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $query = "UPDATE `sales_order` SET `billing_tamino_id` = '".$tamino_id_bill."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $query = "UPDATE `sales_order` SET `shipping_tamino_id` = '".$tamino_id_ship."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $query = "UPDATE `sales_order` SET `billing_address_label` = '".$address_label_bill."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $query = "UPDATE `sales_order` SET `shipping_address_label` = '".$address_label_ship."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $query = "UPDATE `sales_order` SET `ship_set` = '".$customerObj->getShipSet()."' WHERE `sales_order`.`entity_id` = ".$orderid;
		    $connection->query($query);

		    $order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($orderid);   
	    	return "Order Successs #".$order->getData('increment_id');
	    }
	}

	public function deleteSuggested($suggested)
	{
		$suggested = json_decode($suggested);
		if (isset($suggested->id)) {
			$id = $suggested->id;
			$model = $this->suggestedModel->load($id);
			if ($model->getId()) {

				$model->delete();
				return "Your suggested order has been deleted !. Id: ". $id;
				
			} else {
				return "DSFH_CUSTOMER_SUGGESTED_ID Not Found !";
			}
		} else {
			return "DSFH_CUSTOMER_SUGGESTED_ID Not Found !";
		}
	}

}
