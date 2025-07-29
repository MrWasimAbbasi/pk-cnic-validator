<?php

declare(strict_types=1);

namespace PkCnicValidator;

/**
 * Pakistan CNIC (Computerized National Identity Card) Validator
 *
 * This class provides methods to validate Pakistan CNIC numbers
 * in both formats: with dashes (12345-1234567-1) and without dashes (1234512345671).
 */
class CnicValidator
{
    /**
     * CNIC pattern with dashes (format: 12345-1234567-1)
     */
    private const CNIC_WITH_DASHES_PATTERN = '/^\d{5}-\d{7}-\d{1}$/';

    /**
     * CNIC pattern without dashes (format: 1234512345671)
     */
    private const CNIC_WITHOUT_DASHES_PATTERN = '/^\d{13}$/';

    /**
     * Validates a Pakistan CNIC number
     *
     * @param string $cnic The CNIC number to validate
     * @return bool Returns true if the CNIC is valid, false otherwise
     */
    public function isValid(string $cnic): bool
    {
        $cnic = trim($cnic);

        if (empty($cnic)) {
            return false;
        }

        // Check if it's a CNIC with dashes
        if ($this->isValidWithDashes($cnic)) {
            return true;
        }

        // Check if it's a CNIC without dashes
        if ($this->isValidWithoutDashes($cnic)) {
            return true;
        }

        return false;
    }

    /**
     * Validates a Pakistan CNIC number with dashes (format: 12345-1234567-1)
     *
     * @param string $cnic The CNIC number to validate
     * @return bool Returns true if the CNIC is valid, false otherwise
     */
    public function isValidWithDashes(string $cnic): bool
    {
        $cnic = trim($cnic);

        if (empty($cnic)) {
            return false;
        }

        // Check pattern
        if (!preg_match(self::CNIC_WITH_DASHES_PATTERN, $cnic)) {
            return false;
        }

        // Remove dashes and validate the numeric part
        $numericCnic = str_replace('-', '', $cnic);
        return $this->isValidNumericCnic($numericCnic);
    }

    /**
     * Validates a Pakistan CNIC number without dashes (format: 1234512345671)
     *
     * @param string $cnic The CNIC number to validate
     * @return bool Returns true if the CNIC is valid, false otherwise
     */
    public function isValidWithoutDashes(string $cnic): bool
    {
        $cnic = trim($cnic);

        if (empty($cnic)) {
            return false;
        }

        // Check pattern
        if (!preg_match(self::CNIC_WITHOUT_DASHES_PATTERN, $cnic)) {
            return false;
        }

        return $this->isValidNumericCnic($cnic);
    }

    /**
     * Formats a CNIC number to include dashes
     *
     * @param string $cnic The CNIC number to format
     * @return string|null Returns formatted CNIC or null if invalid
     */
    public function formatWithDashes(string $cnic): ?string
    {
        $cnic = trim($cnic);

        if (empty($cnic)) {
            return null;
        }

        // If already has dashes, validate and return
        if ($this->isValidWithDashes($cnic)) {
            return $cnic;
        }

        // If no dashes, validate and format
        if ($this->isValidWithoutDashes($cnic)) {
            return $this->addDashes($cnic);
        }

        return null;
    }

    /**
     * Formats a CNIC number to remove dashes
     *
     * @param string $cnic The CNIC number to format
     * @return string|null Returns formatted CNIC or null if invalid
     */
    public function formatWithoutDashes(string $cnic): ?string
    {
        $cnic = trim($cnic);

        if (empty($cnic)) {
            return null;
        }

        // If no dashes, validate and return
        if ($this->isValidWithoutDashes($cnic)) {
            return $cnic;
        }

        // If has dashes, validate and remove dashes
        if ($this->isValidWithDashes($cnic)) {
            return str_replace('-', '', $cnic);
        }

        return null;
    }

    /**
     * Extracts information from a valid CNIC number
     *
     * @param string $cnic The CNIC number to extract information from
     * @return array|null Returns array with CNIC info or null if invalid
     */
    public function extractInfo(string $cnic): ?array
    {
        $cnic = trim($cnic);

        if (!$this->isValid($cnic)) {
            return null;
        }

        // Remove dashes if present
        $numericCnic = str_replace('-', '', $cnic);

        return [
            'cnic' => trim($cnic),
            'cnic_with_dashes' => $this->formatWithDashes($cnic),
            'cnic_without_dashes' => $this->formatWithoutDashes($cnic),
            'province_code' => substr($numericCnic, 0, 1),
            'district_code' => substr($numericCnic, 0, 2),
            'family_number' => substr($numericCnic, 2, 3),
            'serial_number' => substr($numericCnic, 5, 7),
            'check_digit' => substr($numericCnic, 12, 1),
        ];
    }

    /**
     * Validates the numeric part of a CNIC
     *
     * @param string $cnic The numeric CNIC to validate
     * @return bool Returns true if valid, false otherwise
     */
    private function isValidNumericCnic(string $cnic): bool
    {
        if (strlen($cnic) !== 13) {
            return false;
        }

        // Check if all characters are digits
        if (!ctype_digit($cnic)) {
            return false;
        }

        // Basic validation rules for Pakistan CNIC
        $provinceCode = (int) substr($cnic, 0, 1);
        $districtCode = (int) substr($cnic, 0, 2);
        $familyNumber = (int) substr($cnic, 2, 3);
        $serialNumber = (int) substr($cnic, 5, 7);
        $checkDigit = (int) substr($cnic, 12, 1);

        // Province code should be 1-9
        if ($provinceCode < 1 || $provinceCode > 9) {
            return false;
        }

        // District code should be 11-99
        if ($districtCode < 11 || $districtCode > 99) {
            return false;
        }

        // Family number should be 001-999
        if ($familyNumber < 1 || $familyNumber > 999) {
            return false;
        }

        // Serial number should be 0000001-9999999
        if ($serialNumber < 1 || $serialNumber > 9999999) {
            return false;
        }

        // Check digit should be 0-9
        if ($checkDigit < 0 || $checkDigit > 9) {
            return false;
        }

        return true;
    }

    /**
     * Adds dashes to a numeric CNIC
     *
     * @param string $cnic The numeric CNIC
     * @return string The CNIC with dashes
     */
    private function addDashes(string $cnic): string
    {
        return substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12, 1);
    }
}
