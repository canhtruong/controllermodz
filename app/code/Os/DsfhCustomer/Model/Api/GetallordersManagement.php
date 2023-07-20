<?php 
namespace Os\DsfhCustomer\Model\Api;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\DataObject;
use Os\DsfhCustomer\Api\GetallordersManagementInterface;
/**
 * Class GetallordersManagement
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GetallordersManagement implements GetallordersManagementInterface
{ 


    protected $dataObjectFactory;

    public function __construct(
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    ) {
        $this->dataObjectFactory = $dataObjectFactory;        
    }


    public function getallorders() {
        
        $result = $this->dataObjectFactory->create(); 
        $result->setData('status', 'false');
        $result->setData('error', 'a');
        
        return $result;
    }

}
