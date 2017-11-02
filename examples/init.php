<?php

include __DIR__ . '/../vendor/autoload.php';

use Konekt\Kampaign\Tests\Doubles\FakeAdRepository;

$adRepository = new FakeAdRepository();

$dataResolver = new \Konekt\Kampaign\Common\DataResolver($_GET, $_POST, $_COOKIE);
$impressionRepo = new \Konekt\Kampaign\Impression\CookieImpressionRepository($dataResolver);
$impressionTracker = new \Konekt\Kampaign\Impression\ImpressionTracker($impressionRepo);