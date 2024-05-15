<?php

namespace Cognesy\Instructor\Data;

use Cognesy\Instructor\ApiClient\Contracts\CanCallApi;
use Cognesy\Instructor\ApiClient\Factories\ModelFactory;
use Cognesy\Instructor\ApiClient\ModelParams;
use Cognesy\Instructor\Core\ResponseModelFactory;
use Cognesy\Instructor\Enums\Mode;

class Request
{
    use Traits\HandlesOptions;
    use Traits\HandlesModel;
    use Traits\HandlesSchema;
    use Traits\HandlesApiClient;
    use Traits\HandlesRetries;
    use Traits\HandlesPrompts;
    use Traits\HandlesMessages;
    use Traits\HandlesExamples;

    private Mode $mode;

    public function __construct(
        string|array $messages,
        string|object|array $responseModel,
        array $examples = [],
        string|ModelParams $model = '',
        int $maxRetries = 0,
        array $options = [],
        string $toolName = '',
        string $toolDescription = '',
        string $prompt = '',
        string $retryPrompt = '',
        Mode $mode = Mode::Tools,
        CanCallApi $client = null,
        ModelFactory $modelFactory = null,
        ResponseModelFactory $responseModelFactory = null,
    ) {
        $this->messages = $messages;
        $this->requestedSchema = $responseModel;
        $this->maxRetries = $maxRetries;
        $this->options = $options;
        $this->toolName = $toolName ?: $this->defaultToolName;
        $this->toolDescription = $toolDescription ?: $this->defaultToolDescription;
        $this->mode = $mode;
        $this->client = $client;
        $this->prompt = $prompt ?: $this->defaultPrompts[$this->mode->value];
        $this->retryPrompt = $retryPrompt ?: $this->defaultRetryPrompt;

        $this->modelFactory = $modelFactory;
        $this->responseModelFactory = $responseModelFactory;

        $this->withExamples($examples);
        $this->withModel($model);
        $this->withResponseModel(
            $this->responseModelFactory->fromAny(
                $this->requestedSchema(),
                $this->toolName(),
                $this->toolDescription()
            )
        );
    }

    public function mode() : Mode {
        return $this->mode;
    }

    public function copy(array $messages) : self {
        return (clone $this)->withMessages($messages);
    }
}
