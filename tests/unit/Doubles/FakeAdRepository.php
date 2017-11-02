<?php

namespace Konekt\Kampaign\Tests\Doubles;

use Konekt\Kampaign\Ad\AdRepositoryInterface;
use DateTime;

/**
 * Class FakeAdRepository
 */
class FakeAdRepository implements AdRepositoryInterface
{
    public function findById($adId)
    {
        return new Ad($adId, 'Test Ad #' . $adId, 'Description of the ad', true, new DateTime(), new DateTime(), 20, 5);
    }

    public function findCurrent()
    {
        return $this->findById(isset($_GET['id']) ? $_GET['id'] : 1);
    }
}