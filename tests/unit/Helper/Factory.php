<?php
/**
 * Contains class Factory
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 */

namespace Konekt\Kampaign\Tests\Helper;

use Konekt\Kampaign\Ad\TrackableAdInterface;
use Konekt\Kampaign\Impression\Impressions;
use Konekt\Kampaign\Tests\Doubles\Ad;
use DateTime;

class Factory
{
    /**
     * Creates a ad
     *
     * @param bool $isActive
     *
     * @return Ad
     */
    public static function cc($isActive = true)
    {
        return new Ad(1, 'TestAd', 'Test Description', $isActive, new DateTime(), new DateTime(), 10, 3);
    }

    /**
     * Creates an active ad with specific impression settings.
     *
     * @param int $maxImpressionsPerDay
     * @param int $maxImpressions
     *
     * @param int $id The ID of the ad, is equal to 1 if not specified
     *
     * @return Ad
     */
    public static function cci($maxImpressionsPerDay, $maxImpressions, $id = 1)
    {
        return new Ad($id, 'TestAd', 'Test Description', true, new DateTime(), new DateTime(), $maxImpressions, $maxImpressionsPerDay);
    }

    /**
     * Creates an impression value object
     *
     * @param TrackableAdInterface $ad
     * @param int                  $impressionsToday
     * @param int                  $impressionsTotal
     *
     * @return Impressions
     */
    public static function ci(TrackableAdInterface $ad, $impressionsToday, $impressionsTotal)
    {
        return new Impressions($ad, $impressionsToday, $impressionsTotal);
    }

    /**
     * Creates an impression value object together with its dependent ad.
     *
     * @param int $maxImpressionsPerDay
     * @param int $maxImpressions
     * @param int $impressionsToday
     * @param int $impressionsTotal
     *
     * @param int $adId
     *
     * @return Impressions
     */
    public static function cici($maxImpressionsPerDay, $maxImpressions, $impressionsToday, $impressionsTotal, $adId = 1)
    {
        $c = self::cci($maxImpressionsPerDay, $maxImpressions, $adId);
        return self::ci($c, $impressionsToday, $impressionsTotal);
    }
}