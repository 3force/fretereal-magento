<?php
$attributeGroup = 'Dimensions';

$attributes = array(
    'package_height' => array(
       'group'             => $attributeGroup,
       'input'             => 'text',
       'type'              => 'decimal',
       'label'             => 'Package Height (cm)',
       'visible'           => true,
       'required'          => false,
       'user_defined'         => false,
       'searchable'           => false,
       'filterable'           => false,
       'comparable'           => false,
       'visible_on_front'        => false,
       'visible_in_advanced_search' => false,
       'is_html_allowed_on_front'   => false,
       'used_for_promo_rules'       => true,
       'frontend_class'       => 'validate-number', 
       'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
       'apply_to'          => 'simple,grouped,configurable'
    ),
    'package_width' => array(
       'group'             => $attributeGroup,
       'input'             => 'text',
       'type'              => 'decimal',
       'label'             => 'Package Width (cm)',
       'visible'           => true,
       'required'          => false,
       'user_defined'         => false,
       'searchable'           => false,
       'filterable'           => false,
       'comparable'           => false,
       'visible_on_front'        => false,
       'visible_in_advanced_search' => false,
       'is_html_allowed_on_front'   => false,
       'used_for_promo_rules'       => true,
       'frontend_class'       => 'validate-number', 
       'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
       'apply_to'          => 'simple,grouped,configurable'
    ),
    'package_length' => array(
       'group'             => $attributeGroup,
       'input'             => 'text',
       'type'              => 'decimal',
       'label'             => 'Package Length (cm)',
       'visible'           => true,
       'required'          => false,
       'user_defined'         => false,
       'searchable'           => false,
       'filterable'           => false,
       'comparable'           => false,
       'visible_on_front'        => false,
       'visible_in_advanced_search' => false,
       'is_html_allowed_on_front'   => false,
       'used_for_promo_rules'       => true,
       'frontend_class'       => 'validate-number', 
       'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
       'apply_to'          => 'simple,grouped,configurable'
    )
);

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('sales/order'),'fretereal_token', 'varchar(255)');
$installer->getConnection()->addColumn($installer->getTable('sales/order'),'fretereal_caixas', 'text');

$installer->addAttributeGroup(Mage_Catalog_Model_Product::ENTITY, "Default", $attributeGroup, 1000);

foreach($attributes as $attributeCode => $options) {
    $installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode, $options);
}

$installer->endSetup();

?>