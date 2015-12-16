<?php

namespace Artkonekt\Kampaign\Tests\Doubles;

use Artkonekt\Kampaign\Campaign\CampaignRepositoryInterface;
use DateTime;

/**
 * Class FakeCampaignRepository
 */
class FakeCampaignRepository implements CampaignRepositoryInterface
{
    public function findById($campaignId)
    {
        return new Campaign($campaignId, 'Test Campaign #' . $campaignId, 'Description of the campaign', true, new DateTime(), new DateTime(), 20, 5);
    }

    public function findCurrent()
    {
        return $this->findById(isset($_GET['id']) ? $_GET['id'] : 1);
    }
}