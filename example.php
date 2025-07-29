<?php

require_once 'vendor/autoload.php';

use PkCnicValidator\CnicValidator;

// Create validator instance
$validator = new CnicValidator();

echo "Pakistan CNIC Validator Example\n";
echo "===============================\n\n";

// Example 1: Validate CNIC with dashes
$cnicWithDashes = '12345-1234567-1';
echo "Example 1: Validating CNIC with dashes\n";
echo "CNIC: $cnicWithDashes\n";
echo "Is Valid: " . ($validator->isValid($cnicWithDashes) ? 'Yes' : 'No') . "\n";
echo "Is Valid with Dashes: " . ($validator->isValidWithDashes($cnicWithDashes) ? 'Yes' : 'No') . "\n";
echo "Is Valid without Dashes: " . ($validator->isValidWithoutDashes($cnicWithDashes) ? 'Yes' : 'No') . "\n\n";

// Example 2: Validate CNIC without dashes
$cnicWithoutDashes = '1234512345671';
echo "Example 2: Validating CNIC without dashes\n";
echo "CNIC: $cnicWithoutDashes\n";
echo "Is Valid: " . ($validator->isValid($cnicWithoutDashes) ? 'Yes' : 'No') . "\n";
echo "Is Valid with Dashes: " . ($validator->isValidWithDashes($cnicWithoutDashes) ? 'Yes' : 'No') . "\n";
echo "Is Valid without Dashes: " . ($validator->isValidWithoutDashes($cnicWithoutDashes) ? 'Yes' : 'No') . "\n\n";

// Example 3: Format CNIC
echo "Example 3: Formatting CNIC\n";
echo "Original (without dashes): $cnicWithoutDashes\n";
echo "Formatted with dashes: " . $validator->formatWithDashes($cnicWithoutDashes) . "\n";
echo "Original (with dashes): $cnicWithDashes\n";
echo "Formatted without dashes: " . $validator->formatWithoutDashes($cnicWithDashes) . "\n\n";

// Example 4: Extract information
echo "Example 4: Extracting CNIC information\n";
$info = $validator->extractInfo($cnicWithDashes);
if ($info) {
    echo "CNIC: " . $info['cnic'] . "\n";
    echo "With Dashes: " . $info['cnic_with_dashes'] . "\n";
    echo "Without Dashes: " . $info['cnic_without_dashes'] . "\n";
    echo "Province Code: " . $info['province_code'] . "\n";
    echo "District Code: " . $info['district_code'] . "\n";
    echo "Family Number: " . $info['family_number'] . "\n";
    echo "Serial Number: " . $info['serial_number'] . "\n";
    echo "Check Digit: " . $info['check_digit'] . "\n\n";
}

// Example 5: Invalid CNIC
echo "Example 5: Invalid CNIC\n";
$invalidCnic = 'invalid-cnic-123';
echo "CNIC: $invalidCnic\n";
echo "Is Valid: " . ($validator->isValid($invalidCnic) ? 'Yes' : 'No') . "\n";
echo "Format with dashes: " . ($validator->formatWithDashes($invalidCnic) ?? 'null') . "\n";
echo "Format without dashes: " . ($validator->formatWithoutDashes($invalidCnic) ?? 'null') . "\n";
echo "Extract info: " . ($validator->extractInfo($invalidCnic) ? 'Success' : 'null') . "\n\n";

// Example 6: Handle whitespace
echo "Example 6: Handling whitespace\n";
$cnicWithSpaces = '  12345-1234567-1  ';
echo "CNIC with spaces: '$cnicWithSpaces'\n";
echo "Is Valid: " . ($validator->isValid($cnicWithSpaces) ? 'Yes' : 'No') . "\n";
echo "Formatted: " . $validator->formatWithDashes($cnicWithSpaces) . "\n"; 