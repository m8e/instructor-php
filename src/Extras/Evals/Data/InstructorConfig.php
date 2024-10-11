<?php

namespace Cognesy\Instructor\Extras\Evals\Data;

class InstructorConfig
{
    public function __construct(
        public string|array $messages = '',
        public string|array|object $responseModel = '',
        public int $maxTokens = 512,
        public string $toolName = '',
        public string $toolDescription = '',

        public string $system = '',
        public string $prompt = '',
        public string|array|object $input = '',
        public array $examples = [],
        public string $model = '',
        public string $retryPrompt = '',
        public int $maxRetries = 0,
    ) {}
}