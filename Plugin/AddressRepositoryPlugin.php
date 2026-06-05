<?php
/**
 * Verimod Tax Office Module - Address Repository Plugin
 *
 * @category    Verimod
 * @package     Verimod_TaxOffice
 */

namespace Verimod\TaxOffice\Plugin;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;

class AddressRepositoryPlugin
{
    /**
     * Process address before save - ensure tax_office_number is only on billing
     *
     * @param AddressRepositoryInterface $subject
     * @param callable $proceed
     * @param AddressInterface $address
     * @return AddressInterface
     */
    public function aroundSave(
        AddressRepositoryInterface $subject,
        callable $proceed,
        AddressInterface $address
    ) {
        // If this is a shipping address, remove tax_office_number
        if ($address->getIsDefaultShipping() && !$address->getIsDefaultBilling()) {
            $address->unsetData('tax_office_number');
        }

        return $proceed($address);
    }
}