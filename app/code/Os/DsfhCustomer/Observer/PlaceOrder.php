<?php
namespace Os\DsfhCustomer\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteFactory;
use Psr\Log\LoggerInterface;
 
class PlaceOrder implements ObserverInterface
{
    /**
    * @var \Psr\Log\LoggerInterface
    */
    protected $_logger;
 
    /**
    * @var \Magento\Customer\Model\Session
    */
    protected $quoteFactory;
 
    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
 
    public function __construct(LoggerInterface $logger,
        QuoteFactory $quoteFactory) {
        $this->_logger = $logger;
        $this->quoteFactory = $quoteFactory;
    }
 
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $quoteId = $order->getQuoteId();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $tableNamequte = $resource->getTableName('quote'); //gives table name with prefix
        $sqlselect = "Select * FROM " . $tableNamequte . " WHERE entity_id =". $quoteId .";";;
        $result = $connection->fetchAll($sqlselect);
        $customerpo = "";
        foreach ($result as $resul) {
            $customerpo = $resul['tm_field1'];
        }
        if($customerpo == ""){} else {
            $tableName = $resource->getTableName('sales_order'); //gives table name with prefix
            $dsfh_customer_suggestedTable = $resource->getTableName('dsfh_customer_suggested');
            

                $customerSession = $objectManager->create('Magento\Customer\Model\Session'); 
            
                $orgId = $customerSession->getSuggestedOrderOrgId();
                $suggestedOrderId = $customerSession->getSuggestedOrderNumber();
        
                if(!empty($suggestedOrderId) ){
                $requestUpdate = " , `orderrequesid` = '".$suggestedOrderId."' ";
                $connection->query("UPDATE ".$dsfh_customer_suggestedTable." SET CONVERTED_TO_SALES_ORDER = '1' WHERE REQUEST_ID = " . $suggestedOrderId . ";");
                }else{
                $requestUpdate = "";    
                }

        
            $connection = $resource->getConnection();
            $sql = "UPDATE ".$tableName." SET `tm_field1` = '".$customerpo."' ".$requestUpdate." WHERE `entity_id` = ".$order->getId().";";
            $connection->query($sql);

            $customerSession->setSuggestedOrderOrgId('');
            $customerSession->setSuggestedOrderNumber('');
            
        }
        
    }
}