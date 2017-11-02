<?php

include 'init.php';

$campaign = $campaignRepository->findById($_POST['id']);

switch ($_POST['event']) {
    case 'disabled':
        $impressionTracker->disableFutureImpressions();
        break;
    case 'opened':
        $impressions = $impressionTracker->loadOrCreateFor($campaign);
        $impressionTracker->increase($impressions);
        break;
}