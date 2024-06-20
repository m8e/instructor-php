<?php

namespace Cognesy\Instructor\Validation;

use Cognesy\Instructor\Events\EventDispatcher;
use Cognesy\Instructor\Events\Response\CustomResponseValidationAttempt;
use Cognesy\Instructor\Events\Response\ResponseValidated;
use Cognesy\Instructor\Events\Response\ResponseValidationAttempt;
use Cognesy\Instructor\Events\Response\ResponseValidationFailed;
use Cognesy\Instructor\Utils\Result;
use Cognesy\Instructor\Validation\Contracts\CanValidateObject;
use Cognesy\Instructor\Validation\Contracts\CanValidateSelf;

class ResponseValidator
{
    public function __construct(
        private EventDispatcher $events,
        /** @var CanValidateObject[] $validators */
        private array $validators,
    ) {}

    /**
     * Validate deserialized response object
     */
    public function validate(object $response) : Result {
        $validation = match(true) {
            $response instanceof CanValidateSelf => $this->validateSelf($response),
            default => $this->validateObject($response)
        };
        $this->events->dispatch(match(true) {
            $validation->isInvalid() => new ResponseValidationFailed($validation),
            default => new ResponseValidated($validation)
        });
        return match(true) {
            $validation->isInvalid() => Result::failure($validation->getErrorMessage()),
            default => Result::success($response)
        };
    }

    public function addValidator(CanValidateObject $validator) : self {
        $this->validators[] = $validator;
        return $this;
    }

    protected function validateSelf(CanValidateSelf $response) : ValidationResult {
        $this->events->dispatch(new CustomResponseValidationAttempt($response));
        return $response->validate();
    }

    protected function validateObject(object $response) : ValidationResult {
        $this->events->dispatch(new ResponseValidationAttempt($response));
        $results = [];
        foreach ($this->validators as $validator) {
            $results[] = $validator->validate($response);
        }
        return ValidationResult::merge($results);
    }
}