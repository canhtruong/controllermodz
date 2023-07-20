<?php
namespace Os\DsfhCustomer\Model;
class Billingshipping extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() 
    {
        $type = [];
        $type[] = [
                'value' => '',
                'label' => 'Billing & Shipping'
            ];
        $type[] = [
                'value' => 1,
                'label' => 'Billing'
            ];
        $type[] = [
                'value' => 2,
                'label' => 'Shipping'
            ];
        return $type;
    }
    
    public function getOptionText($value) 
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}
