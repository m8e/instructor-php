<?php

namespace Cognesy\Instructor\ApiClient\Traits;

use Cognesy\Instructor\ApiClient\Requests\ApiRequest;
use Exception;

trait HandlesApiRequest
{
    use HandlesQueryParams;

    protected ApiRequest $apiRequest;

    public function withApiRequest(ApiRequest $request) : static {
        $this->apiRequest = $request;
        return $this;
    }

    public function getApiRequest() : ApiRequest {
        if (empty($this->apiRequest)) {
            throw new Exception('Request is not set');
        }
        if (!empty($this->queryParams)) {
            $this->apiRequest->query()->set($this->queryParams);
        }
        return $this->apiRequest;
    }

    protected function isStreamedRequest() : bool {
        return $this->apiRequest->isStreamed();
    }

    protected function withStreaming(bool $streaming) : void {
        $this->apiRequest->config()->add('stream', $streaming);
    }
}