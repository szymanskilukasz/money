<?php
/*
 * This file is part of the Money package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\Money;

class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SebastianBergmann\Money\Currency::__construct
     */
    public function testCanBeConstructedFromUppercaseCurrencyCode()
    {
        $c = new Currency('EUR');

        $this->assertInstanceOf(Currency::class, $c);

        return $c;
    }

    /**
     * @covers \SebastianBergmann\Money\Currency::__construct
     */
    public function testCanBeConstructedFromLowercaseCurrencyCode()
    {
        $c = new Currency('eur');

        $this->assertInstanceOf(Currency::class, $c);
    }

    /**
     * @backupStaticAttributes enabled
     * @covers \SebastianBergmann\Money\Currency::addCurrency
     * @uses   \SebastianBergmann\Money\Currency::__construct
     */
    public function testCustomCurrencyCanBeRegistered()
    {
        Currency::addCurrency(
            'BTC',
            'Bitcoin',
            999,
            4,
            1000
        );

        $this->assertInstanceOf(Currency::class, new Currency('BTC'));
    }

    /**
     * @covers \SebastianBergmann\Money\Currency::getCurrencies
     */
    public function testRegisteredCurrenciesCanBeAccessed()
    {
        $currencies = Currency::getCurrencies();

        $this->assertInternalType('array', $currencies);
        $this->assertArrayHasKey('EUR', $currencies);
        $this->assertInternalType('array', $currencies['EUR']);
        $this->assertArrayHasKey('display_name', $currencies['EUR']);
        $this->assertArrayHasKey('numeric_code', $currencies['EUR']);
        $this->assertArrayHasKey('default_fraction_digits', $currencies['EUR']);
        $this->assertArrayHasKey('sub_unit', $currencies['EUR']);
    }

    /**
     * @covers  \SebastianBergmann\Money\Currency::__toString
     * @depends testCanBeConstructedFromUppercaseCurrencyCode
     */
    public function testCanBeCastToString(Currency $c)
    {
        $this->assertEquals('EUR', (string) $c);
    }

    /**
     * @covers  \SebastianBergmann\Money\Currency::getCurrencyCode
     * @depends testCanBeConstructedFromUppercaseCurrencyCode
     */
    public function testCurrencyCodeCanBeRetrieved(Currency $c)
    {
        $this->assertEquals('EUR', $c->getCurrencyCode());
    }

    /**
     * @covers  \SebastianBergmann\Money\Currency::getDefaultFractionDigits
     * @depends testCanBeConstructedFromUppercaseCurrencyCode
     */
    public function testDefaultFractionDigitsCanBeRetrieved(Currency $c)
    {
        $this->assertEquals(2, $c->getDefaultFractionDigits());
    }

    /**
     * @covers  \SebastianBergmann\Money\Currency::getDisplayName
     * @depends testCanBeConstructedFromUppercaseCurrencyCode
     */
    public function testDisplayNameCanBeRetrieved(Currency $c)
    {
        $this->assertEquals('Euro', $c->getDisplayName());
    }

    /**
     * @covers  \SebastianBergmann\Money\Currency::getNumericCode
     * @depends testCanBeConstructedFromUppercaseCurrencyCode
     */
    public function testNumericCodeCanBeRetrieved(Currency $c)
    {
        $this->assertEquals(978, $c->getNumericCode());
    }

    /**
     * @covers  \SebastianBergmann\Money\Currency::getSubUnit
     * @depends testCanBeConstructedFromUppercaseCurrencyCode
     */
    public function testSubUnitCanBeRetrieved(Currency $c)
    {
        $this->assertEquals(100, $c->getSubUnit());
    }

    /**
     * @covers \SebastianBergmann\Money\Currency::__construct
     */
    public function testCannotBeCreatedFromUnknownCurrencyCode()
    {
        $this->expectException(InvalidArgumentException::class);

        new Currency(null);
    }
}
