<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineInheritAnnotations\Exception;

use InvalidArgumentException;
use Throwable;
use function get_class;
use function gettype;
use function is_object;

class UnexpectedTypeException extends InvalidArgumentException
{
    public function __construct($value, string $expectedType, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Expected argument of type "%s", "%s" given',
                $expectedType,
                is_object($value) ? get_class($value) : gettype($value)
            ),
            $code,
            $previous
        );
    }
}
