<?php 
namespace Os\DsfhCustomer\Model\Api;

use Psr\Log\LoggerInterface;
use Os\DsfhCustomer\Api\ReceiptManagementInterface;

class ReceiptManagement implements ReceiptManagementInterface
{
	protected $_logger;
	
	public function __construct(
		LoggerInterface $logger,
		\Os\DsfhCustomer\Model\Receipt $receiptModel
    ) {
		$this->logger = $logger;
		$this->receiptModel = $receiptModel;
    }

	public function createReceipt($receipt){
        try {
    		$data = json_decode($receipt,true);
            
    		$model = $this->receiptModel;
    		$model->setData($data);
    		$model->save();
    		
    		return $model->getData('DSFH_CUSTOMER_RECEIPT_ID');
        } catch (Mage_Core_Exception $e) {
        	return 'Receipt  Confirmation data. Details in error message.';
    	}
	}

	public function updateReceipt($receipt)
	{
		$receipt = json_decode($receipt);
		if (isset($receipt->DSFH_CUSTOMER_RECEIPT_ID)) {

			$id = $receipt->DSFH_CUSTOMER_RECEIPT_ID;
			$model = $this->receiptModel->load($id);

			if ($model->getId()) {

				$data = array();
				$data['REQUEST_ID'] = $receipt->REQUEST_ID;
				$data['DATE'] = $receipt->DATE;
				$data['TAMER_ITEM_NUMBER'] = $receipt->TAMER_ITEM_NUMBER;
				$data['CUSTOMER_ITEM_NUMBER'] = $receipt->CUSTOMER_ITEM_NUMBER;
				$data['ITEM_DESCRIPTION'] = $receipt->ITEM_DESCRIPTION;
				$data['UOM'] = $receipt->UOM;
				$data['ORDER_QTY'] = $receipt->ORDER_QTY;
				$data['RECEIVED_QTY'] = $receipt->RECEIVED_QTY;

				$model->setData($data);
				$model->setId($id);
				$model->save();
				return $model->getData();
			} else {
				return "DSFH_CUSTOMER_RECEIPT_ID Not Found !";
			}

		} else {
			return "DSFH_CUSTOMER_RECEIPT_ID Not Found !";
		}
	}

	public function deleteReceipt($receipt)
	{
		$receipt = json_decode($receipt);
		if (isset($receipt->id)) {
			$id = $receipt->id;
			$model = $this->receiptModel->load($id);
			if ($model->getId()) {

				$model->delete();
				return "Your receipt confirmation has been deleted !. Id: ". $id;
				
			} else {
				return "DSFH_CUSTOMER_RECEIPT_ID Not Found !";
			}
		} else {
			return "DSFH_CUSTOMER_RECEIPT_ID Not Found !";
		}
	}

}
