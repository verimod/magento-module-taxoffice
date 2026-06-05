<?php
/**
 * Verimod Tax Office Module - Tax Office Attribute Model
 *
 * @category    Verimod
 * @package     Verimod_TaxOffice
 */

namespace Verimod\TaxOffice\Model\Attribute;

class TaxOfficeAttribute
{
    /**
     * Attribute code
     */
    const ATTRIBUTE_CODE = 'tax_office_number';

    /**
     * Get attribute code
     *
     * @return string
     */
    public function getAttributeCode()
    {
        return self::ATTRIBUTE_CODE;
    }
}
