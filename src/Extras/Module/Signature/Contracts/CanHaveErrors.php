<?php

namespace Cognesy\Instructor\Extras\Module\Signature\Contracts;

interface CanHaveErrors
{
    public function hasErrors() : bool;

    public function errors() : array;

    public function addError(string $message, array $context) : void;
}