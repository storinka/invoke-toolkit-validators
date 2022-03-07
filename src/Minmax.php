<?php

namespace Invoke\Toolkit\Validators;

use Attribute;
use Invoke\Validator;

#[Attribute]
class Minmax implements Validator
{
    public function __construct(public ?int $min = null,
                                public ?int $max = null)
    {
    }

    public function pass(mixed $value): mixed
    {
        if (isset($this->min)) {
            if ($value < $this->min) {
                throw new ValidationException("Min value \"{$this->min}\", got \"{$value}\".");
            }
        }

        if (isset($this->max)) {
            if ($value > $this->max) {
                throw new ValidationException("Max value \"{$this->max}\", got \"{$value}\".");
            }
        }

        return $value;
    }
}
