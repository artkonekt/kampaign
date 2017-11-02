<?php

include __DIR__ . '/../vendor/autoload.php';

use Konekt\Kampaign\Tests\Doubles\FakeCampaignRepository;

$campaignRepository = new FakeCampaignRepository();

$dataResolver = new \Konekt\Kampaign\Common\DataResolver($_GET, $_POST, $_COOKIE);
$impressionRepo = new \Konekt\Kampaign\Impression\CookieImpressionsRepository($dataResolver);
$impressionTracker = new \Konekt\Kampaign\Impression\ImpressionsOperator($impressionRepo);