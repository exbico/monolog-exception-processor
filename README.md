MONOLOG EXCEPTION PROCESSOR
=================

[![Latest Stable Version](https://poser.pugx.org/exbico/monolog-exception-processor/v/stable)](https://packagist.org/packages/exbico/monolog-exception-processor) [![Total Downloads](https://poser.pugx.org/exbico/monolog-exception-processor/downloads)](https://packagist.org/packages/exbico/monolog-exception-processor) [![License](https://poser.pugx.org/drtsb/yii2-seo/license)](https://packagist.org/packages/exbico/monolog-exception-processor)

## INSTALLATION

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require exbico/monolog-exception-processor
```

or add

```
"exbico/monolog-exception-processor": "*"
```

to the require section of your application's `composer.json` file.

## Basic Usage

```php
<?php

use Exbico\Formatter\ExceptionProcessor;
use Monolog\Logger;

$logger = new Logger();
$logger->pushProcessor(new ExceptionProcessor);

```

## USAGE

```php
<?php
try{
    throw new Exception('test');
}catch(Throwable $exception) {
    $logger->alert('message', [..., 'exception' => $exception, ...]);
}

```

Resulted record for any Throwable

```
[
  "message" => "message",
  "context" => [...],
  ...
  "extra" => [
    "message" => "test",
    "class"   => "Exception",
    "trace"   => "#0 {main}",
  ],
]
```

Resulted record, if Throwable has previous

```
[
  "message" => "message",
  "context" => [...],
  ...
  "extra"   => [
    "message"  => "test",
    "class"    => "Exception",
    "trace"    =>"#0 {main}",
    "previous" => [
        "message" => "previous message",
        "class"   => "Class of previous",
        "trace"   => "#0 {main}",
    ],
  ],
]
```

You can implement ExceptionWithContext or extend ContextException

```php
<?php

use Exbico\Formatter\ExceptionWithContext;
use Exbico\Formatter\ExceptionWithContextInterface;

class FooException extends ExceptionWithContext
{

}

class BarException implements ExceptionWithContextInterface
{
    ...

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        ...
    }
}

try {
    throw new FooException(message: 'test', context: ['id' => 12, 'text' => '...'], previous: $previousException);
} catch (FooException $exception) {
    $logger->alert('message', ['id' => 34, 'exception' => $exception, 'date' => '...']);
}


```

Then record will look:

```
[
  "message" => "message",
  "context" => [
    "id"   => 12,
    "text" => "...",
    "date" => "...",
  ],
  ...
  "extra" => [
    "message"  => "test",
    "class"    => "Exception",
    "trace"    =>"#0 {main}",
    "previous" => [
        "message" => "previous message",
        "class"   => "Class of previous",
        "trace"   => "#0 {main}",
    ],
  ],
]
```

WARNING if you have equal keys in both contexts, then value of exception context has higher priority 
