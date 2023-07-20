<?php
namespace Os\DsfhCustomer\Model;
class Area extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() 
    {
        

        $type=[
            [
                'value' => '',
                'label' => ''
            ],
            [
                'value' => 'CENTRAL',
                'label' => 'CENTRAL'
            ],
            [
                'value' => 'EASTERN',
                'label' => 'EASTERN'
            ],
            [
                'value' => 'NONE',
                'label' => 'NONE'
            ],
            [
                'value' => 'NORTH',
                'label' => 'NORTH'
            ],
            [
                'value' => 'SOUTHERN',
                'label' => 'SOUTHERN'
            ],
            [
                'value' => 'WESTERN',
                'label' => 'WESTERN'
            ],
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
