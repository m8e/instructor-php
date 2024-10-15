<?php

namespace Cognesy\Instructor\Utils\Json;

use JsonException;

class Json
{
    private string $json;

    public function __construct(string $json = '') {
        $this->json = $json;
    }

    // NEW API ////////////////////////////////////////////////

    public static function none() : self {
        return new Json('');
    }

    public static function from(string $text) : Json {
        if (empty(trim($text))) {
            return new Json('');
        }
        $json = (new JsonParser)->findCompleteJson($text);
        return new Json($json);
    }

    public static function fromPartial(string $text) : Json {
        if (empty(trim($text))) {
            return new Json('');
        }
        $json = (new JsonParser)->findPartialJson($text);
        return new Json($json);
    }

    public function isEmpty() : bool {
        return $this->json === '';
    }

    public function toString() : string {
        return $this->json;
    }

    public function toArray() : array {
        if ($this->isEmpty()) {
            return [];
        }
        return json_decode($this->json, true);
    }

    // STATIC /////////////////////////////////////////////////

    public static function decode(string $text, mixed $default = null) : mixed {
        if (empty($text)) {
            return $default;
        }
        try {
            $decoded = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            if ($default === null) {
                throw $e;
            }
            return $default;
        }
        return empty($decoded) ? $default : $decoded;
    }

    public static function encode(mixed $json, int $options = 0) : string {
        return json_encode($json, $options);
    }
}
