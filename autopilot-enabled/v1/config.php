<?php
use Interop\Container\ContainerInterface;

return [
    /**
     * Default execution params extractor.
     * It is used as extractor aggregate, to
     * call each extractor on given params and
     * concat all the result in the single resulted
     * array.
     */
    'WorkflowExtractor' => DI\object(SosFlowSDK\Workflow\Extract\ExtractorAggregate::class)
        ->method('attachExtractor',
            DI\get(\SosWorkflow\AutoPilotEnabled\V1\Extractor\AnnounceDataExtractor::class))
        ->method('attachExtractor',
            DI\get(\SosWorkflow\AutoPilotEnabled\V1\Extractor\CustomerEmailExtractor::class)),

    /**
     * Workflow definition
     */
    SosWorkflow\AutoPilotEnabled\V1\Workflow::class => DI\object()
        ->method('registerTask',
            DI\get(\SosWorkflow\AutoPilotEnabled\V1\Task\RequestApprovedStrategyTask::class))
        ->method('registerTask',
            DI\get(\SosWorkflow\AutoPilotEnabled\V1\Task\RequestStateCheckerTask::class))
];
