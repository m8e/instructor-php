<?php
namespace Cognesy\Instructor\Data\Traits\Example;

use BackedEnum;
use Cognesy\Instructor\Data\Messages\Messages;
use Cognesy\Instructor\Utils\Json;
use Cognesy\Instructor\Utils\TemplateUtil;

trait HandlesConversion
{
    public function toArray() : array {
        return match(true) {
            is_array($this->output) => $this->output,
            is_scalar($this->output) => ['value' => $this->output],
            is_object($this->output) && $this->output instanceof BackedEnum => ['value' => $this->output->value()],
            is_object($this->output) && method_exists($this->output, 'toArray') => $this->output->toArray(),
            is_object($this->output) && method_exists($this->output, 'toJson') => $this->output->toJson(),
            is_object($this->output) => get_object_vars($this->output),
            default => [],
        };
    }

    public function toString() : string {
        return TemplateUtil::render($this->template, [
            'input' => $this->inputString(),
            'output' => $this->outputString(),
        ]);
    }

    public function toXmlArray() : array {
        return ['example' => [
            'input' => ['_cdata' => $this->inputString()],
            'output' => ['_cdata' => "```json\n" . $this->outputString() . "\n```"],
        ]];
    }

    public function toMessages() : Messages {
        return Messages::fromArray([
            ['role' => 'user', 'content' => $this->inputString()],
            ['role' => 'assistant', 'content' => $this->outputString()],
        ]);
    }

    public function toJson() : string {
        return Json::encode($this, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->uid,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'input' => $this->input(),
            'output' => $this->output(),
        ];
    }
}