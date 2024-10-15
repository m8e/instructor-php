<?php

namespace Cognesy\Instructor\Extras\Evals\Units;

use Cognesy\Instructor\Extras\Evals\Contracts\Unit;

class ScoreUnit implements Unit
{
    public function name(): string {
        return 'score';
    }

    public function isValid(mixed $value): bool {
        return is_numeric($value);
    }

    public function toString(mixed $value, array $format = []): string {
        return number_format($value, $format['precision'] ?? 2);
    }

    public function toFloat(mixed $value): float {
        return (float) $value;
    }
}
