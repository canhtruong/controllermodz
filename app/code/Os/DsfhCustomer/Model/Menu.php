<?php
namespace Os\DsfhCustomer\Model;
class Menu extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() 
    {
        $option=[
            [
                'value' => '1',
                'label' => 'Yes'
            ],
            [
                'value' => '0',
                'label' => 'No'
            ]
        ]; 

        return $option; 
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
