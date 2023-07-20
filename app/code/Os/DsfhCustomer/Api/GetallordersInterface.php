<?php 
namespace Os\DsfhCustomer\Api;
 
/**
 * GetallordersInterface interface
 *
 * @api
 * @since 100.0.2
 */
interface GetallordersInterface
{
    

    /**
    * @return \Os\DsfhCustomer\Api\GetallordersInterface[]
     */
    public function getError();

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error);

    /**
    * @return \Os\DsfhCustomer\Api\GetallordersInterface[]
     */
    public function getStatus();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

}
