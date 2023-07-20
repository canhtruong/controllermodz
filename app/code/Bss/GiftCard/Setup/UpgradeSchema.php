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
 * @copyright  Copyright (c) 2017-2020 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\GiftCard\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $setup->startSetup();
            $tableName = $setup->getTable('bss_giftcard_pattern_code');

            if ($setup->getConnection()->isTableExists($tableName)) {
                $setup->getConnection()->addColumn(
                    $tableName,
                    'sent_expire_notify',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '0',
                        'unsigned' => true,
                        'comment' => 'Was sent Notification Email about Expire code'
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '1.0.2') <= 0) {
            $setup->startSetup();
            $tableName = $setup->getTable('bss_giftcard_pattern_code');
            if ($setup->getConnection()->isTableExists($tableName)) {
                $setup->getConnection()->addColumn(
                    $tableName,
                    'store_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '0',
                        'unsigned' => true,
                        'comment' => 'Store Id'
                    ]
                );
            }
        }
        $setup->endSetup();
    }
}
