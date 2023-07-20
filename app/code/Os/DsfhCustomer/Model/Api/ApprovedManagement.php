<?php 
namespace Os\DsfhCustomer\Model\Api;

use Psr\Log\LoggerInterface;
use Os\DsfhCustomer\Api\ApprovedManagementInterface;

class ApprovedManagement implements ApprovedManagementInterface
{
	protected $_logger;
	
	public function __construct(
		LoggerInterface $logger,
		\Os\DsfhCustomer\Model\Approved $approvedModel
    ) {
		$this->logger = $logger;
		$this->approvedModel = $approvedModel;
    }

	public function createApproved($approved){
	    try {
    		$data = json_decode($approved,true);
    		
    		$model = $this->approvedModel;
    		$model->setData($data);
    		$model->save();
    		
    		return $model->getData("DSFH_CUSTOMER_APPROVED_ID");
	    } catch (Mage_Core_Exception $e) {
        	return 'Approved data. Details in error message.';
    	}
	}

	public function updateApproved($approved)
	{
	    $data = json_decode($approved,true);
				var_dump($data);die;
	    $approved = json_decode($approved);
		if (isset($approved->DSFH_CUSTOMER_APPROVED_ID)) {
  
			$id = $approved->DSFH_CUSTOMER_APPROVED_ID;
			$model = $this->approvedModel->load($id);

			if ($model->getId()) {
				$data = json_decode($approved,true);
				
				$model->setData($data);
				$model->setId($id);
				$model->save();
				return $approvedModel->getData('DSFH_CUSTOMER_APPROVED_ID');
			} else {
				return 'Approved Release data. Details in error message.';
			}

		}
	}

	public function deleteApproved($approved)
	{
		$approved = json_decode($approved);
		if (isset($approved->id)) {
			$id = $approved->id;
			$model = $this->approvedModel->load($id);
			if ($model->getId()) {

				$model->delete();
				return "Your approved has been deleted !. Id: ". $id;
				
			} else {
				return "DSFH_CUSTOMER_APPROVED_ID Not Found !";
			}
		} else {
			return "DSFH_CUSTOMER_APPROVED_ID Not Found !";
		}
	}

}
