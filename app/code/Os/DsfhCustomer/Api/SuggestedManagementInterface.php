<?php 
namespace Os\DsfhCustomer\Api;
 
interface SuggestedManagementInterface {

	/**
	 * GET for Post api
	 * @param string $suggested
	 * @return string
	 */

	public function createSuggested($suggested);


	/**
	 * GET for Post api
	 * @param string $suggested
	 * @return string
	 */

	public function updateSuggested($suggested);

	/**
	 * GET for Post api
	 * @param string $suggested
	 * @return string
	 */

	public function deleteSuggested($suggested);
}
