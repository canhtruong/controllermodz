<?php

namespace Os\DsfhCustomer\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();

        if (!$setup->getConnection()->isTableExists($setup->getTable('dsfh_customer_approved'))) {

            $table = $setup->getConnection()->newTable($setup->getTable('dsfh_customer_approved'))
                ->addColumn(
                    'DSFH_CUSTOMER_APPROVED_ID',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Approved ID'
                )
                ->addColumn(
                    'ROW_ID',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'ROW ID'
                )
                ->addColumn(
                    'DATE',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true],
                    'DATE'
                )
                ->addColumn(
                    'REQUEST_ID',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'REQUEST ID'
                )
                ->addColumn(
                    'RELEASE_NUMBER',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'RELEASE NUMBER'
                )
                ->addColumn(
                    'TAMER_ITEM_NUMBER',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'TAMER ITEM NUMBER'
                )
                ->addColumn(
                    'FAKIEH_ITEM_NUMBER',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'FAKIEH ITEM NUMBER'
                    )
                ->addColumn(
                    'ITEM_DESCRIPTION',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'ITEM DESCRIPTION'
                )
                ->addColumn(
                    'UOM',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'UOM'
                )
                ->addColumn(
                    'QUANTITY',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true],
                    'QUANTITY'
                )
                ->addColumn(
                    'PRICE',
                    \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    null,
                    ['nullable' => true],
                    'PRICE'
                )
                ->addColumn(
                    'AMOUNT',
                    \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    null,
                    ['nullable' => true],
                    'AMOUNT'
                )
                ->addColumn(
                    'FLAG',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'FLAG'
                )
                ->setComment('Os DSFH Customer')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');

            $setup->getConnection()->createTable($table);

        }

        $setup->endSetup();

    }

}