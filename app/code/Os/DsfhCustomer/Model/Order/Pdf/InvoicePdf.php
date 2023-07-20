<?php

namespace Os\DsfhCustomer\Model\Order\Pdf;

use \Magento\Sales\Model\Order\Pdf\Invoice;

class InvoicePdf extends Invoice
{
    /**
     * We only need to override the getPdf of Invoice,
     *  most of this method is copied directly from parent class
     *
     * @param array $invoices
     * @return \Zend_Pdf
     */
    public function getPdf3($invoices = []) {
        
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

            $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            
            $this->_localeResolver->emulate($storeManager->getStore()->getStoreId());
            $this->_storeManager->setCurrentStore($storeManager->getStore()->getStoreId());
        
            $page = $this->newPage();
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $orderde = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($invoices[0]);
            
            $orderde_data = $orderde->getData();
            
            $shipde = $orderde->getShippingAddress()->getData();
            $orderde_data['countryorderd'] = $shipde['country_id']; 
            
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            
            $tableNamequte = $resource->getTableName('sales_order_address'); //gives table name with prefix
            $sqlselect = "Select * FROM " . $tableNamequte . " WHERE entity_id =". $shipde['entity_id'] .";";;
            $result = $connection->fetchAll($sqlselect);
            $customerpo = "";
            $stree = "";
            foreach ($result as $resul) {
                $customerpo = $resul['customer_address_id'];
                $stree = $resul['street'];
            }
            if($orderde->getShippingAddressLabel()){
                $orderde_data['shipping_address_label'] = $orderde->getShippingAddressLabel();
            } else {
                if($stree == "" || $stree == "-"){
                    if($customerpo == ""){} else {
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        
                        $billingAddress = $objectManager->create('Magento\Customer\Api\AddressRepositoryInterface')->getById($customerpo);
                     
                        $orderde_data['shipping_address_label'] = $billingAddress->getFax();
                    }
                    
                } else {
                    $orderde_data['shipping_address_label'] = $stree;
                }
            }
            
            
            /* Add image */
            $this->insertLogo($page, $storeManager->getStore());
            /* Add address */
            $this->insertAddress3($page, $storeManager->getStore(),$orderde_data);
            /* Add head */

            $order = "";
            $this->insertOrder2(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeManager->getStore()->getStoreId()
                ), '#'.$orderde_data['increment_id']
            );
            
            /* Add table */
            $this->_drawHeader2($page);
            /* Add body */

            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();

            $tableNamesuggested = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY');
            $sqlselect_suggested = "Select * FROM " . $tableNamesuggested . " WHERE ORDER_NUMBER = '". $orderde_data['increment_id']."' ;";
            $suggesteds = $connection->fetchAll($sqlselect_suggested);
            $totalpay_amount = 0;
            
            $numItems = count($suggesteds);
            $i = 0;
            $cnt = 0;
            $pagenm = 1;
            
            foreach ($suggesteds as $suggestItem) {

                $totalpay_amount = $totalpay_amount + $suggestItem['INVOICE_BALANCE'];
                $i = $i + 1;
                $cnt = $cnt + 1;
                /* Add table head */
                $this->_setFontRegular($page, 10);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
                $page->setLineWidth(0.5);

                if($numItems == $i){
                    $page->drawRectangle(25, $this->y, 50, $this->y - 55);
                    $page->drawRectangle(50, $this->y, 154, $this->y - 55);
                    $page->drawRectangle(154, $this->y, 258, $this->y - 55);
                    $page->drawRectangle(258, $this->y, 362, $this->y - 55);
                    $page->drawRectangle(362, $this->y, 466, $this->y - 55);
                    $page->drawRectangle(466, $this->y, 570, $this->y - 55);

                } else {
                    $page->drawRectangle(25, $this->y, 50, $this->y - 72);
                    $page->drawRectangle(50, $this->y, 154, $this->y - 72);
                    $page->drawRectangle(154, $this->y, 258, $this->y - 72);
                    $page->drawRectangle(258, $this->y, 362, $this->y - 72);
                    $page->drawRectangle(362, $this->y, 466, $this->y - 72);
                    $page->drawRectangle(466, $this->y, 570, $this->y - 72);
                }
                $this->y -= 10;
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0,0,0));


                //columns headers
                $lines = array();
                $lines[0][] = ['text' => '1', 'feed' => 35];

                $lines[0][] = ['text' => $suggestItem['TRX_NUMBER'], 'feed' => 60, 'align' => 'center'];
                
                $datepf = explode(" ",$suggestItem['CREATION_DATE']);
                $lines[0][] = ['text' => $datepf[0], 'feed' => 182, 'align' => 'center'];
                $datepf = explode(" ",$suggestItem['DUE_DATE']);
                $lines[0][] = ['text' => $datepf[0], 'feed' => 285, 'align' => 'center'];

                $lines[0][] = ['text' => $suggestItem['INVOICE_AMOUNT'], 'feed' => 395, 'align' => 'center'];

                $lines[0][] = ['text' => $suggestItem['INVOICE_BALANCE'], 'feed' => 500, 'align' => 'center'];

                
                $lineBlock = ['lines' => $lines, 'height' => 20];
                $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                
                if($pagenm == 1){
                    if($cnt == 14){
                        $cnt = 0;
                        $page = $this->newPage();
                        $pagenm = $pagenm + 1;

                    }            
                } else {
                    if($cnt == 20){
                        $cnt = 0;
                        $page = $this->newPage();
                        $pagenm = $pagenm + 1;
                    } 
                }
            }
            
            $lines = array();
            /* Add table head */

            $this->y -= 20;
            $this->_setFontRegular($page, 12);
            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            $page->drawRectangle(362, $this->y, 466, $this->y - 30);
            
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0.93, 0.92, 0.92));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            
            $page->drawRectangle(466, $this->y, 570, $this->y - 30);
            $this->y -= 14;
            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        
            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 60, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 195, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 285, 'align' => 'center'];

            $lines[0][] = ['text' => 'Total', 'feed' => 410, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 485, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); 

            $this->y += 16;
            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 60, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 195, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 285, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => $priceHelper->currency($totalpay_amount, true, false), 'feed' => 485, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            if ($storeManager->getStore()->getStoreId()) {
                $this->_localeResolver->revert();
            }
            
            


        $this->_afterGetPdf();
        return $pdf;
    }

 
    public function getPdf2($invoices = []) {
        
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

            $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            
            $this->_localeResolver->emulate($storeManager->getStore()->getStoreId());
            $this->_storeManager->setCurrentStore($storeManager->getStore()->getStoreId());
        
            $page = $this->newPage();
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $orderde = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($invoices[0]);
            
            $orderde_data = $orderde->getData();
            
            $shipde = $orderde->getShippingAddress()->getData();
            $orderde_data['countryorderd'] = $shipde['country_id']; 
            
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            
            $tableNamequte = $resource->getTableName('sales_order_address'); //gives table name with prefix
            $sqlselect = "Select * FROM " . $tableNamequte . " WHERE entity_id =". $shipde['entity_id'] .";";;
            $result = $connection->fetchAll($sqlselect);
            $customerpo = "";
            $stree = "";
            foreach ($result as $resul) {
                $customerpo = $resul['customer_address_id'];
                $stree = $resul['street'];
            }
            if($orderde->getShippingAddressLabel()){
                $orderde_data['shipping_address_label'] = $orderde->getShippingAddressLabel();
            } else {
                if($stree == "" || $stree == "-"){
                    if($customerpo == ""){} else {
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        
                        $billingAddress = $objectManager->create('Magento\Customer\Api\AddressRepositoryInterface')->getById($customerpo);
                     
                        $orderde_data['shipping_address_label'] = $billingAddress->getFax();
                    }
                    
                } else {
                    $orderde_data['shipping_address_label'] = $stree;
                }
            }
            
            
            /* Add image */
            $this->insertLogo($page, $storeManager->getStore());
            /* Add address */
            $this->insertAddress2($page, $storeManager->getStore(),$orderde_data);
            /* Add head */

            $order = "";
            $this->insertOrder2(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeManager->getStore()->getStoreId()
                ), '#'.$orderde_data['increment_id']
            );
            
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */

            
            
            $items = $orderde->getAllVisibleItems();

            $numItems = count($items);
            $i = 0;
            $cnt = 0;
            $pagenm = 1;
            foreach ($items as $_item) {
                $i = $i + 1;
                $cnt = $cnt + 1;
                /* Add table head */
                $this->_setFontRegular($page, 10);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
                $page->setLineWidth(0.5);

                if($numItems == $i){
                    $page->drawRectangle(25, $this->y, 100, $this->y - 55);
                    $page->drawRectangle(100, $this->y, 140, $this->y - 55);
                    $page->drawRectangle(140, $this->y, 215, $this->y - 55);
                    $page->drawRectangle(215, $this->y, 415, $this->y - 55);
                    $page->drawRectangle(215, $this->y, 368, $this->y - 55);
                    $page->drawRectangle(368, $this->y, 430, $this->y - 55);
                    $page->drawRectangle(430, $this->y, 490, $this->y - 55);
                    $page->drawRectangle(490, $this->y, 570, $this->y - 55);
                } else {
                    $page->drawRectangle(25, $this->y, 100, $this->y - 72);
                    $page->drawRectangle(100, $this->y, 140, $this->y - 72);
                    $page->drawRectangle(140, $this->y, 215, $this->y - 72);
                    $page->drawRectangle(215, $this->y, 415, $this->y - 72);
                    $page->drawRectangle(215, $this->y, 368, $this->y - 72);
                    $page->drawRectangle(368, $this->y, 430, $this->y - 72);
                    $page->drawRectangle(430, $this->y, 490, $this->y - 72);
                    $page->drawRectangle(490, $this->y, 570, $this->y - 72);
                }
                $this->y -= 10;
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0,0,0));


                $sku = $_item->getSku();
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $productObject = $objectManager->get('Magento\Catalog\Model\Product');
                $product = $productObject->loadByAttribute('sku', $sku);
                
                $percent = "0 %";
                $percentval = 0;
                if($product->getTaxClassId() == 5){
                    $percent = "15 %";
                    $percentval = 15 * $_item->getRowTotal() / 100;
                    $percentval = round($percentval,2);
                }

                //columns headers
                $lines = array();
                $lines[0][] = ['text' => $product->getBrandText(), 'feed' => 35];

                $lines[0][] = ['text' => round($_item->getQtyOrdered(),0), 'feed' => 115, 'align' => 'center'];

                $lines[0][] = ['text' => $_item->getSku(), 'feed' => 150, 'align' => 'center'];

                $lines[0][] = ['text' => $_item->getName(), 'feed' => 220, 'align' => 'center'];

                $lines[0][] = ['text' => 'SAR '.$percentval, 'feed' => 380, 'align' => 'center'];

                $lines[0][] = ['text' => $percent, 'feed' => 450, 'align' => 'center'];

                $lines[0][] = ['text' => 'SAR '.round($_item->getRowTotal(),2), 'feed' => 505, 'align' => 'center'];
                
                $lineBlock = ['lines' => $lines, 'height' => 40];
                $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                
                if($pagenm == 1){
                    if($cnt == 9){
                        $cnt = 0;
                        $page = $this->newPage();
                        $pagenm = $pagenm + 1;

                    }            
                } else {
                    if($cnt == 12){
                        $cnt = 0;
                        $page = $this->newPage();
                        $pagenm = $pagenm + 1;
                    } 
                }
            }
            
            $lines = array();
            /* Add table head */
            $this->_setFontRegular($page, 12);
            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            $page->drawRectangle(368, $this->y, 490, $this->y - 75);
            
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            
            $page->drawRectangle(490, $this->y, 570, $this->y - 75);
            $this->y -= 8;
            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        
            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Subtotal', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($orderde->getSubtotal(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Shipping', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($orderde->getShippingAmount(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Tax', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($orderde->getShippingAddress()->getTaxAmount(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Total', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($orderde->getGrandTotal(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
            if ($storeManager->getStore()->getStoreId()) {
                $this->_localeResolver->revert();
            }
            
        
        $this->_afterGetPdf();
        return $pdf;
    }

 
    public function getPdf($invoices = []) {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

            $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            
            $this->_localeResolver->emulate($storeManager->getStore()->getStoreId());
            $this->_storeManager->setCurrentStore($storeManager->getStore()->getStoreId());
        
            $page = $this->newPage();
            
            /* Add image */
            $this->insertLogo($page, $storeManager->getStore());
            /* Add address */
            $this->insertAddress($page, $storeManager->getStore());
            /* Add head */

            $order = "";
            $this->insertOrder(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeManager->getStore()->getStoreId()
                )
            );
            
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 

            // retrieve quote items collection
            $itemsCollection = $cart->getQuote()->getItemsCollection();

            // get array of all items what can be display directly
            $itemsVisible = $cart->getQuote()->getAllVisibleItems();

            // retrieve quote items array
             $items = $cart->getQuote()->getAllItems();

            $numItems = count($items);
            $i = 0;
            $cnt = 0;
            $pagenm = 1;
            foreach ($items as $_item) {
                $i = $i + 1;
                $cnt = $cnt + 1;
                /* Add table head */
                $this->_setFontRegular($page, 10);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
                $page->setLineWidth(0.5);

                if($numItems == $i){
                    $page->drawRectangle(25, $this->y, 100, $this->y - 55);
                    $page->drawRectangle(100, $this->y, 140, $this->y - 55);
                    $page->drawRectangle(140, $this->y, 215, $this->y - 55);
                    $page->drawRectangle(215, $this->y, 415, $this->y - 55);
                    $page->drawRectangle(215, $this->y, 368, $this->y - 55);
                    $page->drawRectangle(368, $this->y, 430, $this->y - 55);
                    $page->drawRectangle(430, $this->y, 490, $this->y - 55);
                    $page->drawRectangle(490, $this->y, 570, $this->y - 55);
                } else {
                    $page->drawRectangle(25, $this->y, 100, $this->y - 72);
                    $page->drawRectangle(100, $this->y, 140, $this->y - 72);
                    $page->drawRectangle(140, $this->y, 215, $this->y - 72);
                    $page->drawRectangle(215, $this->y, 415, $this->y - 72);
                    $page->drawRectangle(215, $this->y, 368, $this->y - 72);
                    $page->drawRectangle(368, $this->y, 430, $this->y - 72);
                    $page->drawRectangle(430, $this->y, 490, $this->y - 72);
                    $page->drawRectangle(490, $this->y, 570, $this->y - 72);
                }
                $this->y -= 10;
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0,0,0));


                $sku = $_item->getSku();
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $productObject = $objectManager->get('Magento\Catalog\Model\Product');
                $product = $productObject->loadByAttribute('sku', $sku);
                
                $percent = "0 %";
                $percentval = 0;
                if($product->getTaxClassId() == 5){
                    $percent = "15 %";
                    $percentval = 15 * $_item->getRowTotal() / 100;
                    $percentval = round($percentval,2);
                }

                //columns headers
                $lines = array();
                $lines[0][] = ['text' => $product->getBrandText(), 'feed' => 35];

                $lines[0][] = ['text' => $_item->getQty(), 'feed' => 115, 'align' => 'center'];

                $lines[0][] = ['text' => $_item->getSku(), 'feed' => 150, 'align' => 'center'];

                $lines[0][] = ['text' => $_item->getName(), 'feed' => 220, 'align' => 'center'];

                $lines[0][] = ['text' => 'SAR '.$percentval, 'feed' => 380, 'align' => 'center'];

                $lines[0][] = ['text' => $percent, 'feed' => 450, 'align' => 'center'];

                $lines[0][] = ['text' => 'SAR '.round($_item->getRowTotal(),2), 'feed' => 505, 'align' => 'center'];
                
                $lineBlock = ['lines' => $lines, 'height' => 40];
                $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                
                if($pagenm == 1){
                    if($cnt == 10){
                        $cnt = 0;
                        $page = $this->newPage();
                        $pagenm = $pagenm + 1;

                    }            
                } else {
                    if($cnt == 12){
                        $cnt = 0;
                        $page = $this->newPage();
                        $pagenm = $pagenm + 1;
                    } 
                }
            }
            
            $lines = array();
            /* Add table head */
            $this->_setFontRegular($page, 12);
            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            $page->drawRectangle(368, $this->y, 490, $this->y - 75);
            
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
            $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            
            $page->drawRectangle(490, $this->y, 570, $this->y - 75);
            $this->y -= 8;
            $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        
            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Subtotal', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($cart->getQuote()->getSubtotal(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Shipping', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($cart->getQuote()->getShippingAmount(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Tax', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($cart->getQuote()->getShippingAddress()->getTaxAmount(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));

            $lines = array();
            $lines[0][] = ['text' => '', 'feed' => 35];

            $lines[0][] = ['text' => '', 'feed' => 115, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 150, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 220, 'align' => 'center'];

            $lines[0][] = ['text' => '', 'feed' => 380, 'align' => 'center'];

            $lines[0][] = ['text' => 'Total', 'feed' => 440, 'align' => 'center'];

            $lines[0][] = ['text' => 'SAR '.round($cart->getQuote()->getGrandTotal(),2), 'feed' => 505, 'align' => 'center'];

            $lineBlock = ['lines' => $lines, 'height' => 12];

            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
            if ($storeManager->getStore()->getStoreId()) {
                $this->_localeResolver->revert();
            }
            
        
        $this->_afterGetPdf();
        return $pdf;
    }

    protected function _drawHeader(\Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 100, $this->y - 55);
        $page->drawRectangle(100, $this->y, 140, $this->y - 55);
        $page->drawRectangle(140, $this->y, 215, $this->y - 55);
        $page->drawRectangle(215, $this->y, 415, $this->y - 55);
        $page->drawRectangle(215, $this->y, 368, $this->y - 55);
        $page->drawRectangle(368, $this->y, 430, $this->y - 55);
        $page->drawRectangle(430, $this->y, 490, $this->y - 55);
        $page->drawRectangle(490, $this->y, 570, $this->y - 55);
        $this->y -= 8;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('Principal'), 'feed' => 35];

        $lines[0][] = ['text' => __('Qty'), 'feed' => 115, 'align' => 'center'];

        $lines[0][] = ['text' => __('Sku'), 'feed' => 165, 'align' => 'center'];

        $lines[0][] = ['text' => __('Product'), 'feed' => 270, 'align' => 'center'];

        $lines[0][] = ['text' => __('Tax Amount'), 'feed' => 375, 'align' => 'center'];

        $lines[0][] = ['text' => __('Tax Percent'), 'feed' => 435, 'align' => 'center'];

        $lines[0][] = ['text' => __('Total'), 'feed' => 515, 'align' => 'center'];

        $lineBlock = ['lines' => $lines, 'height' => 10];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        
    }
    
    protected function _drawHeader2(\Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 50, $this->y - 55);
        $page->drawRectangle(50, $this->y, 154, $this->y - 55);
        $page->drawRectangle(154, $this->y, 258, $this->y - 55);
        $page->drawRectangle(258, $this->y, 362, $this->y - 55);
        $page->drawRectangle(362, $this->y, 466, $this->y - 55);
        $page->drawRectangle(466, $this->y, 570, $this->y - 55);
        
        $this->y -= 8;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('#'), 'feed' => 35];

        $lines[0][] = ['text' => __('Invoice Number'), 'feed' => 60, 'align' => 'center'];

        $lines[0][] = ['text' => __('Date'), 'feed' => 195, 'align' => 'center'];

        $lines[0][] = ['text' => __('Due Date'), 'feed' => 285, 'align' => 'center'];

        $lines[0][] = ['text' => __('Invoice Amount'), 'feed' => 380, 'align' => 'center'];

        $lines[0][] = ['text' => __('Due Amount'), 'feed' => 485, 'align' => 'center'];

        $lineBlock = ['lines' => $lines, 'height' => 10];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        
    }
    
    public function drawLineBlocks(\Zend_Pdf_Page $page, array $draw, array $pageSettings = [])
    {
        foreach ($draw as $itemsProp) {
            if (!isset($itemsProp['lines']) || !is_array($itemsProp['lines'])) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('We don\'t recognize the draw line data. Please define the "lines" array.')
                );
            }
            $lines = $itemsProp['lines'];
            $height = isset($itemsProp['height']) ? $itemsProp['height'] : 10;

            if (empty($itemsProp['shift'])) {
                $shift = 0;
                foreach ($lines as $line) {
                    $maxHeight = 0;
                    foreach ($line as $column) {
                        $lineSpacing = !empty($column['height']) ? $column['height'] : $height;
                        if (!is_array($column['text'])) {
                            $column['text'] = [$column['text']];
                        }
                        $top = 0;
                        foreach ($column['text'] as $part) {
                            $top += $lineSpacing;
                        }

                        $maxHeight = $top > $maxHeight ? $top : $maxHeight;
                    }
                    $shift += $maxHeight;
                }
                $itemsProp['shift'] = $shift;
            }

            if ($this->y - $itemsProp['shift'] < 72) {
                $page = $this->newPage($pageSettings);
            }

            foreach ($lines as $line) {
                $maxHeight = 0;
                $clmfirt = 0;
                foreach ($line as $column) {
                    $fontSize = empty($column['font_size']) ? 10 : $column['font_size'];
                    if (!empty($column['font_file'])) {
                        $font = \Zend_Pdf_Font::fontWithPath($column['font_file']);
                        $page->setFont($font, $fontSize);
                    } else {
                        $fontStyle = empty($column['font']) ? 'regular' : $column['font'];
                        switch ($fontStyle) {
                            case 'bold':
                                $font = $this->_setFontBold($page, $fontSize);
                                break;
                            case 'italic':
                                $font = $this->_setFontItalic($page, $fontSize);
                                break;
                            default:
                                $font = $this->_setFontRegular($page, $fontSize);
                                break;
                        }
                    }

                    if (!is_array($column['text'])) {
                        $column['text'] = [$column['text']];
                    }

                    $lineSpacing = !empty($column['height']) ? $column['height'] : $height;
                    $top = 5;
                    
                    foreach ($column['text'] as $part) {
                        if ($this->y - $lineSpacing < 72) {
                            $page = $this->newPage($pageSettings);
                        }

                        $feed = $column['feed'];
                        $textAlign = empty($column['align']) ? 'left' : $column['align'];
                        $width = empty($column['width']) ? 0 : $column['width'];
                        switch ($textAlign) {
                            case 'right':
                                if ($width) {
                                    $feed = $this->getAlignRight($part, $feed, $width, $font, $fontSize);
                                } else {
                                    $feed = $feed - $this->widthForStringUsingFontSize($part, $font, $fontSize);
                                }
                                break;
                            case 'center':
                                if ($width) {
                                    $feed = $this->getAlignCenter($part, $feed, $width, $font, $fontSize);
                                }
                                break;
                            default:
                                break;
                        }
                        $maxpinlt = 25;
                        if($clmfirt == 0){
                            $clmfirt = 1;
                            $maxpinlt = 9;


                        }

                        if (strlen($part) < $maxpinlt){
                            $page->drawText($part, $feed, $this->y - $top, 'UTF-8');
                        } else {
                            $substr = trim(substr($part, $maxpinlt-1, strlen($part)));
                            if (strlen($substr) < $maxpinlt){

                                $page->drawText(substr($part, 0, $maxpinlt-1), $feed, $this->y - $top, 'UTF-8');

                                $page->drawText(trim(substr($part, $maxpinlt-1, strlen($part))), $feed, $this->y - $top - 15, 'UTF-8');

                            } else {
                                

                                $page->drawText(substr($part, 0, $maxpinlt-1), $feed, $this->y - $top, 'UTF-8');

                                $page->drawText(substr($substr, 0, $maxpinlt-1), $feed, $this->y - $top - 15, 'UTF-8');

                                $substr2 = trim(substr($substr, $maxpinlt-1, strlen($substr)));
                                if (strlen($substr2) > $maxpinlt){
                                    $substr2 = substr($substr2, 0, $maxpinlt-4);
                                    $substr2 = $substr2."...";
                                }
                                $page->drawText($substr2, $feed, $this->y - $top - 15 - 15, 'UTF-8');

                                //$page->drawText(trim(substr(trim(substr($part, $maxpinlt-1, strlen(trim(substr($part, $maxpinlt-1, strlen($part)))))), $maxpinlt-1, strlen($part))), $feed, $this->y - $top - 15 - 15, 'UTF-8');

                            }

                        }

                        


                        $top += $lineSpacing;
                    }

                    $maxHeight = $top > $maxHeight ? $top : $maxHeight;
                }
                $this->y -= $maxHeight;
            }
        }

        return $page;
    }

    protected function insertLogo(&$page, $store = null)
    {
        $this->y = $this->y ? $this->y : 815;
        $image = $this->_scopeConfig->getValue(
            'sales/identity/logo',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        if ($image) {
            $imagePath = '/sales/store/logo/' . $image;
            if ($this->_mediaDirectory->isFile($imagePath)) {
                $image = \Zend_Pdf_Image::imageWithPath($this->_mediaDirectory->getAbsolutePath($imagePath));
                $top = 800;
                //top border of the page
                $widthLimit = 150;
                //half of the page width
                $heightLimit = 90;
                //assuming the image is not a "skyscraper"
                $width = $image->getPixelWidth();
                $height = $image->getPixelHeight();

                //preserving aspect ratio (proportions)
                $ratio = $width / $height;
                if ($ratio > 1 && $width > $widthLimit) {
                    $width = $widthLimit;
                    $height = $width / $ratio;
                } elseif ($ratio < 1 && $height > $heightLimit) {
                    $height = $heightLimit;
                    $width = $height * $ratio;
                } elseif ($ratio == 1 && $height > $heightLimit) {
                    $height = $heightLimit;
                    $width = $widthLimit;
                }

                $y1 = $top - $height;
                $y2 = $top;
                $x1 = 325;
                $x2 = $x1 + $width;

                //coordinates after transformation are rounded by Zend
                $page->drawImage($image, $x1, $y1, $x2, $y2);

                $this->y = $y1 - 10;
            }
        }
    }

    protected function insertAddress(&$page, $store = null)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $font = $this->_setFontRegular($page, 10);
        $page->setLineWidth(0);
        $this->y = $this->y ? $this->y : 815;
        $top = 795;
        $values = explode(
            "\n",
            $this->_scopeConfig->getValue(
                'sales/identity/address',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            )
        );
        $fontSize = 12;   
        $oFont = $this->_setFontRegular($page, $fontSize);
        


        $om = \Magento\Framework\App\ObjectManager::getInstance();  
        $customerSession = $om->get('Magento\Customer\Model\Session');  
        $customerData = $customerSession->getCustomer();

        $top -= 5;
        $txt2 = 'Tamer Store';
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $top -= 15;
        $txt2 = $customerData->getName();
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );


        $top -= 15;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $sgipAddress = $objectManager->create('Magento\Customer\Api\AddressRepositoryInterface')->getById(trim($customerData->getDefaultShipping()));
        $txt2 = $sgipAddress->getStreet();
        $txt2 = $txt2[0];
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $countrycode = $sgipAddress->getCountryId();
        $top -= 15;
        $txt2 = $objectManager->create('\Magento\Directory\Model\Country')->load("$countrycode")->getName();
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $top -= 55;

        $this->y = $this->y > $top ? $top : $this->y;
    }

    protected function insertAddress3(&$page, $store = null, $orderds)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $font = $this->_setFontRegular($page, 10);
        $page->setLineWidth(0);
        $this->y = $this->y ? $this->y : 815;
        $top = 795;
        $values = explode(
            "\n",
            $this->_scopeConfig->getValue(
                'sales/identity/address',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            )
        );
        $fontSize = 12;   
        $oFont = $this->_setFontRegular($page, $fontSize);
        

        $om = \Magento\Framework\App\ObjectManager::getInstance();  
        $customerSession = $om->get('Magento\Customer\Model\Session');  
        $customerData = $customerSession->getCustomer();

        $top -= 5;
        $txt2 = 'Tamer Store';
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $top -= 15;
        $txt2 = $orderds['customer_firstname'].' '.$orderds['customer_lastname'];
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );


        $top -= 15;
        $txt2 = $orderds['shipping_address_label'];
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $countrycode = $orderds['countryorderd'];
        $top -= 15;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $txt2 = $objectManager->create('\Magento\Directory\Model\Country')->load("$countrycode")->getName();
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );
        
        $top -= 15;
        $txt2 = "";
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );


        $top -= 15;
        $txt2 = "";
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );



        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
    
        $time2 = time();
        
        $tableNamesuggested = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY_STATUS');
        $sqlselect_suggested = "Select * FROM " . $tableNamesuggested . " WHERE order_id ='". $orderds['entity_id'] ."';";
        $suggesteds = $connection->fetchAll($sqlselect_suggested);
        $checkpaidornot = 0;
        $check_declined = 0;
        $check_paid = 0;
        $fortid = "";
        $cardnameh = "";
        $cardnumber = "";
        $customeremail = "";
        $reconciliationreference = "";
        foreach($suggesteds as $suggestItem){
            $checkpaidornot = 1;
            $reconciliationreference = $suggestItem['fort_id'];
            $cardnameh = $suggestItem['card_holder_name'];
            $cardnumber = $suggestItem['card_number'];
            $customeremail = $suggestItem['customer_email'];
            if($suggestItem['flag'] == '2'){
                $check_declined = 1;
            } else {
                $check_paid = 1;
            }
        }  
        $amazonpay = "";
        $amazonamount = "";
        $amazonamounta = "";
           
        $tableNamesuggested = $resource->getTableName('TMR_INVC_CM_PAY_GATEWAY');
        $sqlselect_suggested = "Select * FROM " . $tableNamesuggested . " WHERE ORDER_NUMBER ='". $orderds['increment_id'] ."' ;";
        $suggesteds = $connection->fetchAll($sqlselect_suggested);
        $totalpay_amount = 0;
        foreach($suggesteds as $suggestItem){
            $totalpay_amount = $totalpay_amount + $suggestItem['INVOICE_BALANCE']; 
        }
        if($totalpay_amount > 0){
            $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); 
            $amazonamount = $priceHelper->currency($totalpay_amount, true, false);
            $amazonpay = "Not Paid";
        } else {
            $amazonpay = "";
        }
        if($checkpaidornot == 0){} else {
            if($check_paid == 1){
                $amazonpay = "Paid";
            } else {
                if($check_declined == 1){
                    $amazonpay = "Declined";
                }
            }
            $amazonamounta = $amazonamount;
        }
        if($amazonamount == ""){} else {
            $fortid = '000000'.$orderds['entity_id'];
        }
        
        $txt211 = "Paid amount: ".$amazonamounta;
        $this->_setFontBold($page, 13);
        $top -= 15;
        $txt2 = "Payment information";
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        
        $top -= 15;

        $this->_setFontRegular($page, 11);
        
        $txt22 = "Payment Method: MADA";
        $page->drawText(
            trim(strip_tags($txt22)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) + 355,
            $top,
            'UTF-8'
        );

        $txt2 = "Payment status: ".$amazonpay;
        
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        

        $top -= 15;
        $txt2 = "Paid amount: ".$amazonamounta;
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $txt22 = "Card holder name: ".$cardnameh;
        $page->drawText(
            trim(strip_tags($txt22)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) + 355,
            $top,
            'UTF-8'
        );
        

        
        $top -= 15;
        $txt2 = "Payment reference: ".$reconciliationreference;
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $txt23 = "Card number: ".$cardnumber;
        $page->drawText(
            trim(strip_tags($txt23)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) + 355,
            $top,
            'UTF-8'
        );



        $top -= 15;
        $txt22 = "Customer email: ".$customeremail;
        $page->drawText(
            trim(strip_tags($txt22)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt22)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt22)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );
        $datetime1 = Date('Y-m-d h:i:s');
        $txt23 = "Date and time: ".(string)$datetime1;
        $page->drawText(
            trim(strip_tags($txt23)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt211)), $oFont, $fontSize) + 355,
            $top,
            'UTF-8'
        );
        
        $CustomerModel = $objectManager->create('Magento\Customer\Model\Customer');
        $CustomerModel->setWebsiteId(1);
        $CustomerModel->loadByEmail($customeremail);
        $userId = $CustomerModel->getId();
        
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('eav_attribute');
        $attribute_information = "Select * FROM " . $tableName . " WHERE attribute_code = 'tmaino_oracleid';";
        $result = $connection->fetchAll($attribute_information);
        

        $tableName = $resource->getTableName('customer_entity_text');
        $attribute_information = "Select * FROM " . $tableName . " WHERE attribute_id = '".$result[0]['attribute_id']."' AND entity_id = '".$userId."';";
        $result2 = $connection->fetchAll($attribute_information);
        $cusorl = "";
        if(count($result2) == 0){} else {
            $cusorl = $result2[0]['value'];
        }
        $top -= 15;
        $txt22 = "Customer#: ".$cusorl;
        $page->drawText(
            trim(strip_tags($txt22)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt22)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt22)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );
        
        
        
        $top -= 55;

        $this->y = $this->y > $top ? $top : $this->y;
    }
    
    
    protected function insertAddress2(&$page, $store = null, $orderds)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $font = $this->_setFontRegular($page, 10);
        $page->setLineWidth(0);
        $this->y = $this->y ? $this->y : 815;
        $top = 795;
        $values = explode(
            "\n",
            $this->_scopeConfig->getValue(
                'sales/identity/address',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store
            )
        );
        $fontSize = 12;   
        $oFont = $this->_setFontRegular($page, $fontSize);
        

        $om = \Magento\Framework\App\ObjectManager::getInstance();  
        $customerSession = $om->get('Magento\Customer\Model\Session');  
        $customerData = $customerSession->getCustomer();

        $top -= 5;
        $txt2 = 'Tamer Store';
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $top -= 15;
        $txt2 = $orderds['customer_firstname'].' '.$orderds['customer_lastname'];
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );


        $top -= 15;
        $txt2 = $orderds['shipping_address_label'];
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );

        $countrycode = $orderds['countryorderd'];
        $top -= 15;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $txt2 = $objectManager->create('\Magento\Directory\Model\Country')->load("$countrycode")->getName();
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );
        
        $top -= 15;
        $txt2 = "";
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );


        $top -= 15;
        $txt2 = "";
        $page->drawText(
            trim(strip_tags($txt2)),
            $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) - $this->widthForStringUsingFontSize(trim(strip_tags($txt2)), $oFont, $fontSize) + 25,
            $top,
            'UTF-8'
        );


        $top -= 55;

        $this->y = $this->y > $top ? $top : $this->y;
    }
    
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        
        $this->y = $this->y ? $this->y : 815;
        $top = $this->y;

       

        if ($putOrderId) {
            $this->_setFontBold($page, 15);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
            $page->drawText('Order', 25, $top, 'UTF-8');
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
            $this->_setFontRegular($page, 10);
            $top +=15;
        }
        $this->y -= 15;
        
    }
    
    
    
    protected function insertOrder2(&$page, $obj, $putOrderId = true, $ordericre)
    {
        
        $this->y = $this->y ? $this->y : 815;
        $top = $this->y;

       

        if ($putOrderId) {
            $this->_setFontBold($page, 15);
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
            $page->drawText('Order '.$ordericre, 25, $top, 'UTF-8');
            $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
            $this->_setFontRegular($page, 10);
            $top +=15;
        }
        $this->y -= 15;
        
    }

    /**
     * Draw header for item table
     *
     * @param \Zend_Pdf_Page $page
     * @return void
     */
    
}
