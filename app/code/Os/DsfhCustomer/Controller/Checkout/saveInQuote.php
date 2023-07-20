<?php
 
namespace Os\DsfhCustomer\Controller\Checkout;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\View\LayoutFactory;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Action\Action;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\QuoteRepository;
 
class saveInQuote extends Action
{
    protected $resultForwardFactory;
    protected $layoutFactory;
    protected $cart;
 
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        LayoutFactory $layoutFactory,
        Cart $cart,
        Session $checkoutSession,
        QuoteRepository $quoteRepository
    )
    {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->layoutFactory = $layoutFactory;
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
 
        parent::__construct($context);
    }
 
    public function execute()
    {
        $checkVal = $this->getRequest()->getParam('checkVal');

        $quoteId = $this->checkoutSession->getQuoteId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $tableName = $resource->getTableName('quote'); //gives table name with prefix
        $sql = "UPDATE ".$tableName." SET `tm_field1` = '".$checkVal."' WHERE `quote`.`entity_id` = ".$this->checkoutSession->getQuoteId().";";
        $connection->query($sql);
        
        echo $checkVal;
    }
}