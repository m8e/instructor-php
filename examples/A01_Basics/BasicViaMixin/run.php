---
title: 'Basic use via mixin'
docname: 'basic_use_mixin'
---

## Overview

Instructor provides `HandlesSelfInference` trait that you can use to enable
extraction capabilities directly on class via static `infer()` method.

`infer()` method returns an instance of the class with the data extracted
using the Instructor.

`infer()` method has following signature (you can also find it in the
`CanSelfInfer` interface):

```php
static public function infer(
    string|array $messages, // (required) The message(s) to infer data from
    string $model = '',     // (optional) The model to use for inference (otherwise - use default)
    int $maxRetries = 2,    // (optional) The number of retries in case of validation failure
    array $options = [],    // (optional) Additional data to pass to the Instructor or LLM API
    array $examples = [],   // (optional) Examples to include in the prompt
    string $toolName = '',  // (optional) The name of the tool call - used to add semantic information for LLM
    string $toolDescription = '', // (optional) The description of the tool call - as above
    string $prompt = '',    // (optional) The prompt to use for inference
    string $retryPrompt = '', // (optional) The prompt to use in case of validation failure
    Mode $mode = Mode::Tools, // (optional) The mode to use for inference
    Instructor $instructor = null // (optional) The Instructor instance to use for inference
) : static;
```

## Example

```php
<?php
$loader = require 'vendor/autoload.php';
$loader->add('Cognesy\\Instructor\\', __DIR__ . '../../src/');

use Cognesy\Instructor\Extras\Mixin\HandlesSelfInference;
use Cognesy\Instructor\Instructor;

class User {
    use HandlesSelfInference;

    public int $age;
    public string $name;

    protected function getInstructor() : Instructor {
        return new Instructor();
    }

    protected function getResponseModel() : string|array|object {
        return $this;
    }
}

$user = User::infer("Jason is 25 years old and works as an engineer.");

dump($user);

assert(isset($user->name));
assert(isset($user->age));
assert($user->name === 'Jason');
assert($user->age === 25);
?>
```
