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

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Bss\GiftCard\Model\Product\Type\GiftCard;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Catalog\Model\Product\Attribute\Backend\Price;
use Bss\GiftCard\Model\Attribute\Source\Type;
use Bss\GiftCard\Model\Attribute\Source\Pattern;
use Bss\GiftCard\Model\Attribute\Source\Template;
use Bss\GiftCard\Model\Attribute\Source\Backend\Template as TemplateBackend;
use Bss\GiftCard\Model\Attribute\Source\Price as PriceAttribute;
use Bss\GiftCard\Model\Attribute\Source\Backend\Amounts;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Model\Order;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallData
 * @package Bss\GiftCard\Setup
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Sales setup factory
     *
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * Quote setup factory
     *
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        SalesSetupFactory $salesSetupFactory,
        QuoteSetupFactory $quoteSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $groupName = GiftCard::BSS_GIFT_CARD_GROUP;
        $fieldList = [
            'weight',
            'tax_class_id'
        ];

        foreach ($fieldList as $field) {
            $applyTo = explode(
                ',',
                $eavSetup->getAttribute(Product::ENTITY, $field, 'apply_to')
            );
            if (!in_array(GiftCard::BSS_GIFT_CARD, $applyTo)) {
                $applyTo[] = GiftCard::BSS_GIFT_CARD;
                $eavSetup->updateAttribute(
                    Product::ENTITY,
                    $field,
                    'apply_to',
                    implode(',', $applyTo)
                );
            }
        }

        $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

        foreach ($attributeSetIds as $attributeSetId) {
            $eavSetup->addAttributeGroup(
                $entityTypeId,
                $attributeSetId,
                $groupName,
                99
            );
        }

        $this->addAttribute($eavSetup, $groupName);
        $this->saleSetup($setup);
        $this->quoteSetup($setup);
    }

    /**
     * @param mixed $eavSetup
     * @param string $groupName
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function addAttribute($eavSetup, $groupName)
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD,
            [
                'group' => $groupName,
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'sort_order' => 110,
                'label' => 'Type',
                'input' => 'select',
                'class' => '',
                'source' => Type::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_AMOUNTS,
            [
                'group' => $groupName,
                'type' => 'static',
                'backend' => Amounts::class,
                'frontend' => '',
                'sort_order' => 120,
                'label' => 'Amounts',
                'input' => 'text',
                'class' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_DYNAMIC_PRICE,
            [
                'group' => $groupName,
                'type' => 'int',
                'frontend' => '',
                'sort_order' => 130,
                'label' => 'Dynamic Price',
                'input' => 'boolean',
                'class' => '',
                'source' => Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_OPEN_MIN_AMOUNT,
            [
                'group' => $groupName,
                'type' => 'decimal',
                'backend' => Price::class,
                'frontend' => '',
                'sort_order' => 140,
                'label' => 'Min Value',
                'input' => 'price',
                'frontend_class' => 'validate-greater-than-zero',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_OPEN_MAX_AMOUNT,
            [
                'group' => $groupName,
                'type' => 'decimal',
                'backend' => Price::class,
                'frontend' => '',
                'sort_order' => 150,
                'label' => 'Max Value',
                'input' => 'price',
                'frontend_class' => 'validate-greater-than-zero',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_PERCENTAGE_TYPE,
            [
                'group' => $groupName,
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'sort_order' => 160,
                'label' => 'Percentage Price',
                'input' => 'select',
                'class' => '',
                'source' => PriceAttribute::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_PERCENTAGE_VALUE,
            [
                'group' => $groupName,
                'type' => 'int',
                'backend' => Price::class,
                'frontend' => '',
                'sort_order' => 170,
                'label' => 'Value',
                'input' => 'text',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 100,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_TEMPLATE,
            [
                'group' => $groupName,
                'type' => 'text',
                'frontend' => '',
                'sort_order' => 180,
                'label' => 'Template',
                'input' => 'multiselect',
                'class' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'source' => Template::class,
                'backend' => TemplateBackend::class,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_CODE_PATTERN,
            [
                'group' => $groupName,
                'type' => 'int',
                'frontend' => '',
                'sort_order' => 190,
                'label' => 'Gift Code Pattern',
                'input' => 'select',
                'class' => '',
                'source' => Pattern::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_MESSAGE,
            [
                'group' => $groupName,
                'type' => 'int',
                'frontend' => '',
                'sort_order' => 200,
                'label' => 'Message',
                'input' => 'boolean',
                'class' => '',
                'source' => Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            GiftCard::BSS_GIFT_CARD_EXPIRES,
            [
                'group' => $groupName,
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'sort_order' => 210,
                'label' => 'Expires After (days)',
                'input' => 'text',
                'frontend_class' => 'validate-zero-or-greater validate-number',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 0,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => GiftCard::BSS_GIFT_CARD
            ]
        );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function saleSetup($setup)
    {
        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute(
            Order::ENTITY,
            'bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        )->addAttribute(
            Order::ENTITY,
            'base_bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        );

        $salesSetup->addAttribute(
            'invoice',
            'bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        )->addAttribute(
            'invoice',
            'base_bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        );

        $salesSetup->addAttribute(
            'creditmemo',
            'bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        )->addAttribute(
            'creditmemo',
            'base_bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function quoteSetup($setup)
    {

        /** @var \Magento\Quote\Setup\QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        $quoteSetup->addAttribute(
            'quote',
            'bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        )->addAttribute(
            'quote',
            'base_bss_giftcard_amount',
            ['type' => Table::TYPE_DECIMAL]
        );
    }
}
