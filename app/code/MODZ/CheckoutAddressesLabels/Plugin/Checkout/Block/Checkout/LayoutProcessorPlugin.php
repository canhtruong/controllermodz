<?php

namespace MODZ\CheckoutAddressesLabels\Plugin\Checkout\Block\Checkout;

class LayoutProcessorPlugin
{
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['street'] = [
            'component' => 'Magento_Ui/js/form/components/group',
            'label' => __('Street Address'), // I removed main label
            'required' => false, //turn false because I removed main label
            'dataScope' => 'shippingAddress.street',
            'provider' => 'checkoutProvider',
            'sortOrder' => 50,
            'type' => 'group',
            'additionalClasses' => 'street',
            'children' => [
                [
                    'label' => __('Street Address: Line 1'),
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'shippingAddress',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '0',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ],
                [
                    'label' => __('Street Address: Line 2 (Optional)'),
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'shippingAddress',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '1',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => false, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ],
            ]
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children']['billing-address-form-shared']['children']['form-fields']['children']['street'] = [
            'component' => 'Magento_Ui/js/form/components/group',
            'label' => __('Street Address'), // I removed main label
            'required' => false, //turn false because I removed main label
            'dataScope' => 'billingAddressshared.street',
            'provider' => 'checkoutProvider',
            'sortOrder' => 50,
            'type' => 'group',
            'additionalClasses' => 'street',
            'children' => [
                [
                    'label' => __('Street Address: Line 1'),
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'billingAddressshared',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '0',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ],
                [
                    'label' => __('Street Address: Line 2 (Optional)'),
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'billingAddressshared',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '1',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => false, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ],
            ]
        ];
        return $jsLayout;
    }
}
