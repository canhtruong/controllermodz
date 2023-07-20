<?php
namespace Os\DsfhCustomer\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            $tableName = $setup->getTable('dsfh_customer_approved');

            $setup->getConnection()->changeColumn($tableName, 'PRICE', 'PRICE', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '10,4',
                'nullable' => true,
                'comment' => 'PRICE'
            ]);

            $setup->getConnection()->changeColumn($tableName, 'AMOUNT', 'AMOUNT', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '10,4',
                'nullable' => true,
                'comment' => 'AMOUNT'
            ]);
        }

        // Daily On Hand Stock
        if (version_compare($context->getVersion(), '1.0.2', '<')) {

            if (!$setup->getConnection()->isTableExists($setup->getTable('dsfh_daily_on_hand_stock'))) {

                $table = $setup->getConnection()->newTable($setup->getTable('dsfh_daily_on_hand_stock'))
                    ->addColumn(
                        'DSFH_CUSTOMER_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'DSFH_DAILY ON HAND STOCK ID'
                    )
                    ->addColumn(
                        'DSFH_REQUEST_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'DSFH REQUEST ID'
                    )
                    ->addColumn(
                        'ONHAND_DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'ONHAND DATE'
                    )
                    ->addColumn(
                        'CUSTOMER_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER ID'
                    )
                    ->addColumn(
                        'CUSTOMER_NUMBER',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER NUMBER'
                    )
                    ->addColumn(
                        'CUSTOMER_NAME',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER NAME'
                    )
                    ->addColumn(
                        'CUSTOMER_ORGANIZATION_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER ORGANIZATION ID'
                        )
                    ->addColumn(
                        'CUSTOMER_ITEM_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'CUSTOMER ITEM ID'
                    )
                    ->addColumn(
                        'CUSTOMER_ITEM_NUMBER',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER ITEM NUMBER'
                    )
                    ->addColumn(
                        'CUSTOMER_ITEM_DESCRIPTIOA',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true],
                        'CUSTOMER ITEM DESCRIPTIOA'
                    )
                    ->addColumn(
                        'PRIMARY_UOM_CODE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'PRIMARY UOM CODE'
                    )
                    ->addColumn(
                        'SUBINVENTORY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'SUBINVENTORY'
                    )
                    ->addColumn(
                        'LOT',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'LOT'
                    )
                    ->addColumn(
                        'ORIGINATION_DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'ORIGINATION DATE'
                    )
                    ->addColumn(
                        'EXPIRY_DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'EXPIRY DATE'
                    )
                    ->addColumn(
                        'QTY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true,],
                        'QTY'
                    )
                    ->addColumn(
                        'STATUS',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'STATUS'
                    )
                    ->addColumn(
                        'CREATION_DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'CREATION DATE'
                    )
                    ->addColumn(
                        'CREATION_BY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CREATION BY'
                    )
                    ->addColumn(
                        'LAST_UPDATE_DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'LAST UPDATE DATE'
                    )
                    ->addColumn(
                        'LAST_UPDATE_BY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'LAST UPDATE BY'
                    )
                    ->addColumn(
                        'SEQID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'SEQID'
                    )
                    ->setComment('Os DSFH Daily On Hand Stock')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');

                $setup->getConnection()->createTable($table);
    
            }
        }

        // Suggested
        if (version_compare($context->getVersion(), '1.0.3', '<')) {

            if (!$setup->getConnection()->isTableExists($setup->getTable('dsfh_customer_suggested'))) {

                $table = $setup->getConnection()->newTable($setup->getTable('dsfh_customer_suggested'))
                    ->addColumn(
                        'DSFH_CUSTOMER_SUGGESTED_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'DSFH CUSTOMER SUGGESTED ID'
                    )
                    ->addColumn(
                        'REQUEST_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'REQUEST_ID'
                    )
                    ->addColumn(
                        'TRX_DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'TRX DATE'
                    )
                    ->addColumn(
                        'TAMER_ITEM_NO',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'TAMER ITEM NO'
                    )
                    ->addColumn(
                        'CUSTOMER_ITEM_NUMBER',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'CUSTOMER ITEM NUMBER'
                    )
                    ->addColumn(
                        'CUSTOMER_ITEM_DESCRIPTIOA',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER ITEM DESCRIPTIOA'
                    )
                    ->addColumn(
                        'UNIT_PRICE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        '10,4',
                        ['nullable' => true,],
                        'UNIT PRICE'
                        )
                    ->addColumn(
                        'UOM_CODE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'UOM CODE'
                    )
                    ->addColumn(
                        'QTY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true,],
                        'QTY'
                    )
                    ->setComment('Os DSFH Customer Suggested')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');

                $setup->getConnection()->createTable($table);
    
            }
        }

        // Receipt
        if (version_compare($context->getVersion(), '1.0.4', '<')) {

            if (!$setup->getConnection()->isTableExists($setup->getTable('dsfh_customer_receipt'))) {

                $table = $setup->getConnection()->newTable($setup->getTable('dsfh_customer_receipt'))
                    ->addColumn(
                        'DSFH_CUSTOMER_RECEIPT_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'DSFH CUSTOMER RECEIPT ID'
                    )
                    ->addColumn(
                        'REQUEST_ID',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'REQUEST_ID'
                    )
                    ->addColumn(
                        'DATE',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        null,
                        ['nullable' => true,],
                        'DATE'
                    )
                    ->addColumn(
                        'TAMER_ITEM_NUMBER',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => false,],
                        'TAMER ITEM NUMBER'
                    )
                    ->addColumn(
                        'CUSTOMER_ITEM_NUMBER',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'CUSTOMER ITEM NUMBER'
                    )
                    ->addColumn(
                        'ITEM_DESCRIPTION',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'ITEM DESCRIPTION'
                    )
                    ->addColumn(
                        'UOM',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        ['nullable' => true,],
                        'UOM'
                        )
                    ->addColumn(
                        'ORDER_QTY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true,],
                        'ORDER QTY'
                    )
                    ->addColumn(
                        'RECEIVED_QTY',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => true,],
                        'RECEIVED QTY'
                    )
                    ->setComment('Os DSFH Customer Receipt')
                    ->setOption('type', 'InnoDB')
                    ->setOption('charset', 'utf8');

                $setup->getConnection()->createTable($table);
    
            }
        }

        if (version_compare($context->getVersion(), '1.0.5', '<')) {

            $tableName = $setup->getTable('dsfh_daily_on_hand_stock');

            $setup->getConnection()->changeColumn($tableName, 'ONHAND_DATE', 'ONHAND_DATE', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                'nullable' => true,
                'comment' => 'ONHAND DATE'
            ]);

        }
        
        $setup->endSetup();
    }
}