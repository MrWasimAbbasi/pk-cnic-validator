# Pakistan CNIC Validator

A PHP package for validating Pakistan CNIC (Computerized National Identity Card) numbers with and without dashes, following PSR standards.

## Features

- ✅ Validate CNIC numbers with dashes (format: `12345-1234567-1`)
- ✅ Validate CNIC numbers without dashes (format: `1234512345671`)
- ✅ Format CNIC numbers between dash and non-dash formats
- ✅ Extract detailed information from valid CNIC numbers
- ✅ Comprehensive validation rules for Pakistan CNIC structure
- ✅ PSR-12 coding standards compliance
- ✅ Full unit test coverage
- ✅ Static analysis with PHPStan

## Installation

### Via Composer

```bash
composer require wasim/pk-cnic-validator
```

### Manual Installation

1. Clone the repository:
```bash
git clone https://github.com/MrWasimAbbasi/pk-cnic-validator.git
cd pk-cnic-validator
```

2. Install dependencies:
```bash
composer install
```

## Usage

### Basic Validation

```php
use PkCnicValidator\CnicValidator;

$validator = new CnicValidator();

// Validate CNIC with dashes
$isValid = $validator->isValid('12345-1234567-1'); // true

// Validate CNIC without dashes
$isValid = $validator->isValid('1234512345671'); // true

// Validate invalid CNIC
$isValid = $validator->isValid('invalid'); // false
```

### Specific Format Validation

```php
// Check if CNIC is in dash format
$hasDashes = $validator->isValidWithDashes('12345-1234567-1'); // true
$hasDashes = $validator->isValidWithDashes('1234512345671'); // false

// Check if CNIC is in numeric format
$isNumeric = $validator->isValidWithoutDashes('1234512345671'); // true
$isNumeric = $validator->isValidWithoutDashes('12345-1234567-1'); // false
```

### Formatting

```php
// Format CNIC to include dashes
$withDashes = $validator->formatWithDashes('1234512345671'); // '12345-1234567-1'

// Format CNIC to remove dashes
$withoutDashes = $validator->formatWithoutDashes('12345-1234567-1'); // '1234512345671'

// Invalid CNIC returns null
$formatted = $validator->formatWithDashes('invalid'); // null
```

### Information Extraction

```php
$info = $validator->extractInfo('12345-1234567-1');

// Returns:
[
    'cnic' => '12345-1234567-1',
    'cnic_with_dashes' => '12345-1234567-1',
    'cnic_without_dashes' => '1234512345671',
    'province_code' => '1',
    'district_code' => '12',
    'family_number' => '345',
    'serial_number' => '1234567',
    'check_digit' => '1'
]
```

## CNIC Structure

Pakistan CNIC follows this structure:

- **Province Code** (1 digit): 1-9
- **District Code** (2 digits): 11-99
- **Family Number** (3 digits): 001-999
- **Serial Number** (7 digits): 0000001-9999999
- **Check Digit** (1 digit): 0-9

### Valid Formats

1. **With Dashes**: `12345-1234567-1`
2. **Without Dashes**: `1234512345671`

## Testing

### Run Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage
```

### Code Quality

```bash
# Check coding standards
composer cs

# Fix coding standards automatically
composer cs-fix

# Run static analysis
composer stan
```

## Requirements

- PHP >= 7.4
- Composer

## Development

### Project Structure

```
pk-cnic-validator/
├── src/
│   └── CnicValidator.php
├── tests/
│   ├── CnicValidatorTest.php
│   └── IntegrationTest.php
├── composer.json
├── phpunit.xml
├── phpcs.xml
├── phpstan.neon
└── README.md
```

### Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

If you encounter any issues or have questions, please open an issue on GitHub.

## Changelog

### Version 1.0.0
- Initial release
- CNIC validation with and without dashes
- Format conversion functionality
- Information extraction
- Comprehensive test coverage 