<?php
namespace Cognesy\Instructor\Utils\Messages;

class Message {
    use Traits\Message\HandlesCreation;
    use Traits\Message\HandlesMutation;
    use Traits\Message\HandlesAccess;
    use Traits\Message\HandlesTransformation;

    public const DEFAULT_ROLE = 'user';

    /**
     * @param string $role
     * @param string|array<string|array> $content
     */
    public function __construct(
        public string $role = '',
        public string|array $content = '',
    ) {}
}
