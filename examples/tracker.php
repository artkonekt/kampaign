<?php

include 'init.php';

$ad = $adRepository->findById($_POST['id']);

switch ($_POST['event']) {
    case 'disabled':
        $impressionTracker->disableFutureImpressions();
        break;
    case 'opened':
        $impressions = $impressionTracker->loadOrCreateFor($ad);
        $impressionTracker->increase($impressions);
        break;
}