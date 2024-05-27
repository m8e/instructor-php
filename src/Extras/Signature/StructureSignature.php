<?php

namespace Cognesy\Instructor\Extras\Signature;

use Cognesy\Instructor\Extras\Field\Field;
use Cognesy\Instructor\Extras\Signature\Contracts\Signature;
use Cognesy\Instructor\Extras\Signature\Traits\ConvertsToString;
use Cognesy\Instructor\Extras\Structure\Structure;
use Cognesy\Instructor\Extras\Structure\StructureFactory;

class StructureSignature implements Signature
{
    use ConvertsToString;

    protected Structure $inputs;
    protected Structure $outputs;
    protected string $description = '';
    protected string $prompt = 'Your task is to find output arguments in input data based on specification: {signature} {description}';

    public function __construct(
        string|Signature $signature = null,
        Structure $inputs = null,
        Structure $outputs = null,
        string $description = null,
    ) {
        if (!is_null($inputs)) {
            $this->inputs = $inputs;
        }
        if (!is_null($outputs)) {
            $this->outputs = $outputs;
        }
        if (is_string($signature)) {
            $signature = SignatureFactory::fromString($signature);
            $this->inputs = $signature->inputs;
        }
        if ($signature instanceof Signature) {
            $this->inputs = $signature->inputs;
            $this->outputs = $signature->outputs;
        }
        if (!is_null($description)) {
            $this->description = $description;
        }
    }

    public function getInputs(): Structure {
        return $this->inputs;
    }

    public function asInputArgs(): array {
        return $this->inputs->fieldValues();
    }

    /** @return Field[] */
    public function getInputFields(): array {
        return $this->inputs->fields();
    }

    public function getOutputs(): Structure {
        return $this->outputs;
    }

    /** @return Field[] */
    public function getOutputFields(): array {
        return $this->outputs->fields();
    }

    public function asOutputValues(): array {
        return $this->inputs->fieldValues();
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function withInputClass(string|object $input): static {
        $this->inputs = $this->makeStructureFromClass($input);
        return $this;
    }

    public function withOutputClass(string|object $output): static {
        $this->outputs = $this->makeStructureFromClass($output);
        return $this;
    }

    protected function makeStructureFromClass(string|object $class): Structure {
        $class = is_string($class) ? $class : get_class($class);
        return StructureFactory::fromClass($class);
    }
}