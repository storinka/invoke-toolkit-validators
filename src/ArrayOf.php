<?php

namespace Invoke\Toolkit\Validators;

use Attribute;
use Invoke\Exceptions\InvalidTypeException;
use Invoke\Exceptions\ValidationFailedException;
use Invoke\Piping;
use Invoke\Stop;
use Invoke\Type;
use Invoke\Types\ArrayType;
use Invoke\Utils\Utils;
use Invoke\Validator;

/**
 * Array item type validator.
 *
 * Can be used as type to validate nested arrays.
 */
#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
class ArrayOf extends ArrayType implements Validator, Type, HasDynamicName, HasUsedTypes
{
    public Type $itemPipe;

    public function __construct(Type|string|array $itemPipe)
    {
        $this->itemPipe = Utils::toType($itemPipe);
    }

    public function pass(mixed $value): mixed
    {
        $value = parent::pass($value);

        if ($value instanceof Stop) {
            return $value;
        }

        // todo: this is broken, fix it

        foreach ($value as $index => $item) {
            try {
                $value[$index] = Piping::run($this->itemPipe, $item);
            } catch (InvalidTypeException|ValidationFailedException) {
                // ignore
            }
        }

        return $value;
    }

    public function invoke_getUsedTypes(): array
    {
        return [$this->itemPipe];
    }

    public function invoke_getDynamicName(): string
    {
        $arrayTypeName = static::invoke_getTypeName();
        $itemPipeName = Utils::getPipeTypeName($this->itemPipe);

        return "{$arrayTypeName}<{$itemPipeName}>";
    }

    public function invoke_getValidatorData(): array
    {
        return [
            "itemType" => Utils::getUniqueTypeName($this->itemPipe),
        ];
    }
}
