<?php
/**
 * Contains interface TrackableAdInterface
 *
 * @package     Konekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Konekt\Kampaign\Ad;


/**
 * Interface TrackableAdInterface
 *
 * @package Konekt\Kampaign\Ad
 */
interface TrackableAdInterface
{
    /**
     * Returns the tracking ID of the ad. It should be unique.
     * A common way to implement it is to just return the numeric ID of the ad entity.
     *
     * @return string
     */
    public function getTrackingId();

    /**
     * Returns the max impressions per day for a user aka. how many times the ad can be shown for a user a day.
     *
     * @return int
     */
    public function getMaxImpressionPerDay();

    /**
     * Returns the max overall impressions for a user aka. how many times the ad can be shown for a user.
     *
     * @return int
     */
    public function getMaxImpressions();

    /**
     * Returns the contents to be rendered in the popup
     *
     * @return string
     */
    public function getContent();

    /**
     * Returns the list id.
     *
     * @return mixed
     */
    public function getListId();
}