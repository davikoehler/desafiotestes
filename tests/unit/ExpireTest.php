<?php

use Headers\Response\Exceptions\IntervalMethodNotFoundException;
use Headers\Response\Expires;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(Expires::class)]
final class ExpireTest extends PHPUnit\Framework\TestCase
{
    protected Expires $expires;

    protected function setUp(): void
    {
        $this->expires = new Expires();
    }

    public static function datesDataProvider(): array
    {
        return [
            ['seconds', 30, '30 seconds'],
            ['minutes', 2, '2 minutes'],
            ['hours', 3, '3 hours'],
            ['days', 10, '10 days'],
            ['weeks', 11, '11 weeks'],
            ['months', 2, '2 months'],
            ['years', 1, '1 years'],
        ];
    }

    #[DataProvider('datesDataProvider')]
    public function testExpiresComponentDisplayExpireString($method, $value, $result): void
    {
        $this->expires->$method($value);
        $this->assertEquals($result, $this->expires->get());
    }

    public static function datesMultipleDataProvider(): array
    {
        return [
            [ ['seconds', 'minutes'], 2, '2 seconds + 2 minutes' ],
            [ ['days', 'months'], 2, '2 days + 2 months' ],
            [ ['weeks', 'months'], 2, '2 weeks + 2 months' ],
            [ ['months', 'years'], 2, '2 months + 2 years' ],
            [ ['years', 'months'], 2, '2 years + 2 months' ],
            [ ['months', 'weeks'], 2, '2 months + 2 weeks' ],
            [ ['weeks', 'minutes'], 2, '2 weeks + 2 minutes' ],
            [ ['minutes', 'seconds'], 2, '2 minutes + 2 seconds' ],
            [ ['hours', 'seconds'], 2, '2 hours + 2 seconds' ],
            [ ['months', 'days', 'minutes'], 5, '5 months + 5 days + 5 minutes' ],
            [ ['days', 'days', 'days'], 8, '8 days + 8 days + 8 days' ]
        ];
    }

    #[DataProvider('datesMultipleDataProvider')]
    public function testExpiresComponentDisplayMultipleExpireString(array $methods, $value, $result): void
    {
        foreach ($methods as $method) {
            $this->expires->$method($value);
        }
        $this->assertEquals($result, $this->expires->get());
    }

    public static function datesMultipleValuesAndMethods(): array
    {
        return [
            [['seconds', 'minutes', 'hours'], [2, 30, 5], '2 seconds + 30 minutes + 5 hours'],
            [['hours', 'weeks', 'days'], [5, 8, 16], '5 hours + 8 weeks + 16 days'],
            [['weeks', 'years', 'months'], [2, 30, 5], '2 weeks + 30 years + 5 months'],
            [['seconds', 'seconds', 'hours', 'seconds'], [8, 2, 10, 3], '8 seconds + 2 seconds + 10 hours + 3 seconds'],
            [['days', 'days', 'minutes'], [332, 22, 90], '332 days + 22 days + 90 minutes'],
            [['days'], [0], '0 days'],
        ];
    }

    #[DataProvider('datesMultipleValuesAndMethods')]
    public function testExpiresComponentDisplayMultipleFieldAndExpireString(
        array $methods, 
        array $values, 
        string $result
    ): void {
        foreach ($methods as $method) {
            $this->expires->$method(array_shift($values));
        }
        $this->assertEquals($result, $this->expires->get());
    }

    public function testExpiresComponentShouldDisplayIntError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expires->days('2');
    }

    public function testExpiresComponentShouldThrowIntervalMethodNotFoundExceptionWhenMethodNotExists(): void
    {
        $this->expectException(IntervalMethodNotFoundException::class);
        $this->expires->abc();
    }

    public function testExpiresComponentShouldThrowInvalidArgumentExceptionWhenNegativeParameter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expires->days(-500);
    }
}