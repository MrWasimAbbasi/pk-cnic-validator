<?php

declare(strict_types=1);

namespace PkCnicValidator\Tests;

use PHPUnit\Framework\TestCase;
use PkCnicValidator\CnicValidator;

/**
 * Integration tests for CnicValidator class
 */
class IntegrationTest extends TestCase
{
    private CnicValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CnicValidator();
    }

    /**
     * @test
     */
    public function it_handles_complete_workflow_with_valid_cnic(): void
    {
        $cnicWithDashes = '12345-1234567-1';
        $cnicWithoutDashes = '1234512345671';

        // Test validation
        $this->assertTrue($this->validator->isValid($cnicWithDashes));
        $this->assertTrue($this->validator->isValid($cnicWithoutDashes));

        // Test formatting
        $this->assertEquals($cnicWithDashes, $this->validator->formatWithDashes($cnicWithoutDashes));
        $this->assertEquals($cnicWithoutDashes, $this->validator->formatWithoutDashes($cnicWithDashes));

        // Test information extraction
        $info = $this->validator->extractInfo($cnicWithDashes);
        $this->assertIsArray($info);
        $this->assertEquals($cnicWithDashes, $info['cnic_with_dashes']);
        $this->assertEquals($cnicWithoutDashes, $info['cnic_without_dashes']);
    }

    /**
     * @test
     */
    public function it_handles_edge_cases_in_workflow(): void
    {
        // Test with minimum valid values
        $minCnic = '11111-1111111-1';
        $this->assertTrue($this->validator->isValid($minCnic));
        $info = $this->validator->extractInfo($minCnic);
        $this->assertNotNull($info);
        $this->assertEquals('1', $info['province_code']);
        $this->assertEquals('11', $info['district_code']);
        $this->assertEquals('111', $info['family_number']);

        // Test with maximum valid values
        $maxCnic = '99999-9999999-9';
        $this->assertTrue($this->validator->isValid($maxCnic));
        $info = $this->validator->extractInfo($maxCnic);
        $this->assertNotNull($info);
        $this->assertEquals('9', $info['province_code']);
        $this->assertEquals('99', $info['district_code']);
        $this->assertEquals('999', $info['family_number']);
    }

    /**
     * @test
     */
    public function it_handles_whitespace_in_complete_workflow(): void
    {
        $cnicWithSpaces = '  12345-1234567-1  ';
        $expectedWithDashes = '12345-1234567-1';
        $expectedWithoutDashes = '1234512345671';

        // Test validation with spaces
        $this->assertTrue($this->validator->isValid($cnicWithSpaces));

        // Test formatting with spaces
        $this->assertEquals($expectedWithDashes, $this->validator->formatWithDashes($cnicWithSpaces));
        $this->assertEquals($expectedWithoutDashes, $this->validator->formatWithoutDashes($cnicWithSpaces));

        // Test information extraction with spaces
        $info = $this->validator->extractInfo($cnicWithSpaces);
        $this->assertNotNull($info);
        $this->assertEquals($expectedWithDashes, $info['cnic_with_dashes']);
        $this->assertEquals($expectedWithoutDashes, $info['cnic_without_dashes']);
    }

    /**
     * @test
     */
    public function it_handles_invalid_inputs_gracefully(): void
    {
        $invalidInputs = [
            '',
            '   ',
            'invalid',
            '12345',
            '12345-1234567',
            '12345-1234567-12',
            '1234a-1234567-1',
            '02345-1234567-1', // Invalid province code
            '10345-1234567-1', // Invalid district code
        ];

        foreach ($invalidInputs as $input) {
            $this->assertFalse($this->validator->isValid($input));
            $this->assertNull($this->validator->formatWithDashes($input));
            $this->assertNull($this->validator->formatWithoutDashes($input));
            $this->assertNull($this->validator->extractInfo($input));
        }
    }

    /**
     * @test
     */
    public function it_maintains_consistency_across_methods(): void
    {
        $cnicWithDashes = '12345-1234567-1';
        $cnicWithoutDashes = '1234512345671';

        // Test that formatting methods are consistent
        $formattedWithDashes = $this->validator->formatWithDashes($cnicWithoutDashes);
        $formattedWithoutDashes = $this->validator->formatWithoutDashes($cnicWithDashes);

        $this->assertEquals($cnicWithDashes, $formattedWithDashes);
        $this->assertEquals($cnicWithoutDashes, $formattedWithoutDashes);

        // Test that information extraction is consistent
        $infoFromWithDashes = $this->validator->extractInfo($cnicWithDashes);
        $infoFromWithoutDashes = $this->validator->extractInfo($cnicWithoutDashes);

        $this->assertNotNull($infoFromWithDashes);
        $this->assertNotNull($infoFromWithoutDashes);

        // The extracted info should be the same except for the 'cnic' field which preserves original format
        $this->assertEquals($infoFromWithDashes['cnic_with_dashes'], $infoFromWithoutDashes['cnic_with_dashes']);
        $this->assertEquals($infoFromWithDashes['cnic_without_dashes'], $infoFromWithoutDashes['cnic_without_dashes']);
        $this->assertEquals($infoFromWithDashes['province_code'], $infoFromWithoutDashes['province_code']);
        $this->assertEquals($infoFromWithDashes['district_code'], $infoFromWithoutDashes['district_code']);
        $this->assertEquals($infoFromWithDashes['family_number'], $infoFromWithoutDashes['family_number']);
        $this->assertEquals($infoFromWithDashes['serial_number'], $infoFromWithoutDashes['serial_number']);
        $this->assertEquals($infoFromWithDashes['check_digit'], $infoFromWithoutDashes['check_digit']);
    }
}
