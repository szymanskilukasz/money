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

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SebastianBergmann\Money\Money::__construct
     * @covers \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeConstructedFromIntegerValueAndCurrencyObject()
    {
        $m = new Money(0, new Currency('EUR'));

        $this->assertInstanceOf(Money::class, $m);

        return $m;
    }

    /**
     * @covers \SebastianBergmann\Money\Money::__construct
     * @covers \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeConstructedFromIntegerValueAndCurrencyString()
    {
        $m = new Money(0, 'EUR');

        $this->assertInstanceOf(Money::class, $m);

        return $m;
    }

    /**
     * @covers \SebastianBergmann\Money\Money::fromString
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeConstructedFromStringValueAndCurrencyObject()
    {
        $this->assertEquals(
            new Money(1234, new Currency('EUR')),
            Money::fromString('12.34', new Currency('EUR'))
        );
    }

    /**
     * @covers \SebastianBergmann\Money\Money::fromString
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeConstructedFromStringValueAndCurrencyString()
    {
        $this->assertEquals(
            new Money(1234, new Currency('EUR')),
            Money::fromString('12.34', 'EUR')
        );
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::getAmount
     * @depends testCanBeConstructedFromIntegerValueAndCurrencyObject
     */
    public function testAmountCanBeRetrieved(Money $m)
    {
        $this->assertEquals(0, $m->getAmount());
    }

    /**
     * @covers \SebastianBergmann\Money\Money::getConvertedAmount
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testConvertedAmountCanBeRetrieved()
    {
        $m = new Money(1234, 'EUR');
        $this->assertSame(12.34, $m->getConvertedAmount());
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::getCurrency
     * @uses    \SebastianBergmann\Money\Currency
     * @depends testCanBeConstructedFromIntegerValueAndCurrencyObject
     */
    public function testCurrencyCanBeRetrieved(Money $m)
    {
        $this->assertEquals(new Currency('EUR'), $m->getCurrency());
    }

    /**
     * @covers \SebastianBergmann\Money\Money::add
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Money::assertIsInteger
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testAnotherMoneyObjectWithSameCurrencyCanBeAdded()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $c = $a->add($b);

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
        $this->assertEquals(3, $c->getAmount());
    }

    /**
     * @covers \SebastianBergmann\Money\Money::subtract
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Money::assertIsInteger
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testAnotherMoneyObjectWithSameCurrencyCanBeSubtracted()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $c = $b->subtract($a);

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
        $this->assertEquals(1, $c->getAmount());
    }

    /**
     * @covers \SebastianBergmann\Money\Money::negate
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeNegated()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = $a->negate();

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(-1, $b->getAmount());
    }

    /**
     * @covers \SebastianBergmann\Money\Money::multiply
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeMultipliedByAFactor()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = $a->multiply(2);

        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
    }

    /**
     * @covers \SebastianBergmann\Money\Money::allocateToTargets
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::multiply
     * @uses   \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeAllocatedToNumberOfTargets()
    {
        $a = new Money(99, new Currency('EUR'));
        $r = $a->allocateToTargets(10);

        $this->assertEquals(
            [
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(10, new Currency('EUR')),
                new Money(9, new Currency('EUR'))
            ],
            $r
        );
    }

    /**
     * @covers \SebastianBergmann\Money\Money::allocateToTargets
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::multiply
     * @uses   \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testNegativeAmountCanBeAllocatedToNumberOfTargets()
    {
        $a = new Money(-99, new Currency('EUR'));
        $r = $a->allocateToTargets(10);

        $this->assertEquals(
            [
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-10, new Currency('EUR')),
                new Money(-9, new Currency('EUR'))
            ],
            $r
        );
    }

    /**
     * @covers \SebastianBergmann\Money\Money::extractPercentage
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Money::subtract
     * @uses   \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::assertIsInteger
     * @uses   \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Money::newMoney
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testPercentageCanBeExtracted()
    {
        $original = new Money(10000, new Currency('EUR'));
        $extract  = $original->extractPercentage(21);

        $this->assertEquals(new Money(8264, new Currency('EUR')), $extract['subtotal']);
        $this->assertEquals(new Money(1736, new Currency('EUR')), $extract['percentage']);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::allocateByRatios
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Money::multiply
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeAllocatedByRatios()
    {
        $a = new Money(5, new Currency('EUR'));
        $r = $a->allocateByRatios([3, 7]);

        $this->assertEquals(
            [
                new Money(2, new Currency('EUR')),
                new Money(3, new Currency('EUR'))
            ],
            $r
        );
    }

    /**
     * @covers \SebastianBergmann\Money\Money::allocateByRatios
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Money::multiply
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testNegativeAmountCanBeAllocatedByRatios()
    {
        $a = new Money(-5, new Currency('EUR'));
        $r = $a->allocateByRatios([3, 7]);

        $this->assertEquals(
            [
                new Money(-2, new Currency('EUR')),
                new Money(-3, new Currency('EUR'))
            ],
            $r
        );
    }

    /**
     * @covers \SebastianBergmann\Money\Money::compareTo
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));

        $this->assertEquals(-1, $a->compareTo($b));
        $this->assertEquals(1, $b->compareTo($a));
        $this->assertEquals(0, $a->compareTo($a));
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::greaterThan
     * @covers  \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses    \SebastianBergmann\Money\Money::__construct
     * @uses    \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses    \SebastianBergmann\Money\Money::compareTo
     * @uses    \SebastianBergmann\Money\Money::getAmount
     * @uses    \SebastianBergmann\Money\Money::getCurrency
     * @uses    \SebastianBergmann\Money\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency2()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));

        $this->assertFalse($a->greaterThan($b));
        $this->assertTrue($b->greaterThan($a));
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::lessThan
     * @covers  \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses    \SebastianBergmann\Money\Money::__construct
     * @uses    \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses    \SebastianBergmann\Money\Money::compareTo
     * @uses    \SebastianBergmann\Money\Money::getAmount
     * @uses    \SebastianBergmann\Money\Money::getCurrency
     * @uses    \SebastianBergmann\Money\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency3()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));

        $this->assertFalse($b->lessThan($a));
        $this->assertTrue($a->lessThan($b));
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::equals
     * @covers  \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses    \SebastianBergmann\Money\Money::__construct
     * @uses    \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses    \SebastianBergmann\Money\Money::compareTo
     * @uses    \SebastianBergmann\Money\Money::getAmount
     * @uses    \SebastianBergmann\Money\Money::getCurrency
     * @uses    \SebastianBergmann\Money\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency4()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(1, new Currency('EUR'));

        $this->assertEquals(0, $a->compareTo($b));
        $this->assertEquals(0, $b->compareTo($a));
        $this->assertTrue($a->equals($b));
        $this->assertTrue($b->equals($a));
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::greaterThanOrEqual
     * @covers  \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses    \SebastianBergmann\Money\Money::__construct
     * @uses    \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses    \SebastianBergmann\Money\Money::greaterThan
     * @uses    \SebastianBergmann\Money\Money::equals
     * @uses    \SebastianBergmann\Money\Money::compareTo
     * @uses    \SebastianBergmann\Money\Money::getAmount
     * @uses    \SebastianBergmann\Money\Money::getCurrency
     * @uses    \SebastianBergmann\Money\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency5()
    {
        $a = new Money(2, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $c = new Money(1, new Currency('EUR'));

        $this->assertTrue($a->greaterThanOrEqual($a));
        $this->assertTrue($a->greaterThanOrEqual($b));
        $this->assertTrue($a->greaterThanOrEqual($c));
        $this->assertFalse($c->greaterThanOrEqual($a));
    }

    /**
     * @covers  \SebastianBergmann\Money\Money::lessThanOrEqual
     * @covers  \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses    \SebastianBergmann\Money\Money::__construct
     * @uses    \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses    \SebastianBergmann\Money\Money::lessThan
     * @uses    \SebastianBergmann\Money\Money::equals
     * @uses    \SebastianBergmann\Money\Money::compareTo
     * @uses    \SebastianBergmann\Money\Money::getAmount
     * @uses    \SebastianBergmann\Money\Money::getCurrency
     * @uses    \SebastianBergmann\Money\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency6()
    {
        $a = new Money(1, new Currency('EUR'));
        $b = new Money(1, new Currency('EUR'));
        $c = new Money(2, new Currency('EUR'));

        $this->assertTrue($a->lessThanOrEqual($a));
        $this->assertTrue($a->lessThanOrEqual($b));
        $this->assertTrue($a->lessThanOrEqual($c));
        $this->assertFalse($c->lessThanOrEqual($a));
    }

    /**
     * @covers \SebastianBergmann\Money\Money::jsonSerialize
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Currency
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     */
    public function testCanBeSerializedToJson()
    {
        $this->assertEquals(
            '{"amount":1,"currency":"EUR"}',
            json_encode(new EUR(1))
        );
    }

    /**
     * @covers \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeCreatedFromInvalidAmount()
    {
        $this->expectException(InvalidArgumentException::class);

        new Money(null, new Currency('EUR'));
    }

    /**
     * @covers \SebastianBergmann\Money\Money::__construct
     * @covers \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeCreatedFromInvalidCurrency()
    {
        $this->expectException(InvalidArgumentException::class);

        new Money(0, null);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::fromString
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeCreatedFromInvalidString()
    {
        $this->expectException(InvalidArgumentException::class);

        Money::fromString(1234, new Currency('EUR'));
    }

    /**
     * @covers \SebastianBergmann\Money\Money::add
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @covers \SebastianBergmann\Money\Money::assertIsInteger
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotPerformOverflowingAddition()
    {
        $this->expectException(OverflowException::class);

        $a = new Money(PHP_INT_MAX, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $a->add($b);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::assertInsideIntegerBounds
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::multiply
     * @uses   \SebastianBergmann\Money\Money::castToInt
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeCreatedForAmountOutsideOfIntegerBounds()
    {
        $this->expectException(OverflowException::class);

        $a = new Money(PHP_INT_MAX, new Currency('EUR'));
        $a->multiply(2);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::add
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotAddMoneyObjectWithDifferentCurrency()
    {
        $this->expectException(CurrencyMismatchException::class);

        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('USD'));

        $a->add($b);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::subtract
     * @covers \SebastianBergmann\Money\Money::newMoney
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @covers \SebastianBergmann\Money\Money::assertIsInteger
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotPerformOverflowingSubtraction()
    {
        $this->expectException(OverflowException::class);

        $a = new Money(-PHP_INT_MAX, new Currency('EUR'));
        $b = new Money(2, new Currency('EUR'));
        $a->subtract($b);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::subtract
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getAmount
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotSubtractMoneyObjectWithDifferentCurrency()
    {
        $this->expectException(CurrencyMismatchException::class);

        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('USD'));

        $b->subtract($a);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::multiply
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeMultipliedUsingInvalidRoundingMode()
    {
        $this->expectException(InvalidArgumentException::class);

        $a = new Money(1, new Currency('EUR'));
        $a->multiply(2, null);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::allocateToTargets
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeAllocatedToInvalidNumberOfTargets()
    {
        $this->expectException(InvalidArgumentException::class);

        $a = new Money(0, new Currency('EUR'));
        $a->allocateToTargets(null);
    }

    /**
     * @covers \SebastianBergmann\Money\Money::compareTo
     * @covers \SebastianBergmann\Money\Money::assertSameCurrency
     * @uses   \SebastianBergmann\Money\Money::__construct
     * @uses   \SebastianBergmann\Money\Money::handleCurrencyArgument
     * @uses   \SebastianBergmann\Money\Money::getCurrency
     * @uses   \SebastianBergmann\Money\Currency
     */
    public function testCannotBeComparedToMoneyObjectWithDifferentCurrency()
    {
        $this->expectException(InvalidArgumentException::class);

        $a = new Money(1, new Currency('EUR'));
        $b = new Money(2, new Currency('USD'));

        $a->compareTo($b);
    }
}
