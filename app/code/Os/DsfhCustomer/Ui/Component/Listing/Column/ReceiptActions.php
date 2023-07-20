<?php

namespace Os\DsfhCustomer\Ui\Component\Listing\Column;

class ReceiptActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    const URL_PATH_EDIT = 'dsfhcustomer/receipts/edit';
    const URL_PATH_DELETE = 'dsfhcustomer/receipts/delete';
    const URL_PATH_DETAILS = 'dsfhcustomer/receipts/details';

    private $urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */

    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {

                if (isset($item['DSFH_CUSTOMER_RECEIPT_ID'])) {

                    $item[$this->getData('name')] = [

                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                ['id' => $item['DSFH_CUSTOMER_RECEIPT_ID']]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                ['id' => $item['DSFH_CUSTOMER_RECEIPT_ID']]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete receipt confirmation'),
                                'message' => __('Are you sure you want to delete this receipt confirmation ?')
                            ]
                        ]
                    ];

                }

            }

        }

        return $dataSource;

    }

}

