<?php

namespace Invoke\Toolkit\Validators;

use Attribute;
use Invoke\Stop;
use Invoke\Validator;

/**
 * String length validator.
 */
#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
class Length implements Validator
{
    public ?int $min;
    public ?int $max;

    public function __construct(?int $min = null, ?int $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function pass(mixed $value): mixed
    {
        if ($value instanceof Stop) {
            return $value;
        }

        $length = mb_strlen($value);

        if (!is_null($this->min)) {
            if ($length < $this->min) {
                throw new ValidationException(
                    "Min length {$this->min}, got {$length}."
                );
            }
        }

        if (!is_null($this->max)) {
            if ($length > $this->max) {
                throw new ValidationException(
                    "Max length {$this->max}, got {$length}."
                );
            }
        }

        return $value;
    }
}
