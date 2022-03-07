<?php

namespace Invoke\Toolkit\Validators;

use Attribute;
use Invoke\Validator;

#[Attribute]
class Regex implements Validator
{
    public function __construct(public string $pattern)
    {
    }

    public function pass(mixed $value): mixed
    {
        if (!preg_match($this->pattern, $value)) {
            throw new ValidationException("Expected value that matches \"{$this->pattern}\", got \"{$value}\".");
        }

        return $value;
    }
}
