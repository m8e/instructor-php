<?php

namespace Cognesy\Instructor\Features\Core\Traits;

use Cognesy\Instructor\Exceptions\JsonParsingException;
use Cognesy\Instructor\Features\Core\Data\ResponseModel;
use Cognesy\Instructor\Utils\Arrays;
use Cognesy\Instructor\Utils\Chain;
use Cognesy\Instructor\Utils\Json\Json;
use Cognesy\Instructor\Utils\Result\Result;
use Exception;

trait ValidatesPartialResponse
{
    public function validatePartialResponse(
        string $partialResponseText,
        ResponseModel $responseModel,
        bool $preventJsonSchema,
        bool $matchToExpectedFields
    ) : Result {
        return Chain::make()
            ->through(fn() => $this->preventJsonSchemaResponse($preventJsonSchema, $partialResponseText))
            ->through(fn() => $this->detectNonMatchingJson($matchToExpectedFields, $partialResponseText, $responseModel))
            ->onFailure(fn($result) => throw new JsonParsingException(
                message: $result->errorMessage(),
                json: $partialResponseText,
            ))
            ->result();
    }

    // INTERNAL ////////////////////////////////////////////////////////

    private function preventJsonSchemaResponse(bool $check, string $partialResponseText) : Result {
        if (!$check) {
            return Result::success(true);
        }
        if (!$this->isJsonSchemaResponse($partialResponseText)) {
            return Result::success(true);
        }
        return Result::failure(new JsonParsingException(
            message: 'You started responding with JSONSchema. Respond correctly with strict JSON object data instead.',
            json: $partialResponseText,
        ));
    }

    private function isJsonSchemaResponse(string $responseText) : bool {
        try {
            $decoded = Json::fromPartial($responseText)->toArray();
        } catch (Exception $e) {
            // also covers no JSON at all - which is fine, as some models will respond with text
            return false;
        }
        return isset($decoded['type']) && $decoded['type'] === 'object';
    }

    private function detectNonMatchingJson(bool $check, string $responseText, ResponseModel $responseModel) : Result {
        if (!$check) {
            return Result::success(true);
        }
        if ($this->isMatchingResponseModel($responseText, $responseModel)) {
            return Result::success(true);
        }
        return Result::failure(new JsonParsingException(
            message: 'JSON does not match schema.',
            json: $responseText,
        ));
    }

    private function isMatchingResponseModel(
        string        $partialResponseText,
        ResponseModel $responseModel
    ) : bool {
        // ...check for response model property names
        $propertyNames = $responseModel->getPropertyNames();
        if (empty($propertyNames)) {
            return true;
        }
        // ...detect matching response model
        try {
            $decoded = Json::fromPartial($partialResponseText)->toArray();
            // we can try removing last item as it is likely to be still incomplete
            $decoded = Arrays::removeTail($decoded, 1);
        } catch (Exception $e) {
            return false;
        }
        // Question: how to make this work while we're getting partially
        // retrieved field names
        $decodedKeys = array_filter(array_keys($decoded));
        if (empty($decodedKeys)) {
            return true;
        }
        return Arrays::isSubset($decodedKeys, $propertyNames);
    }
}