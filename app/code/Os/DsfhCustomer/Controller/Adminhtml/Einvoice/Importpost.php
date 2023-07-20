<?php

namespace Os\DsfhCustomer\Controller\Adminhtml\Einvoice;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;
use Bss\FastOrder\Helper\Data;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Shuchkin\SimpleXLSX;
use Magento\Framework\Filesystem\Driver\File;

$include_path = dirname(__FILE__);
$path_to_PHP_ExcelReader = $include_path."/read_xls/Excel/reader.php";
require_once $path_to_PHP_ExcelReader;

require_once __DIR__.'/SimpleXLSX.php';

class Importpost extends \Magento\Backend\App\Action
{
    private $resultPageFactory;
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var Csv
     */
    protected $csv;
    /**
     * @var File
     */
    protected $file;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        Csv $csv,
        File $file
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->directoryList = $directoryList;
        $this->csv = $csv;
        $this->file = $file;
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();   
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        $mediaDirectory = $objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::VAR_DIR);
        $files = $this->getRequest()->getFiles()->toarray();
        if (isset($files['filename']['name']) && $files['filename']['name'] != '') {
            $target = $mediaDirectory->getAbsolutePath('import');
            $target = $target.'/'.$files['filename']['name'];
            if(file_exists($target)){ 
                unlink($target);
            }
            try {
                
                if (!file_exists($mediaDirectory->getAbsolutePath().'import')) {
                    mkdir($mediaDirectory->getAbsolutePath().'import', 0777, true);
                }
                $uploader = $objectManager->create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'filename']);
                $allowed_ext_array = ['csv','xls','xlsx'];
                $uploader->setAllowedExtensions($allowed_ext_array);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $result = $uploader->save($mediaDirectory->getAbsolutePath('import/'));
                $path = $mediaDirectory->getAbsolutePath('import');
                $path = $path.'/'.$files['filename']['name'];


                if (pathinfo($files['filename']['name'], PATHINFO_EXTENSION) == 'xlsx') {

                    if ($xlsx = SimpleXLSX::parse($target)) {

                        $csvLines = array();
                        $ii = 0;
                        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                        $connection = $resource->getConnection();
                        $tableName = $resource->getTableName('dsfh_customer');

                        $query = "TRUNCATE `customers_e_invoice` ";
                        $result = $connection->query($query);

                        foreach($xlsx->rows() as $value){
                            
                            //var_dump($value);die;
                            if($ii == 0){ $ii = 1; 
                                
                            } else {
                                $date1 = $value[8];
                                $date1 = explode(" ", $date1);

                                $query = "INSERT INTO `customers_e_invoice` (`ID`, `CUSTOMER_ID`, `PARTY_ID`, `TRX_DATE`, `CUSTOMER_NUMBER`, `BILL_TO_SITE_USE_ID`, `INVOICE_IDENTIFY_NUMBER`, `BUYER_NAME`, `ORG_ID`, `OPERATING_UNITS`, `FILE_NAME`, `IS_DOWNLOADED`, `INVOICE_CLASS`, `INVOICE_CURRENCY_CODE`, `SUPPLY_DATE`, `INVOICE_TOTAL_AMT_WITHOUTVAT`, `INVOICE_TOTAL_AMT_WITH_VAT`, `PO_NUMBER`, `BRANCH_CODE`, `BRANCH_NAME`, `CUSTOMER_RETURN_REF`) VALUES (NULL, '".$value[2]."', '".$value[4]."', '".$value[8]."', '".$value[5]."', '".$value[6]."', '".$value[7]."', '".str_replace("'", "\'", $value[9])."', '".$value[10]."', '".$value[11]."', '".$value[12]."', '0', '".$value[13]."', '".$value[15]."', '".$value[19]."', '".$value[34]."', '".$value[36]."', '".$value[3]."', '".$value[1]."', '".$value[0]."', '".$value[31]."'); ";
                                $result = $connection->query($query);
                                
                            }
                        }
                        $this->messageManager->addSuccess('All items are imported !!!');
                        return $resultRedirect->setPath('*/*/import', array('_current' => true));
                    }
                }
                
                if (pathinfo($files['filename']['name'], PATHINFO_EXTENSION) == 'xls') {

                     // ExcelFile($filename, $encoding);
                    $data = new \Spreadsheet_Excel_Reader();
                     
                    // Set output Encoding.
                    $data->setOutputEncoding('utf-8');
                    /* if you want you can change 'iconv' to mb_convert_encoding:*/
                     
                    $data->setUTFEncoder('mb');
                     
                    /*
                    * By default rows & cols indeces start with 1
                    * For change initial index use:
                    */
                    $index = 0;
                     
                    $data->setRowColOffset($index);
                    /* setDefaultFormat - set format for columns with unknown formatting*/
                     
                    $data->setDefaultFormat('%.2f');
                     
                    /* setColumnFormat - set format for column (apply only to number fields)*/
                    $data->setColumnFormat(4, '%.3f');
                    /*Do the actual reading of file*/
                    

                    $include_path = dirname(__FILE__);
                    $path_to_XML = $target;
                    $data->read($path_to_XML);
                    $data = $data->sheets[0]['cells'];
                    $csvLines = array();
                    $ii = 0;
                    
                            
                    $csvLines = array();
                    $ii = 0;
                    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                    $connection = $resource->getConnection();
                    $tableName = $resource->getTableName('dsfh_customer');

                    $query = "TRUNCATE `customers_e_invoice` ";
                    $result = $connection->query($query);

                    foreach($data as $value){
                        if($ii == 0){ $ii = 1; } else {
                            $query = "INSERT INTO `customers_e_invoice` (`ID`, `CUSTOMER_ID`, `PARTY_ID`, `TRX_DATE`, `CUSTOMER_NUMBER`, `BILL_TO_SITE_USE_ID`, `INVOICE_IDENTIFY_NUMBER`, `BUYER_NAME`, `ORG_ID`, `OPERATING_UNITS`, `FILE_NAME`, `IS_DOWNLOADED`, `INVOICE_CLASS`, `INVOICE_CURRENCY_CODE`, `SUPPLY_DATE`, `INVOICE_TOTAL_AMT_WITHOUTVAT`, `INVOICE_TOTAL_AMT_WITH_VAT`, `PO_NUMBER`, `BRANCH_CODE`, `BRANCH_NAME`, `CUSTOMER_RETURN_REF`) VALUES (NULL, '".$value[2]."', '".$value[4]."', '".$value[8]."', '".$value[5]."', '".$value[6]."', '".$value[7]."', '".str_replace("'", "\'", $value[9])."', '".$value[10]."', '".$value[11]."', '".$value[12]."', '0', '".$value[13]."', '".$value[15]."', '".$value[19]."', '".$value[34]."', '".$value[36]."', '".$value[3]."', '".$value[1]."', '".$value[0]."', '".$value[31]."'); ";
                            $result = $connection->query($query);
                        }
                    }
                    $this->messageManager->addSuccess('All items are imported !!!');
                    return $resultRedirect->setPath('*/*/import', array('_current' => true));
                    
                }
                
                if (pathinfo($files['filename']['name'], PATHINFO_EXTENSION) == 'csv') {
                    try {
                        //set delimiter, for tab pass "\t"
                        $this->csv->setDelimiter(",");
                        //get data as an array
                        $data = $this->csv->getData($path);
                        if (!empty($data)) {
                            // ignore first header column and read data
                            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                            $connection = $resource->getConnection();
                            $tableName = $resource->getTableName('dsfh_customer');
                            
                            $query = "TRUNCATE `customers_e_invoice` ";
                            $result = $connection->query($query);

                            foreach (array_slice($data, 1) as $key => $value) {

                                $query = "INSERT INTO `customers_e_invoice` (`ID`, `CUSTOMER_ID`, `PARTY_ID`, `TRX_DATE`, `CUSTOMER_NUMBER`, `BILL_TO_SITE_USE_ID`, `INVOICE_IDENTIFY_NUMBER`, `BUYER_NAME`, `ORG_ID`, `OPERATING_UNITS`, `FILE_NAME`, `IS_DOWNLOADED`, `INVOICE_CLASS`, `INVOICE_CURRENCY_CODE`, `SUPPLY_DATE`, `INVOICE_TOTAL_AMT_WITHOUTVAT`, `INVOICE_TOTAL_AMT_WITH_VAT`, `PO_NUMBER`, `BRANCH_CODE`, `BRANCH_NAME`, `CUSTOMER_RETURN_REF`) VALUES (NULL, '".$value[2]."', '".$value[4]."', '".$value[8]."', '".$value[5]."', '".$value[6]."', '".$value[7]."', '".str_replace("'", "\'", $value[9])."', '".$value[10]."', '".$value[11]."', '".$value[12]."', '0', '".$value[13]."', '".$value[15]."', '".$value[19]."', '".$value[34]."', '".$value[36]."', '".$value[3]."', '".$value[1]."', '".$value[0]."', '".$value[31]."'); ";
                                $result = $connection->query($query);
                            }
                            $this->messageManager->addSuccess('All items are imported !!!');
                            return $resultRedirect->setPath('*/*/import', array('_current' => true));
                        }
                        
                    } catch (FileSystemException $e) {
                        $this->messageManager->addError(__($e->getMessage()));
                        return $resultRedirect->setPath('*/*/import', array('_current' => true));
                    }
                }

            } catch (\Exception $e) {
                $this->messageManager->addError(__($e->getMessage()));
                return $resultRedirect->setPath('*/*/import', array('_current' => true));
            }
        } else {
            $this->messageManager->addError('Please upload a csv/xls/xlsx file to import.');
            return $resultRedirect->setPath('*/*/import', array('_current' => true));
        }
    }
}