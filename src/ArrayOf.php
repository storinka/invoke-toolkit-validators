<?php

namespace Invoke\Toolkit\Validators;

use Attribute;
use Invoke\Exceptions\InvalidParameterTypeException;
use Invoke\Exceptions\InvalidTypeException;
use Invoke\Piping;
use Invoke\Stop;
use Invoke\Support\HasDynamicTypeName;
use Invoke\Support\HasUsedTypes;
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
class ArrayOf extends ArrayType implements Validator, Type, HasDynamicTypeName, HasUsedTypes
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

        $newValue = [];

        foreach ($value as $index => $item) {
            try {
                $newValue[$index] = Piping::run($this->itemPipe, $item);
            } catch (InvalidParameterTypeException $exception) {
                throw new InvalidParameterTypeException("{$index}->{$exception->path}", $exception->expectedType, $exception->valueTypeName);
            } catch (InvalidTypeException $exception) {
                throw new InvalidParameterTypeException($index, $exception->expectedType, $exception->valueTypeName);
            }
        }

        return $newValue;
    }

    public function invoke_getUsedTypes(): array
    {
        return [$this->itemPipe];
    }

    public function invoke_getDynamicTypeName(): string
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
