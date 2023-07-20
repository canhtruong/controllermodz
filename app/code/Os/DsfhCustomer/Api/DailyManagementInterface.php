<?php 
namespace Os\DsfhCustomer\Api;
 
interface DailyManagementInterface {

	/**
	 * GET for Post api
	 * @param string $daily
	 * @return string
	 */

	public function createDaily($daily);


	/**
	 * GET for Post api
	 * @param string $daily
	 * @return string
	 */

	public function updateDaily($daily);

	/**
	 * GET for Post api
	 * @param string $daily
	 * @return string
	 */

	public function deleteDaily($daily);
}
