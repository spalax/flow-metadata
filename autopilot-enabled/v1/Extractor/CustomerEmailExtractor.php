<?php
namespace SosWorkflow\AutoPilotEnabled\V1\Extractor;

use SosFlowSDK\Workflow\Extract\ExtractionInterface;

/**
 * Class CustomerEmailExtractor
 * @package SosWorkflow\AutoPilotEnabled\V1\Extractor
 */
class CustomerEmailExtractor implements ExtractionInterface
{
    /**
     * @param AnnounceEntity $announceEntity
     *
     * @return array
     */
    public function extractor(AnnounceEntity $announceEntity)
    {
        return ['customerEmail' => $announceEntity->getId()];
    }
}
