<?php
namespace SosWorkflow\AutoPilotEnabled\V1\Extractor;

use SosFlowSDK\Workflow\Extract\ExtractionInterface;

/**
 * Class AnnounceDataExtractor
 * @package SosWorkflow\AutoPilotEnabled\V1\Extractor
 */
class AnnounceDataExtractor implements ExtractionInterface
{
    /**
     * @param AnnounceEntity $announceEntity
     *
     * @return array
     */
    public function extractor(AnnounceEntity $announceEntity)
    {
        return ['id' => $announceEntity->getId(),
                'createdTime' => $announceEntity->getCreatedTimestamp()];
    }
}
