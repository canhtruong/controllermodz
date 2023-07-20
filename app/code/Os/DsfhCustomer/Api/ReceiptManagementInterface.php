<?php 
namespace Os\DsfhCustomer\Api;
 
interface ReceiptManagementInterface {

	/**
	 * GET for Post api
	 * @param string $receipt
	 * @return string
	 */

	public function createReceipt($receipt);


	/**
	 * GET for Post api
	 * @param string $receipt
	 * @return string
	 */

	public function updateReceipt($receipt);

	/**
	 * GET for Post api
	 * @param string $receipt
	 * @return string
	 */

	public function deleteReceipt($receipt);
}
