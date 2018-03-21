<?php
namespace SosWorkflow\AutoPilotEnabled\V1\Task;

use SosFlowSDK\Workflow\Task\TaskInterface;

/**
 * @Task Check Request State
 *
 * @Task\Parallel Wait for Request Update Event
 * @Task\Parallel\Branches [LookupAddress, LookupPhone]
 * @Task\Parallel\Next End
 */
class RequestApprovedStrategyTask implements TaskInterface
{
    /**
     * @var AnnounceTable
     */
    protected $announceTable;

    /**
     * RequestStateCheckerTask constructor.
     *
     * @param AnnounceTable $announceTable
     */
    public function __construct(AnnounceTable $announceTable)
    {
        $this->announceTable = $announceTable;
    }

    /**
     * @param SfnRequest $sfnRequest
     *
     * @Task\AssertRequest [id: Integer, name: String, pros: Integer[]]
     *
     * @throws SosFlowSDK\Workflow\Task\Exception\RuntimeException
     */
    public function execute(SfnRequest $sfnRequest)
    {
        $announceEntity = $this->announceTable->find($sfnRequest->getParam('id'));

        if ($announceEntity->state === 0) {
            return ['requestState' => 'pending',
                'waitUntilTimestamp' => new \DateInterval('PT1MS')];
        } else if ($announceEntity->state === 1) {
            return ['requestState' => 'approved'];
        } else if ($announceEntity->state === 2) {
            return ['requestState' => 'declined'];
        } else {
            throw new \SosFlowSDK\Workflow\Task\Exception\RuntimeException('Unknown state of the request');
        }
    }
}
