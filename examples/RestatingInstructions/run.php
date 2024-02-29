<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$loader = require 'vendor/autoload.php';
$loader->add('Cognesy\\Instructor\\', __DIR__ . '../../src/');

use Cognesy\Instructor\Attributes\Description;
use Cognesy\Instructor\Instructor;

// PROMPTING HINT: Make Instructor restate the instructions and rules to improve inference accuracy.

/**
 * Identify what kind of job the user is doing.
 * Typical roles we're working with are CEO, CTO, CFO, CMO, Sales, Marketing, Customer Support, Developer, Designer, etc.
 * Sometimes user does not state their role directly - you will need to make a guess, based on their description.
 */
class Role
{
    #[Description("Restate the instructions and rules to correctly determine the title.")]
    public string $instructions;
    #[Description("Role description")]
    public string $description;
    #[Description("Most likely job title")]
    public string $title;
}

class UserDetail
{
    public string $name;
    public int $age;
    public Role $role;
}

$user = (new Instructor)->respond(
    messages: [["role" => "user",  "content" => "I'm Jason, I'm 28 yo. I am responsible for driving growth of our company."]],
    responseModel: UserDetail::class,
);

dump($user);