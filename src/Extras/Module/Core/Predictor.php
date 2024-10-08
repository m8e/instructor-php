<?php
namespace Cognesy\Instructor\Extras\Module\Core;

use Closure;
use Cognesy\Instructor\Extras\Module\Signature\Signature;
use Cognesy\Instructor\Features\Core\Data\RequestInfo;
use Cognesy\Instructor\Features\LLM\Inference;
use Cognesy\Instructor\Instructor;

class Predictor
{
    use Traits\Predictor\HandlesCreation;
    use Traits\Predictor\HandlesAccess;
    use Traits\Predictor\HandlesFeedback;
    use Traits\Predictor\HandlesParametrization;
    use Traits\Predictor\HandlesPrediction;

    protected Instructor $instructor;
    protected Inference $inference;
    protected string $connection;

    protected RequestInfo $requestInfo;
    protected ?Signature $signature;
    protected Feedback $feedback;
    protected Closure $feedbackFn;
    protected string $roleDescription;
    private string $instructions;
//    protected ProvideFeedback $provideFeedback;

    public function __construct(
        string|Signature $signature = '',
        string $description = '',
        string $roleDescription = '',
        string $instructions = '',
    ) {
        $this->instructor = new Instructor();
        $this->requestInfo = new RequestInfo();
        $this->signature = match(true) {
            !empty($signature) => $this->makeSignature($signature, $description),
            default => null,
        };
        $this->instructions = $instructions;
        $this->roleDescription = $roleDescription;
        $this->feedback = new Feedback();
//        $this->provideFeedback = new ProvideFeedback();
//        $this->instructions = new Parameter(
//            $this->signature->toInstructions(),
//            requiresFeedback: true,
//            roleDescription: 'Predictor instructions'
//        );
    }
}
