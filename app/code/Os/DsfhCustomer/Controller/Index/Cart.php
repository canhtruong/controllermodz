<?php

namespace Os\DsfhCustomer\Controller\Index;

use Magento\Framework\App\Http\Context as AuthContext;

use Magento\Framework\UrlInterface;

use Magento\Framework\App\Filesystem\DirectoryList;

class Cart extends \Magento\Framework\App\Action\Action

{

    protected $_pageFactory;

    private $authContext;

    private $invoicePdf;

    protected $_urlInterface; 

    public function __construct(

        \Magento\Framework\App\Action\Context $context,

        \Magento\Framework\View\Result\PageFactory $pageFactory,

        \Os\DsfhCustomer\Model\Order\Pdf\InvoicePdf $invoicePdf,

        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,

        AuthContext $authContext,

        UrlInterface $urlInterface
    )
    {

        $this->_pageFactory = $pageFactory;

        $this->authContext = $authContext;

        $this->invoicePdf = $invoicePdf;

        $this->fileFactory = $fileFactory;

        $this->_urlInterface = $urlInterface;

        return parent::__construct($context);

    }



    public function execute()

    {
        if($this->getRequest()->getParam('paypost')){
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://test-dot-last-mile-delivery-49da9.appspot.com/lmdservice/api/magento/',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "orderNumber": '.$this->getRequest()->getParam('id').', 
                "paymentAmount": '.$this->getRequest()->getParam('amount').',
                "paymentStatus": "PAID"
            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic TG1kQWRtaW46MTIzNDU2Nzg='
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            
            $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
 
	        $tableNamesuggested = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY_STATUS');

	        $query = "UPDATE " . $tableNamesuggested . " SET `flagpost` = '1' WHERE `id` = ".$this->getRequest()->getParam('idstatus');
		    $connection->query($query);
		    
            echo 1;die;
            
        } else {
            
        
        
            if($this->getRequest()->getParam('payfort')){
                $ordecu = array();
                $ordecu[] = $this->getRequest()->getParam('id');
                $response = $this->fileFactory->create(
                    'Order.pdf',
                    $this->invoicePdf->getPdf3($ordecu)->render(),
                    DirectoryList::VAR_DIR,
                    'application/pdf'
                );
                return $response;
            } else {    
                if($this->getRequest()->getParam('id')){
                    $ordecu = array();
                    $ordecu[] = $this->getRequest()->getParam('id');
                    $response = $this->fileFactory->create(
                        'Order.pdf',
                        $this->invoicePdf->getPdf2($ordecu)->render(),
                        DirectoryList::VAR_DIR,
                        'application/pdf'
                    );
                    return $response;
    
                } else {
                    $ordecu = array();
                    $response = $this->fileFactory->create(
                        'Order.pdf',
                        $this->invoicePdf->getPdf($ordecu)->render(),
                        DirectoryList::VAR_DIR,
                        'application/pdf'
                    );
                    return $response;
                }
            
    
            }
        }

    }

}