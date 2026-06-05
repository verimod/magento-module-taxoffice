<?php
/**
 * Verimod Tax Office Module - Checkout Layout Processor Plugin
 *
 * @category    Verimod
 * @package     Verimod_TaxOffice
 */

namespace Verimod\TaxOffice\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\Stdlib\ArrayManager;

class CheckoutLayoutProcessorPlugin
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * Constructor
     *
     * @param ArrayManager $arrayManager
     */
    public function __construct(ArrayManager $arrayManager)
    {
        $this->arrayManager = $arrayManager;
    }

    /**
     * Process checkout layout to add tax office field to billing address only
     *
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        $billingAddressPath = 'components/checkout/children/steps/children/billing-step/children/payment/children/payments-list/children/*/children/billing-address-form/children/form-fields/children';
        $shippingAddressPath = 'components/checkout/children/steps/children/shipping-step/children/shippingAddress/children/shipping-address-fieldset/children';

        // Add tax_office_number field to billing address
        $billingChildren = $this->arrayManager->get($billingAddressPath, $jsLayout);
        
        if ($billingChildren) {
            foreach ($billingChildren as $paymentMethod => $fields) {
                if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethod]['children']['billing-address-form']['children']['form-fields']['children'])) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethod]['children']['billing-address-form']['children']['form-fields']['children']['tax_office_number'] = $this->getTaxOfficeFieldConfig();
                }
            }
        }

        // Remove tax_office_number field from shipping address if it exists
        $shippingChildren = $this->arrayManager->get($shippingAddressPath, $jsLayout);
        
        if ($shippingChildren && isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['tax_office_number'])) {
            $jsLayout = $this->arrayManager->remove(
                $shippingAddressPath . '/tax_office_number',
                $jsLayout
            );
        }

        return $jsLayout;
    }

    /**
     * Get tax office field configuration
     *
     * @return array
     */
    private function getTaxOfficeFieldConfig()
    {
        return [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'billingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'tax_office_number'
            ],
            'dataScope' => 'billingAddress.tax_office_number',
            'label' => __('Vergi Müşterisisi Kimlik Numarası'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => false,
                'max-text-length' => 255
            ],
            'sortOrder' => 100,
            'id' => 'tax_office_number'
        ];
    }
}