<?php
namespace Cognesy\Setup;

require __DIR__ . '/bootstrap.php';

use Symfony\Component\Console\Application;

$application = new Application('Instructor setup tool', '1.0.0');
$application->add(new PublishCommand());
$application->run();
