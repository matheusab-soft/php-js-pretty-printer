# php-js-pretty-printer

This project allows you to "prettily" generate JS syntax (separators and line breaks included) through PHP classes.

It differs from [json_encode](https://www.php.net/manual/en/function.json-encode.php) due to its capability of printing
raw js as well as strings.

# Installation
Include it via Composer:
```
composer require matheusab/php-js-pretty-printer
```

# Usage

Let's suppose you need to print a javascript object that contains strings, functions, arrays and objects as values.
You can do it like so:

```php
use MAB\JS\JS;

$js = JS::object([
    'string'  => 'myStringValue',
    'rawJS'   => JS::raw("() => {}"),
    'array1'  => [1, 2, 3],
    'array2'  => JS::array(1, 2, 3), // same as array1
    'array3'  => JS::array(1, 2, 3)->breakLine(),
    'object1' => [
        'a' => 1,
        'b' => 2
    ],
    'object2' => JS::object([ //same as object1
        'a' => 1,
        'b' => 2
    ]),
]);

echo $js;
```

Result:

```json
{
    "myString": "myStringValue",
    "myCallback": () => {},
    "array1": [1, 2, 3],
    "array2": [1, 2, 3],
    "array3": [
        1,
        2,
        3
    ],
    "object1": {
        "a": 1,
        "b": 2
    },
    "object2": {
        "a": 1,
        "b": 2
    }
}
```

## API

Through the main class, `JS`, you have access to the following creational methods:

| Method   | Arguments                      | Return type | 
|----------|--------------------------------|-------------|
| `format` | `array $lines, int $level = 0` | string      |
| `array`  | `...$items`                    | JSArray     |
| `object` | `?array $object = []`          | JSObject    |
| `raw`    | `string $js`                   | Raw         |



## Formatting

Call `->format()` on a `JSArray` or `JSObject` or use `JS::format($lines)`, like so:

```php
<?php

use MAB\JS\JS;

echo JS::object($map)->format();
echo JS::array(...$values)->format();
echo JS::format($lines);
```





# Features

## Arrays and objects

### ArrayAccess
JSArray and JSObject classes implement `ArrayAccess`, allowing you to push or access values using PHP's [array access syntax](https://www.php.net/manual/en/language.types.array.php#language.types.array.syntax.accessing):
 

Object access example:
```php

$object = JS::object();
$object['a'] = 1;

// equivalent to

$object = JS::object(['a' => 1]); 
```

Array access example:
```php
$array = JS::array(1);

// equivalent to

$array = JS::array();
$array[] = 1;
```
### Casting to string: 

Both classes also implement `__toString()`, which calls the `format` method.

```php
echo JS::object();

// is equivalent to

echo JS::object()->format();
```

## Nesting and indentation
You can use arrays, strings, and Raw, JSObject and JSArray objects as values of instances of `JSArray` and `JSObject`.

Nesting level is considered for indentation so that generated javascript is always properly (prettily) generated.

Example:
```php
echo JS::object([
    'a' => [
        'a_a' => 1,
        'a_b' => 2,
    ],
    'b' => JS::object([
        'b_a' => 1,
        'b_b' => 2
    ])
]);
```
outputs:
```js
{
    "a": {
    "a_a": 1,
        "a_b": 2
    },
    "b": {
    "b_a": 1,
        "b_b": 2
    }
}
```

### Initial indentation level

You can change it in arrays and objects by calling the `indent($level = 1)` method.

