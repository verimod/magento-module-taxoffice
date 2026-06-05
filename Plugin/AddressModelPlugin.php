<?php
/**
 * Verimod Tax Office Module - Address Model Plugin
 *
 * @category    Verimod
 * @package     Verimod_TaxOffice
 */

namespace Verimod\TaxOffice\Plugin;

use Magento\Customer\Model\Address;

class AddressModelPlugin
{
    /**
     * After get address type
     *
     * @param Address $subject
     * @param string $result
     * @return string
     */
    public function afterGetAddressType(Address $subject, $result)
    {
        return $result;
    }

    /**
     * Before save - validate address type for tax office field
     *
     * @param Address $subject
     * @return void
     */
    public function beforeSave(Address $subject)
    {
        // If this is a shipping-only address, remove tax_office_number
        if ($subject->getIsDefaultShipping() && !$subject->getIsDefaultBilling()) {
            $subject->unsetData('tax_office_number');
        }
    }
}