<?php
/**
 * Verimod Tax Office Module - Setup InstallData
 *
 * @category    Verimod
 * @package     Verimod_TaxOffice
 */

namespace Verimod\TaxOffice\Setup;

use Magento\Customer\Model\Address;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * Constructor
     *
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * Install data
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        // Create tax_office_number attribute for customer address
        $customerSetup->addAttribute(
            Address::ENTITY,
            'tax_office_number',
            [
                'type' => 'varchar',
                'label' => 'Vergi Müşterisisi Kimlik Numarası',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => false,
                'is_used_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'sort_order' => 100,
                'position' => 100,
                'length' => 255,
                'used_in_forms' => [
                    'customer_account_create',
                    'customer_account_edit',
                    'customer_address_edit',
                    'customer_register_address',
                    'adminhtml_customer_address',
                    'checkout_billing_address'
                ]
            ]
        );

        $setup->endSetup();
    }
}
