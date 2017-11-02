<?php
/**
 * Contains class Factory
 *
 * @package     Konekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Konekt\Kampaign\Tests\Helper;

use Konekt\Kampaign\Campaign\TrackableCampaignInterface;
use Konekt\Kampaign\Impression\Impressions;
use Konekt\Kampaign\Tests\Doubles\Campaign;
use DateTime;

class Factory
{
    /**
     * Creates a campaign
     *
     * @param bool $isActive
     *
     * @return Campaign
     */
    public static function cc($isActive = true)
    {
        return new Campaign(1, 'TestCampaign', 'Test Description', $isActive, new DateTime(), new DateTime(), 10, 3);
    }

    /**
     * Creates an active campaign with specific impression settings.
     *
     * @param int $maxImpressionsPerDay
     * @param int $maxImpressions
     *
     * @param int $id The ID of the campaign, is equal to 1 if not specified
     *
     * @return Campaign
     */
    public static function cci($maxImpressionsPerDay, $maxImpressions, $id = 1)
    {
        return new Campaign($id, 'TestCampaign', 'Test Description', true, new DateTime(), new DateTime(), $maxImpressions, $maxImpressionsPerDay);
    }

    /**
     * Creates an impression value object
     *
     * @param TrackableCampaignInterface $campaign
     * @param int                        $impressionsToday
     * @param int                        $impressionsTotal
     *
     * @return Impressions
     */
    public static function ci(TrackableCampaignInterface $campaign, $impressionsToday, $impressionsTotal)
    {
        return new Impressions($campaign, $impressionsToday, $impressionsTotal);
    }

    /**
     * Creates an impression value object together with its dependent campaign.
     *
     * @param int $maxImpressionsPerDay
     * @param int $maxImpressions
     * @param int $impressionsToday
     * @param int $impressionsTotal
     *
     * @param int $campaignId
     *
     * @return Impressions
     */
    public static function cici($maxImpressionsPerDay, $maxImpressions, $impressionsToday, $impressionsTotal, $campaignId = 1)
    {
        $c = self::cci($maxImpressionsPerDay, $maxImpressions, $campaignId);
        return self::ci($c, $impressionsToday, $impressionsTotal);
    }
}