<?php

declare(strict_types=1);

namespace PkCnicValidator\Tests;

use PHPUnit\Framework\TestCase;
use PkCnicValidator\CnicValidator;

/**
 * Test cases for CnicValidator class
 */
class CnicValidatorTest extends TestCase
{
    private CnicValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CnicValidator();
    }

    /**
     * @test
     * @dataProvider validCnicWithDashesProvider
     */
    public function it_validates_cnic_with_dashes_correctly(string $cnic): void
    {
        $this->assertTrue($this->validator->isValid($cnic));
        $this->assertTrue($this->validator->isValidWithDashes($cnic));
        $this->assertFalse($this->validator->isValidWithoutDashes($cnic));
    }

    /**
     * @test
     * @dataProvider validCnicWithoutDashesProvider
     */
    public function it_validates_cnic_without_dashes_correctly(string $cnic): void
    {
        $this->assertTrue($this->validator->isValid($cnic));
        $this->assertFalse($this->validator->isValidWithDashes($cnic));
        $this->assertTrue($this->validator->isValidWithoutDashes($cnic));
    }

    /**
     * @test
     * @dataProvider invalidCnicProvider
     */
    public function it_rejects_invalid_cnic_numbers(string $cnic): void
    {
        $this->assertFalse($this->validator->isValid($cnic));
        $this->assertFalse($this->validator->isValidWithDashes($cnic));
        $this->assertFalse($this->validator->isValidWithoutDashes($cnic));
    }

    /**
     * @test
     */
    public function it_rejects_empty_strings(): void
    {
        $this->assertFalse($this->validator->isValid(''));
        $this->assertFalse($this->validator->isValid('   '));
        $this->assertFalse($this->validator->isValid("\t\n"));
    }

    /**
     * @test
     */
    public function it_formats_cnic_with_dashes_correctly(): void
    {
        $cnicWithoutDashes = '1234512345671';
        $expectedWithDashes = '12345-1234567-1';

        $this->assertEquals($expectedWithDashes, $this->validator->formatWithDashes($cnicWithoutDashes));
        $this->assertEquals($expectedWithDashes, $this->validator->formatWithDashes($expectedWithDashes));
    }

    /**
     * @test
     */
    public function it_formats_cnic_without_dashes_correctly(): void
    {
        $cnicWithDashes = '12345-1234567-1';
        $expectedWithoutDashes = '1234512345671';

        $this->assertEquals($expectedWithoutDashes, $this->validator->formatWithoutDashes($cnicWithDashes));
        $this->assertEquals($expectedWithoutDashes, $this->validator->formatWithoutDashes($expectedWithoutDashes));
    }

    /**
     * @test
     */
    public function it_returns_null_for_invalid_formatting(): void
    {
        $this->assertNull($this->validator->formatWithDashes('invalid'));
        $this->assertNull($this->validator->formatWithoutDashes('invalid'));
        $this->assertNull($this->validator->formatWithDashes(''));
        $this->assertNull($this->validator->formatWithoutDashes(''));
    }

    /**
     * @test
     */
    public function it_extracts_cnic_information_correctly(): void
    {
        $cnicWithDashes = '12345-1234567-1';
        $expectedInfo = [
            'cnic' => '12345-1234567-1',
            'cnic_with_dashes' => '12345-1234567-1',
            'cnic_without_dashes' => '1234512345671',
            'province_code' => '1',
            'district_code' => '12',
            'family_number' => '345',
            'serial_number' => '1234567',
            'check_digit' => '1',
        ];

        $result = $this->validator->extractInfo($cnicWithDashes);
        $this->assertNotNull($result);
        $this->assertEquals($expectedInfo, $result);
    }

    /**
     * @test
     */
    public function it_extracts_cnic_information_from_numeric_format(): void
    {
        $cnicWithoutDashes = '1234512345671';
        $expectedInfo = [
            'cnic' => '1234512345671',
            'cnic_with_dashes' => '12345-1234567-1',
            'cnic_without_dashes' => '1234512345671',
            'province_code' => '1',
            'district_code' => '12',
            'family_number' => '345',
            'serial_number' => '1234567',
            'check_digit' => '1',
        ];

        $result = $this->validator->extractInfo($cnicWithoutDashes);
        $this->assertNotNull($result);
        $this->assertEquals($expectedInfo, $result);
    }

    /**
     * @test
     */
    public function it_returns_null_for_invalid_cnic_extraction(): void
    {
        $this->assertNull($this->validator->extractInfo('invalid'));
        $this->assertNull($this->validator->extractInfo(''));
        $this->assertNull($this->validator->extractInfo('12345-1234567'));
    }

    /**
     * @test
     */
    public function it_handles_whitespace_correctly(): void
    {
        $cnicWithSpaces = '  12345-1234567-1  ';
        $cnicWithoutSpaces = '12345-1234567-1';

        $this->assertTrue($this->validator->isValid($cnicWithSpaces));
        $this->assertEquals($cnicWithoutSpaces, $this->validator->formatWithDashes($cnicWithSpaces));
        $this->assertEquals('1234512345671', $this->validator->formatWithoutDashes($cnicWithSpaces));
    }

    /**
     * Data providers
     */
    public function validCnicWithDashesProvider(): array
    {
        return [
            'standard format' => ['12345-1234567-1'],
            'another valid format' => ['34567-8901234-5'],
            'edge case 1' => ['11111-1111111-1'],
            'edge case 2' => ['99999-9999999-9'],
        ];
    }

    public function validCnicWithoutDashesProvider(): array
    {
        return [
            'standard format' => ['1234512345671'],
            'another valid format' => ['3456789012345'],
            'edge case 1' => ['1111111111111'],
            'edge case 2' => ['9999999999999'],
        ];
    }

    public function invalidCnicProvider(): array
    {
        return [
            'too short' => ['12345-123456-1'],
            'too long' => ['12345-12345678-1'],
            'wrong format' => ['12345-1234567-12'],
            'non-numeric' => ['1234a-1234567-1'],
            'missing dash' => ['1234512345671-1'],
            'extra dash' => ['12345-12345-67-1'],
            'invalid province code' => ['02345-1234567-1'],
            'invalid district code' => ['10345-1234567-1'],
            'zero family number' => ['12000-1234567-1'],
            'zero serial number' => ['12345-0000000-1'],
            'random string' => ['abcdefghijklm'],
            'special characters' => ['12345@1234567#1'],
        ];
    }
}
