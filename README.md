
# PHP Liberator
A php library to liberate private/protected methods and properties for read/write/call access. Useful for testing purposes

---

## Installation
``
composer require-dev pialechini/liberator  
``
> Note
> 
>> You can install it as a required dependency and use it in production
>> but you **should not**! It is harmful to access  private/protected properties and methods out of the class.

---

## Usage
Imagine you have a class like this:
* Product
    * **private** `$name`
    * **private** `$price`
    * **protected** `$count`
    * **public function setPrice** (`$price`)
    * **public function getPrice** ()

Then you want to do your job with this class and make assertions
on the properties above.

### Steps:

1. Create new instance of `Liberator` and pass `$product` to its constructor.
2. Do anything that you want with `$product`.
3. Use `$liberator` in your assertions

Or you can swap second step with the first step. Therefore, It would be like this:
1. Do anything that you want with `$product`.
2. Create new instance of `Liberator` and pass `$product` to its constructor.
3. Use `$liberator` in your assertions
```php
// inside a test
// ===================================================
$product = new Product();
$liberator = new \Pialechini\Liberator\Liberator($product);

$this->assertEquals('Ball', $liberator->get('name'));

// it is possible to set private/protected properties
// without getters/setters (also outside tests scope)
// but it can be harmful. Be aware !
$liberator->set('name', 'Book');
$this->assertEquals('Book', $liberator->get('name'));
// ===================================================

```
Another method in Liberator is `call()` which allows you to call 
any class method in object context.

Finally, there is a `restore()` method in Liberator. It returns
The given object in the constructor as it was whenever you want.
(Think it as a backup)

Take a look at the code below:
```php
$product = new Product();
$product->setPrice(300);

// when you create a liberator, it would automatically
// take a backup for you
$liberator = new \Pialechini\Liberator\Liberator($product);
$liberator->set('price', 3);

$liberator->get('price'); // will return 3
$product->getPrice(); // will return 3

$liberator->restore();

$liberator->get('price'); // will return 300
$product->getPrice(); // will return 300
```
---
## Public Liberator::class methods
```php
public function call (string $method, ...$arguments) : mixed
public function get (string $property) : mixed
public function set (string $property, mixed $value) : void
public function restore () : void
```
