<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_GiftCard
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\GiftCard\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @package Bss\GiftCard\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            $installer = $setup;
            $installer->startSetup();

            $giftCardAmountsTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_amounts')
            )->addColumn(
                'amount_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Amount ID'
            )->addColumn(
                'value',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Value'
            )->addColumn(
                'price',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Price'
            )->addColumn(
                'website_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Website Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product Id'
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_amounts',
                    'website_id',
                    'store_website',
                    'website_id'
                ),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_amounts',
                    'amount_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_amounts',
                    ['product_id']
                ),
                ['product_id']
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_amounts',
                    ['website_id']
                ),
                ['website_id']
            );
            $installer->getConnection()->createTable($giftCardAmountsTable);

            // Gift card template table
            $giftCardTemplateTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_template')
            )->addColumn(
                'template_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Template ID'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [],
                'Template name'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Template Status'
            )->addColumn(
                'code_color',
                Table::TYPE_TEXT,
                10,
                [],
                'Gift code color'
            )->addColumn(
                'message_color',
                Table::TYPE_TEXT,
                10,
                [],
                'Gift card message text color'
            )->addColumn(
                'created_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Creation Time'
            )->addColumn(
                'updated_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated Time'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_template',
                    ['template_id']
                ),
                ['template_id']
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_template',
                    ['status']
                ),
                ['status']
            );
            $installer->getConnection()->createTable($giftCardTemplateTable);

            // Gift card template images table
            $giftCardImagesTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_template_images')
            )->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Image ID'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                255,
                [],
                'Value'
            )->addColumn(
                'template_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Template Id'
            )->addColumn(
                'position',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Position'
            )->addColumn(
                'label',
                Table::TYPE_TEXT,
                255,
                [],
                'Alt'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_template_images',
                    ['template_id']
                ),
                ['template_id']
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_template_images',
                    'template_id',
                    'bss_giftcard_template',
                    'template_id'
                ),
                'template_id',
                $installer->getTable('bss_giftcard_template'),
                'template_id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->createTable($giftCardImagesTable);

            // Gift card pattern table
            $giftCardPatternTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_pattern')
            )->addColumn(
                'pattern_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Pattern ID'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [],
                'Name'
            )->addColumn(
                'pattern',
                Table::TYPE_TEXT,
                255,
                [],
                'Pattern'
            )->addColumn(
                'pattern_code_qty',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Total Qty Code'
            )->addColumn(
                'pattern_code_unused',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Total Qty Unused Code'
            )->addColumn(
                'pattern_code_qty_max',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Total Qty Code Max'
            )->addColumn(
                'created_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Creation Time'
            )->addColumn(
                'updated_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated Time'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_pattern',
                    ['pattern']
                ),
                ['pattern']
            );
            $installer->getConnection()->createTable($giftCardPatternTable);

            // Gift card code pattern table
            $giftCardCodePatternTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_pattern_code')
            )->addColumn(
                'code_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Code ID'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                255,
                [],
                'Code'
            )->addColumn(
                'pattern_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Pattern Id'
            )->addColumn(
                'value',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Value'
            )->addColumn(
                'origin_value',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Origin Value'
            )->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Order Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Product Id'
            )->addColumn(
                'sent',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Sent'
            )->addColumn(
                'send_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Send At'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Status'
            )->addColumn(
                'sender_name',
                Table::TYPE_TEXT,
                255,
                [],
                'Sender Name'
            )->addColumn(
                'recipient_name',
                Table::TYPE_TEXT,
                255,
                [],
                'Recipient Name'
            )->addColumn(
                'recipient_email',
                Table::TYPE_TEXT,
                255,
                [],
                'Recipient Email'
            )->addColumn(
                'sender_email',
                Table::TYPE_TEXT,
                255,
                [],
                'Sender Email'
            )->addColumn(
                'message',
                Table::TYPE_TEXT,
                255,
                [],
                'Message'
            )->addColumn(
                'image_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Image Id'
            )->addColumn(
                'expiry_day',
                Table::TYPE_TIMESTAMP,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Expiry Day'
            )->addColumn(
                'created_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Creation Time'
            )->addColumn(
                'updated_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated Time'
            )->addColumn(
                'website_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Website Id'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_pattern_code',
                    ['code']
                ),
                ['code']
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_pattern_code',
                    ['pattern_id']
                ),
                ['pattern_id']
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_pattern_code',
                    'order_id',
                    'sales_order',
                    'entity_id'
                ),
                'order_id',
                $installer->getTable('sales_order'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_pattern_code',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_pattern_code',
                    'pattern_id',
                    'bss_giftcard_pattern',
                    'pattern_id'
                ),
                'pattern_id',
                $installer->getTable('bss_giftcard_pattern'),
                'pattern_id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->createTable($giftCardCodePatternTable);

            $giftCardProductTemplateTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_product_template')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )->addColumn(
                'template_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Template Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product Id'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_product_template',
                    ['template_id']
                ),
                ['template_id']
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_product_template',
                    ['product_id']
                ),
                ['product_id']
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_product_template',
                    'template_id',
                    'bss_giftcard_template',
                    'template_id'
                ),
                'template_id',
                $installer->getTable('bss_giftcard_template'),
                'template_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_product_template',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->createTable($giftCardProductTemplateTable);

            $giftCardProductTemplateTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_quote')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )->addColumn(
                'quote_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Quote Id'
            )->addColumn(
                'giftcard_code',
                Table::TYPE_TEXT,
                255,
                [],
                'Gift Card Code'
            )->addColumn(
                'base_giftcard_amount',
                Table::TYPE_DECIMAL,
                '12,2',
                ['unsigned' => true, 'default' => null],
                'Base Gift Card Amount'
            )->addColumn(
                'giftcard_amount',
                Table::TYPE_DECIMAL,
                '12,2',
                ['unsigned' => true, 'default' => null],
                'Gift Card Amount'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_quote',
                    ['giftcard_code']
                ),
                ['giftcard_code']
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_quote',
                    ['quote_id']
                ),
                ['quote_id']
            );
            $installer->getConnection()->createTable($giftCardProductTemplateTable);

            $giftCardProductHistoryTable = $installer->getConnection()->newTable(
                $installer->getTable('bss_giftcard_history')
            )->addColumn(
                'history_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )->addColumn(
                'code_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Code Id'
            )->addColumn(
                'quote_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Quote Id'
            )->addColumn(
                'amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Amount'
            )->addColumn(
                'created_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Creation Time'
            )->addColumn(
                'updated_time',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated Time'
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_history',
                    ['quote_id']
                ),
                ['quote_id']
            )->addIndex(
                $installer->getIdxName(
                    'bss_giftcard_history',
                    ['code_id']
                ),
                ['code_id']
            )->addForeignKey(
                $installer->getFkName(
                    'bss_giftcard_history',
                    'code_id',
                    'bss_giftcard_pattern_code',
                    'code_id'
                ),
                'code_id',
                $installer->getTable('bss_giftcard_pattern_code'),
                'code_id',
                Table::ACTION_CASCADE
            );

            $installer->getConnection()->createTable($giftCardProductHistoryTable);

            $installer->endSetup();
        }
    }
}
