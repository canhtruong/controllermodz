<?php 
namespace Os\DsfhCustomer\Api;
 
 
interface ApprovedManagementInterface {

	/**
	 * GET for Post api
	 * @param string $approved
	 * @return string
	 */

	public function createApproved($approved);


	/**
	 * GET for Post api
	 * @param string $approved
	 * @return string
	 */

	public function updateApproved($approved);

	/**
	 * GET for Post api
	 * @param string $approved
	 * @return string
	 */

	public function deleteApproved($approved);
}
