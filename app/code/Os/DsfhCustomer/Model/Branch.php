<?php
namespace Os\DsfhCustomer\Model;
class Branch extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() 
    {
        $option=[
            [
                'value' => '',
                'label' => ''
            ],
            [
                'value' => 'RIYADH',
                'label' => 'RIYADH'
            ],
            [
                'value' => 'JEDDAH',
                'label' => 'JEDDAH'
            ],
            [
                'value' => 'KHOBAR',
                'label' => 'KHOBAR'
            ],
            [
                'value' => 'ABHA',
                'label' => 'ABHA'
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
