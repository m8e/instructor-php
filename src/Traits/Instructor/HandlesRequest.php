<?php

namespace Cognesy\Instructor\Traits\Instructor;

use Cognesy\Instructor\Data\Request;
use Cognesy\Instructor\Extras\Http\Contracts\CanHandleHttp;
use Cognesy\Instructor\Extras\LLM\Contracts\CanHandleInference;
use JetBrains\PhpStorm\Deprecated;

trait HandlesRequest
{
    private ?CanHandleInference $driver = null;
    private ?CanHandleHttp $httpClient = null;
    private string $connection = '';

    private Request $request;
    private array $cachedContext = [];

    // PUBLIC /////////////////////////////////////////////////////////////////////

    public function cacheContext(
        string|array $messages = '',
        string|array|object $input = '',
        string $system = '',
        string $prompt = '',
        array $examples = [],
    ) : ?self {
        $this->cachedContext = [
            'messages' => $messages,
            'input' => $input,
            'system' => $system,
            'prompt' => $prompt,
            'examples' => $examples,
        ];
        return $this;
    }

    public function getRequest() : Request {
        return $this->request;
    }

    public function withDriver(CanHandleInference $driver) : self {
        $this->driver = $driver;
        return $this;
    }

    public function withHttpClient(CanHandleHttp $httpClient) : self {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function withConnection(string $connection) : self {
        $this->connection = $connection;
        return $this;
    }

    #[Deprecated]
    public function withClient(string $client) : self {
        $this->connection = $client;
        return $this;
    }
}
