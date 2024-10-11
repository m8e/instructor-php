<?php

namespace Cognesy\Instructor\Extras\Evals\Data;

use Cognesy\Instructor\Enums\Mode;
use Cognesy\Instructor\Extras\Evals\Utils\Combination;
use Cognesy\Instructor\Utils\Settings;
use Generator;

class InferenceCases
{
    private array $connections = [];
    private array $modes = [];
    private array $stream = [];

    private function __construct(
        array $connections = [],
        array $modes = [],
        array $stream = [],
    ) {
        $this->connections = $connections;
        $this->modes = $modes;
        $this->stream = $stream;
    }

    public static function except(
        array $connections = [],
        array $modes = [],
        array $stream = [],
    ) : Generator {
        $instance = (new self)->initiateWithAll();
        $instance->connections = match(true) {
            [] === $connections => $instance->connections,
            default => array_diff($instance->connections, $connections),
        };
        $instance->modes = match(true) {
            [] === $modes => $instance->modes,
            default => array_filter($instance->modes, fn($mode) => !$mode->isIn($modes)),
        };
        $instance->stream = match(true) {
            [] === $stream => $instance->stream,
            default => array_diff($instance->stream, $stream),
        };
        return $instance->make();
    }

    public static function only(
        array $connections = [],
        array $modes = [],
        array $stream = [],
    ) : Generator {
        $instance = (new self)->initiateWithAll();
        $instance->connections = match(true) {
            [] === $connections => $instance->connections,
            default => array_intersect($instance->connections, $connections),
        };
        $instance->modes = match(true) {
            [] === $modes => $instance->modes,
            default => array_filter($instance->modes, fn($mode) => $mode->isIn($modes)),
        };
        $instance->stream = match(true) {
            [] === $stream => $instance->stream,
            default => array_intersect($instance->stream, $stream),
        };
        return $instance->make();
    }

    public static function all() : Generator {
        return (new self)->initiateWithAll()->make();
    }

    // INTERNAL //////////////////////////////////////////////////

    private function initiateWithAll() : self {
        return new self(
            connections: $this->connections(),
            modes: $this->modes(),
            stream: $this->streamingModes(),
        );
    }

    private function make() : Generator {
        return Combination::generator(
            mapping: InferenceParamsCase::class,
            sources: [
                'isStreaming' => $this->stream ?: $this->streamingModes(),
                'mode' => $this->modes ?: $this->modes(),
                'connection' => $this->connections ?: $this->connections(),
            ],
        );
    }

    private function connections() : array {
//        return Settings::get('llm', 'connections', []);
        return [
            'azure',
            'cohere1',
            'cohere2',
            'fireworks',
            'gemini',
            'groq',
            'mistral',
            'ollama',
            'openai',
            'openrouter',
            'together',
        ];
    }

    private function streamingModes() : array {
        return [
            false,
            true,
        ];
    }

    private function modes() : array {
        return [
            Mode::Text,
            Mode::MdJson,
            Mode::Json,
            Mode::JsonSchema,
            Mode::Tools,
        ];
    }
}