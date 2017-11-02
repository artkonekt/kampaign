<?php

include __DIR__ . '/../vendor/autoload.php';

use Konekt\Kampaign\Ad\AdLoader;
use Konekt\Kampaign\Tests\Doubles\FakeAdRepository;

$dataResolver = new \Konekt\Kampaign\Common\DataResolver($_GET, $_POST, $_COOKIE);

$adLoader = new AdLoader(new FakeAdRepository(), $dataResolver);

$impressionRepo = new \Konekt\Kampaign\Impression\CookieImpressionRepository($dataResolver);
$impressionTracker = new \Konekt\Kampaign\Impression\ImpressionTracker($impressionRepo);