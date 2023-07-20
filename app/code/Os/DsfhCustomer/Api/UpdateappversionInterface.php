<?php 
namespace Os\DsfhCustomer\Api;
 
/**
 * UpdateappversionInterface interface
 *
 * @api
 * @since 100.0.2
 */
interface UpdateappversionInterface
{
    
    /**
    * @return \Os\DsfhCustomer\Api\UpdateappversionInterface[]
     */
    public function getTtmfield();

    /**
     * @param string $ttmfield
     * @return $this
     */
    public function setTtmfield($ttmfield);


    /**
    * @return \Os\DsfhCustomer\Api\UpdateappversionInterface[]
     */
    public function getTmainooracleid();

    /**
     * @param string $tmainooracleid
     * @return $this
     */
    public function setTmainooracleid($tmainooracleid);


    /**
    * @return \Os\DsfhCustomer\Api\UpdateappversionInterface[]
     */
    public function getBillingtaminoid();

    /**
     * @param string $billingtaminoid
     * @return $this
     */
    public function setBillingtaminoid($billingtaminoid);


    /**
    * @return \Os\DsfhCustomer\Api\UpdateappversionInterface[]
     */
    public function getFirecheckoutdeliverydate();

    /**
     * @param string $firecheckoutdeliverydate
     * @return $this
     */
    public function setFirecheckoutdeliverydate($firecheckoutdeliverydate);

    /**
    * @return \Os\DsfhCustomer\Api\UpdateappversionInterface[]
     */
    public function getShippingtaminoid();

    /**
     * @param string $shippingtaminoid
     * @return $this
     */
    public function setShippingtaminoid($shippingtaminoid);

}
