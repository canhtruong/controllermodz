<?php 
namespace Os\DsfhCustomer\Model\Api;
use Magento\Framework\DataObject;
use Os\DsfhCustomer\Api\UpdateappversionManagementInterface;

/**
 * Class UpdateappversionManagement
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateappversionManagement implements UpdateappversionManagementInterface
{ 


    protected $dataObjectFactory;

    public function __construct(
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    ) {
    
        $this->dataObjectFactory = $dataObjectFactory;
        
    }


    public function updateappversion() {
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('sales_order'); //gives table name with prefix
        $_order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($_REQUEST['id']);
        
        //Select Data from table
        $sql = "Select * FROM " . $tableName . " WHERE entity_id =".$_REQUEST['id'];
        $resultsql = $connection->fetchAll($sql); // gives associated array, table fields as key in array.

        $result1 = $this->dataObjectFactory->create();    
        foreach ($resultsql as $item){
            $result1->setData('ttmfield', $item['tm_field1']);
            $result1->setData('tmainooracleid', $item['tmaino_oracleid']);
            $result1->setData('billingtaminoid', $item['billing_tamino_id']);
            $result1->setData('firecheckoutdeliverydate', $item['firecheckout_delivery_date']);
            $result1->setData('shippingtaminoid', $item['shipping_tamino_id']);
        }
    
        $tableNamequte = $resource->getTableName('sales_order_address'); //gives table name with prefix
        $sqlselect = "Select * FROM " . $tableNamequte . " WHERE entity_id =". $_order->getBillingAddressId() .";";;
        $result = $connection->fetchAll($sqlselect);
        $customerpobill = "";
        foreach ($result as $resul) {
            $customerpobill = $resul['customer_address_id'];
         }
        
        
        $tableNamequte = $resource->getTableName('sales_order_address'); //gives table name with prefix
        $sqlselect = "Select * FROM " . $tableNamequte . " WHERE entity_id =". $_order->getShippingAddressId() .";";;
        $result = $connection->fetchAll($sqlselect);
        $customerpoship = "";
        foreach ($result as $resul) {
            $customerpoship = $resul['customer_address_id'];
         }
        
        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($_order->getCustomerId());
        
        if($customerObj->getTmainoOracleid()){
            $result1->setData('tmainooracleid', $customerObj->getTmainoOracleid());
        }
        foreach ($customerObj->getAddresses() as $address)
        {
            if($address->getId() == $customerpobill){
                $customerAddress = $address->toArray();
                $result1->setData('billingtaminoid', $customerAddress['tamino_id']);
            }
            
            if($address->getId() == $customerpoship){
                $customerAddress = $address->toArray();
                $result1->setData('shippingtaminoid', $customerAddress['tamino_id']);
            }
        }
        
        return $result1;
    }

}
