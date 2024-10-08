<?php

namespace Cognesy\Instructor\Utils\Messages\Traits\Script;

use Cognesy\Instructor\Utils\Messages\Messages;
use Cognesy\Instructor\Utils\Messages\Script;
use Cognesy\Instructor\Utils\Messages\Section;

trait HandlesCreation
{
    /**
     * @param array<string, string|array> $sections
     * @return static
     */
    static public function fromArray(array $sections) : Script {
        $sectionList = [];
        foreach ($sections as $name => $content) {
            $sectionList[] = (new Section($name))->appendMessages(
                match(true) {
                    is_string($content) => Messages::fromString($content),
                    is_array($content) => Messages::fromArray($content),
                }
            );
        }
        return new self(...$sectionList);
    }

    static public function clone(Script $script) : Script {
        $script = new Script(...$script->sections);
        $script->withParams($script->parameters());
        return $script;
    }
}