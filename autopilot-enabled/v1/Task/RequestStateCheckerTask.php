<?php
namespace SosWorkflow\AutoPilotEnabled\V1\Task;

use SosFlowSDK\Workflow\Task\TaskInterface;

/**
 * @State Check Request State
 * @State\Next Switch Request State
 * @State\Endpoint /lead/generate
 *
 * @State\Switch Switch Request State
 * @State\Switch\Condition [$.requestState == pending -> Wait for Request Update Event,
 *                         $.requestState == approved -> Request Approved Strategy,
 *                         $.requestState == declined -> Remove Pending Messages]
 *
 * @State\Wait Wait for Request Update Event
 * @State\Wait\TimestampPath $.waitUntilTimestamp
 * @State\Wait\Next Switch Request State
 *
 * @State\Pass Catch All Fallback
 * @State\Pass\Next End
 *
 * @State\Catch [\SosFlowSDK\Workflow\Task\Exception\RuntimeException -> Wait for Request Update Event,
 *              \SosFlowSDK\Workflow\Task\Exception\NotFoundException -> Remove Pending Messages]
 */
class RequestStateCheckerTask implements TaskInterface
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
