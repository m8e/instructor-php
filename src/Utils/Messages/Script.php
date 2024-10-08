<?php
namespace Cognesy\Instructor\Utils\Messages;

class Script {
    use Traits\Script\HandlesAccess;
    use Traits\Script\HandlesParameters;
    use Traits\Script\HandlesConversion;
    use Traits\Script\HandlesCreation;
    use Traits\Script\HandlesMutation;
    use Traits\Script\HandlesReordering;
    use Traits\Script\HandlesTransformation;
    use Traits\RendersContent;

    /** @var Section[] */
    private array $sections;

    public function __construct(Section ...$sections) {
        $this->sections = $sections;
        $this->parameters = new ScriptParameters(null);
    }
}
