<?php

namespace Headers\Response;

use Headers\Response\Exceptions\IntervalMethodNotFoundException;
use InvalidArgumentException;

/**
 * @method self seconds(int $value)
 * @method self minutes(int $value)
 * @method self hours(int $value)
 * @method self days(int $value)
 * @method self weeks(int $value)
 * @method self months(int $value)
 * @method self years(int $value)
 */
class Expires
{
    protected array $intervalStrings = [];

    protected const DATETIME_LIST = [
        'seconds',
        'minutes',
        'hours',
        'days',
        'weeks',
        'months',
        'years'
    ];


    /**
     * Obs: 
     * $method = o nome do método invocado ex: days
     * $param[0] = o primeiro parametro capturado pelo __call
     * 
     * Ex: $expires->days(10)
     * $method = 'days'
     * $params[0] = 10
     */
    public function __call(string $method, array $params): mixed
    {
        if (false === in_array($method, self::DATETIME_LIST)) {
            throw new IntervalMethodNotFoundException("Method '$method' does not exists");
        }

        if (false === is_int($params[0])) {
            throw new InvalidArgumentException('Parameter is not int');
        }

        if ($params[0] < 0) {
            throw new InvalidArgumentException('Invalid negative parameter');
        }

        $this->intervalStrings[] = $params[0] . ' ' . $method;
        return $this;
    }

    public function get(): string
    {
        return implode(' + ', $this->intervalStrings);
    }
}