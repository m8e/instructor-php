<?php

namespace Cognesy\Evals\ComplexExtraction;

/** Represents type of project event */
enum ProjectEventType: string {
    case Risk = 'risk';
    case Issue = 'issue';
    case Action = 'action';
    case Progress = 'progress';
    case Other = 'other';
}
