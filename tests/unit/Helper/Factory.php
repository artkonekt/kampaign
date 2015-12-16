<?php
/**
 * Contains class Factory
 *
 * @package     Artkonekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Artkonekt\Kampaign\Tests\Helper;

use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;
use Artkonekt\Kampaign\Impression\Impressions;
use Artkonekt\Kampaign\Tests\Doubles\Campaign;
use DateTime;

class Factory
{
    /**
     * Creates a campaign
     *
     * @param bool $isActive
     *
     * @return \Campaign
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
     * @return \Artkonekt\Kampaign\Campaign
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
     * @param bool                       $isShowingAllowed
     *
     * @return \Artkonekt\Kampaign\Impression\Impressions
     */
    public static function ci(TrackableCampaignInterface $campaign, $impressionsToday, $impressionsTotal, $isShowingAllowed = true)
    {
        return new Impressions($campaign, $impressionsToday, $impressionsTotal, $isShowingAllowed);
    }

    /**
     * Creates an impression value object together with its dependent campaign.
     *
     * @param int  $maxImpressionsPerDay
     * @param int  $maxImpressions
     * @param int  $impressionsToday
     * @param int  $impressionsTotal
     *
     * @param bool $isShowingAllowed
     *
     * @param int  $campaignId
     *
     * @return \Artkonekt\Kampaign\Impression\Impressions
     */
    public static function cici($maxImpressionsPerDay, $maxImpressions, $impressionsToday, $impressionsTotal, $isShowingAllowed = true, $campaignId = 1)
    {
        $c = self::cci($maxImpressionsPerDay, $maxImpressions, $campaignId);
        return self::ci($c, $impressionsToday, $impressionsTotal, $isShowingAllowed);
    }
}