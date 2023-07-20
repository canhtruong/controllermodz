<?php

namespace Os\DsfhCustomer\Controller\Exportcsv;


class Index extends \Magento\Framework\App\Action\Action

{

    protected $cart;
    protected $_productRepository;
    protected $_downloader;
    protected $_directory;

    public function __construct(

        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory,
        \Magento\Checkout\Model\Cart $cart
    )
    {
        $this->_downloader =  $fileFactory;
        $this->directory = $directory;
        $this->_productRepository = $productRepository;
        $this->cart = $cart;
        return parent::__construct($context);

    }



    public function execute()

    {
        $file = 'items.csv';
        $csv = new \Magento\Framework\File\Csv(new \Magento\Framework\Filesystem\Driver\File());  
        $csvdata = array();
        // retrieve quote items collection
        $itemsCollection = $this->cart->getQuote()->getItemsCollection();

        // get array of all items what can be display directly
        $itemsVisible = $this->cart->getQuote()->getAllVisibleItems();

        // retrieve quote items array
        $quote = $this->cart->getQuote();

        $items = $this->cart->getQuote()->getAllItems();

        $itemsInCart = array();
        $itemsInCart['sku'] = 'Sku';
        $itemsInCart['name'] = 'Name';
        $itemsInCart['qty'] = 'Qty';
        $itemsInCart['ingredient_name'] = 'Ingredient name';
        $itemsInCart['brand'] = 'Principal';
        $itemsInCart['price'] = 'Unit Price';
        $itemsInCart['tax_amount'] = 'Tax Amount';
        $itemsInCart['tax_percent'] = 'Tax Percent';
        $itemsInCart['rowtotal'] = 'Subtotal';
        $csvdata[] = $itemsInCart;
        
        
        foreach ($items as $item) {
            $product = $this->_productRepository->getById($item->getProductId());
            $itemsInCart = array();
            $itemsInCart['sku'] = $item->getSku();
            $itemsInCart['name'] = $item->getName();
            $itemsInCart['qty'] = $item->getQty();          
            $itemsInCart['ingredient_name'] = $product->getIngredientName();
            $itemsInCart['brand'] = $product->getBrandText();
            $itemsInCart['price'] = $item->getPrice();
            $itemsInCart['tax_amount'] = $item->getTaxAmount();
            $itemsInCart['tax_percent'] = $item->getTaxPercent().' %';
            $itemsInCart['rowtotal'] = $item->getRowTotalInclTax();
            $csvdata[] = $itemsInCart;
        }
        
        $itemsInCart = array();
        $itemsInCart['sku'] = '';
        $itemsInCart['name'] = '';
        $itemsInCart['qty'] = '';           
        $itemsInCart['ingredient_name'] = '';
        $itemsInCart['brand'] = '';
        $itemsInCart['price'] = '';
        $itemsInCart['tax_amount'] = "";
        $itemsInCart['tax_percent'] = "";
        $itemsInCart['rowtotal'] = "";
        $csvdata[] = $itemsInCart;
        
        $itemsInCart = array();
        $itemsInCart['sku'] = '';
        $itemsInCart['name'] = '';
        $itemsInCart['qty'] = '';           
        $itemsInCart['ingredient_name'] = '';
        $itemsInCart['brand'] = '';
        $itemsInCart['price'] = '';
        $itemsInCart['tax_amount'] = "";
        $itemsInCart['tax_percent'] = "";
        $itemsInCart['rowtotal'] = "";
        $csvdata[] = $itemsInCart;
        
        $itemsInCart = array();
        $itemsInCart['sku'] = '';
        $itemsInCart['name'] = '';
        $itemsInCart['qty'] = '';           
        $itemsInCart['ingredient_name'] = '';
        $itemsInCart['brand'] = '';
        
        $itemsInCart['tax_amount'] = "";
        $itemsInCart['tax_percent'] = "";
        $itemsInCart['price'] = 'Subtotal';
        $itemsInCart['rowtotal'] = $quote->getSubtotal();
        $csvdata[] = $itemsInCart;
        
        $itemsInCart = array();
        $itemsInCart['sku'] = '';
        $itemsInCart['name'] = '';
        $itemsInCart['qty'] = '';           
        $itemsInCart['ingredient_name'] = '';
        $itemsInCart['brand'] = '';
        
        $itemsInCart['tax_amount'] = "";
        $itemsInCart['tax_percent'] = "";
        $itemsInCart['price'] = 'Tax';
        $itemsInCart['rowtotal'] = $quote->getShippingAddress()->getData('tax_amount');
        $csvdata[] = $itemsInCart;
        
        $itemsInCart = array();
        $itemsInCart['sku'] = '';
        $itemsInCart['name'] = '';
        $itemsInCart['qty'] = '';           
        $itemsInCart['ingredient_name'] = '';
        $itemsInCart['brand'] = '';
        
        $itemsInCart['tax_amount'] = "";
        $itemsInCart['tax_percent'] = "";
        $itemsInCart['price'] = 'Grand Total';
        $itemsInCart['rowtotal'] = $quote->getGrandTotal();
        $csvdata[] = $itemsInCart;
    
        $csv->saveData($file, $csvdata);

        $fileName = 'items.csv';
        return $this->_downloader->create($file,array('type' => 'filename', 'value' => $file,'rm' => true));

    }

}