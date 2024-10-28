<?php
namespace Cognesy\Instructor\Extras\Evals;

use Cognesy\Instructor\Events\EventDispatcher;
use Cognesy\Instructor\Extras\Evals\Console\Display;
use Cognesy\Instructor\Extras\Evals\Contracts\CanRunExecution;
use Cognesy\Instructor\Extras\Evals\Observers\Aggregate\ExperimentFailureRate;
use Cognesy\Instructor\Extras\Evals\Observers\Aggregate\ExperimentLatency;
use Cognesy\Instructor\Extras\Evals\Observers\Measure\DurationObserver;
use Cognesy\Instructor\Extras\Evals\Observers\Measure\TokenUsageObserver;
use Cognesy\Instructor\Features\LLM\Data\Usage;
use Cognesy\Instructor\Utils\DataMap;
use Cognesy\Instructor\Utils\Uuid;
use DateTime;
use Exception;
use Generator;

class Experiment {
    use Traits\Experiment\HandlesAccess;
    use Traits\Experiment\HandlesExecution;

    private EventDispatcher $events;
    private array $defaultProcessors = [
        DurationObserver::class,
        TokenUsageObserver::class,
        ExperimentLatency::class,
        ExperimentFailureRate::class,
    ];

    private Display $display;
    private Generator $cases;
    private CanRunExecution $executor;
    private array $processors = [];
    private array $postprocessors = [];

    readonly private string $id;
    private ?DateTime $startedAt = null;
    private float $timeElapsed = 0.0;
    private ?Usage $usage = null;
    private DataMap $data;

    /** @var Execution[] */
    private array $executions = [];
    /** @var Exception[] */
    private array $exceptions = [];

    /** @var Observation[] */
    private array $observations = [];

    public function __construct(
        Generator       $cases,
        CanRunExecution $executor,
        array|object    $processors,
        array|object    $postprocessors,
        EventDispatcher $events = null,
    ) {
        $this->events = $events ?? new EventDispatcher();
        $this->id = Uuid::uuid4();
        $this->display = new Display();
        $this->data = new DataMap();

        $this->cases = $cases;
        $this->executor = $executor;
        $this->processors = match (true) {
            is_array($processors) => $processors,
            default => [$processors],
        };
        $this->postprocessors = match (true) {
            is_array($postprocessors) => $postprocessors,
            default => [$postprocessors],
        };
    }

    // PUBLIC //////////////////////////////////////////////////

    public function toArray() : array {
        return [
            'id' => $this->id,
            'data' => $this->data->toArray(),
            'executions' => array_map(fn($e) => $e->id(), $this->executions),
        ];
    }
}
