# Invoke Toolkit Validators

Validators collection for Invoke Toolkit.

## Installation

```shell
composer require storinka/invoke-toolkit-validators
```

## Available validators

### `#[ArrayOf]`

Validate array items type.

#### Examples

```php
// ensures that array contains only strings
#[ArrayOf("string")]

// ensures that array contains only integers
#[ArrayOf("int")]

// ensures that array contains only items with type SomeData
#[ArrayOf(SomeData::class)]

// ensures that array contains only items with type string or int
#[ArrayOf(["string", "int"])]

// ensures that array contains only items with type string or int or SomeData
#[ArrayOf(["string", "int", SomeData::class])]
```

### `#[Length]`

Validate length of the string.

#### Examples

```php
// ensures that string length is min 3 and max 16
#[Length(3, 16)]

// ensures that string length is min 3
#[Length(3)]

// ensures that string length is max 16
#[Length(max: 16)]
```