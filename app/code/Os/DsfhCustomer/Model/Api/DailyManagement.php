<?php 
namespace Os\DsfhCustomer\Model\Api;

use Psr\Log\LoggerInterface;
use Os\DsfhCustomer\Api\DailyManagementInterface;

class DailyManagement implements DailyManagementInterface
{
	protected $_logger;
	
	public function __construct(
		LoggerInterface $logger,
		\Os\DsfhCustomer\Model\Daily $dailyModel
    ) {
		$this->logger = $logger;
		$this->dailyModel = $dailyModel;
    }

	public function createDaily($daily){
        try {
    		$data = json_decode($daily,true);
    		
    		$model = $this->dailyModel;
    		$model->setData($data);
    		$model->save();
    		
    		return $model->getData('DSFH_CUSTOMER_ID');
        } catch (Mage_Core_Exception $e) {
        	return 'Daily on-hand stock data. Details in error message.';
    	}
	}

	public function updateDaily($daily)
	{
		$daily = json_decode($daily);
		if (isset($daily->DSFH_CUSTOMER_ID)) {

			$id = $daily->DSFH_CUSTOMER_ID;
			$model = $this->dailyModel->load($id);

			if ($model->getId()) {

				$data = array();
				$data['DSFH_REQUEST_ID'] = $daily->DSFH_REQUEST_ID;
				$data['ONHAND_DATE'] = $daily->ONHAND_DATE;
				$data['CUSTOMER_ID'] = $daily->CUSTOMER_ID;
				$data['CUSTOMER_NUMBER'] = $daily->CUSTOMER_NUMBER;
				$data['CUSTOMER_NAME'] = $daily->CUSTOMER_NAME;
				$data['CUSTOMER_ORGANIZATION_ID'] = $daily->CUSTOMER_ORGANIZATION_ID;
				$data['CUSTOMER_ITEM_ID'] = $daily->CUSTOMER_ITEM_ID;
				$data['CUSTOMER_ITEM_NUMBER'] = $daily->CUSTOMER_ITEM_NUMBER;
				$data['CUSTOMER_ITEM_DESCRIPTIOA'] = $daily->CUSTOMER_ITEM_DESCRIPTIOA;
				$data['PRIMARY_UOM_CODE'] = $daily->PRIMARY_UOM_CODE;
				$data['SUBINVENTORY'] = $daily->SUBINVENTORY;
				$data['LOT'] = $daily->LOT;
				$data['ORIGINATION_DATE'] = $daily->ORIGINATION_DATE;
				$data['EXPIRY_DATE'] = $daily->EXPIRY_DATE;
				$data['QTY'] = $daily->QTY;
				$data['STATUS'] = $daily->STATUS;
				$data['CREATION_DATE'] = $daily->CREATION_DATE;
				$data['CREATION_BY'] = $daily->CREATION_BY;
				$data['LAST_UPDATE_DATE'] = $daily->LAST_UPDATE_DATE;
				$data['LAST_UPDATE_BY'] = $daily->LAST_UPDATE_BY;
				$data['SEQID'] = $daily->SEQID;

				$model->setData($data);
				$model->setId($id);
				$model->save();
				return $model->getData();
			} else {
				return "DSFH_CUSTOMER_ID Not Found !";
			}

		} else {
			return "DSFH_CUSTOMER_ID Not Found !";
		}
	}

	public function deleteDaily($daily)
	{
		$daily = json_decode($daily);
		if (isset($daily->id)) {
			$id = $daily->id;
			$model = $this->dailyModel->load($id);
			if ($model->getId()) {

				$model->delete();
				return "Your daily on hand stock has been deleted !. Id: ". $id;
				
			} else {
				return "DSFH_CUSTOMER_ID Not Found !";
			}
		} else {
			return "DSFH_CUSTOMER_ID Not Found !";
		}
	}

}
