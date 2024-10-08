---
title: 'Receive specific internal event with onEvent()'
docname: 'on_event'
---

## Overview

`(new Instructor)->onEvent(string $class, callable $callback)` method allows
you to receive callback when specified type of event is dispatched by Instructor.

This way you can plug into the execution process and monitor it, for example logging
or reacting to the events which are of interest to your application.

This example demonstrates how you can monitor outgoing requests and received responses
via Instructor's events.

Check the `Cognesy\Instructor\Events` namespace for the list of available events
and their properties.


## Example

```php
<?php
$loader = require 'vendor/autoload.php';
$loader->add('Cognesy\\Instructor\\', __DIR__ . '../../src/');

use Cognesy\Instructor\Events\Event;
use Cognesy\Instructor\Events\HttpClient\RequestSentToLLM;
use Cognesy\Instructor\Events\HttpClient\ResponseReceivedFromLLM;
use Cognesy\Instructor\Instructor;

class User
{
    public string $name;
    public int $age;
}

// let's mock a logger class - in real life you would use a proper logger, e.g. Monolog
$logger = new class {
    public function log(Event $event) {
        // we're using a predefined asLog() method to get the event data,
        // but you can access the event properties directly and customize the output
        echo $event->asLog()."\n";
    }
};

$user = (new Instructor)
    ->onEvent(RequestSentToLLM::class, fn($event) => $logger->log($event))
    ->onEvent(ResponseReceivedFromLLM::class, fn($event) => $logger->log($event))
    ->request(
        messages: "Jason is 28 years old",
        responseModel: User::class,
    )
    ->get();

dump($user);
?>
```
