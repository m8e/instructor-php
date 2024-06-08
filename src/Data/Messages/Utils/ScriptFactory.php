<?php

namespace Cognesy\Instructor\Data\Messages\Utils;

use Cognesy\Instructor\Data\Example;
use Cognesy\Instructor\Data\Messages\Script;
use Cognesy\Instructor\Data\Messages\Section;
use Exception;

class ScriptFactory
{
    static public function make(
        string|array $messages = [],
        string $dataAckPrompt = '',
        string $prompt = '',
        ?array $examples = null,
        string $retryPrompt = '',
        array $attempts = [],
    ) : Script {
        $instance = new self();
        return $instance->makeScript(
            $instance->normalizeMessages($messages),
            $dataAckPrompt,
            $prompt,
            $examples,
            $retryPrompt,
            $attempts,
        );
    }

    // INTERNAL ////////////////////////////////////////////////////////////////

    private function normalizeMessages(string|array $messages): array {
        if (!is_array($messages)) {
            return [['role' => 'user', 'content' => $messages]];
        }
        return $messages;
    }

    private function makeScript(
        array $messages,
        string $dataAckPrompt,
        string $prompt,
        array $examples,
        string $retryPrompt,
        array $attempts,
    ) : Script {
        if (empty($messages)) {
            throw new Exception('Messages cannot be empty - you have to provide the content for processing.');
        }

        $script = new Script();
        $script->addSection(new Section('system', 'System messages'));
        $script->addSection(new Section('messages', 'Chat messages'));
        $script->addSection(new Section('data_ack', 'Data acknowledged prompt'));
        $script->addSection(new Section('command', 'Command prompt'));
        $script->addSection(new Section('examples', 'Inference examples'));
        $script->addSection(new Section('retries', 'Responses and retries'));

        // SYSTEM SECTION
        $index = 0;
        foreach ($messages as $message) {
            if ($message['role'] !== 'system') {
                break;
            }
            $script->section('system')->appendMessage(['role' => 'system', 'content' => $message['content']]);
            $index++;
        }

        // DATA ACK SECTION
        $script->section('data_ack')->appendMessage([
            'role' => 'assistant',
            'content' => $dataAckPrompt
        ]);

        // MESSAGES SECTION
        $script->section('messages')->appendMessages(array_slice($messages, $index));

        // PROMPT SECTION
        if (!empty($prompt)) {
            $script->section('command')->appendMessage([
                'role' => 'user',
                'content' => $prompt
            ]);
        }

        // EXAMPLES SECTION
        if (!empty($examples)) {
            foreach ($examples as $item) {
                $example = match(true) {
                    is_array($item) => Example::fromArray($item),
                    is_string($item) => Example::fromJson($item),
                    $item instanceof Example => $item,
                };
                $script->section('examples')->appendMessages($example->toMessages());
            }
        }

        // RETRY SECTION
        if (!empty($attempts)) {
        }

        return $script;
    }
}