<?php

namespace Invoke\Toolkit\Validators;

use Attribute;
use Invoke\Validator;

#[Attribute]
class In implements Validator
{
    public function __construct(public array $values)
    {
    }

    public function pass(mixed $value): mixed
    {
        if (!in_array($value, $this->values)) {
            $itemsString = implode(", ", $this->values);

            throw new ValidationException("Expected value from \"{$itemsString}\", got \"{$value}\".");
        }

        return $value;
    }
}
