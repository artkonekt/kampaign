<?php
/**
 * Contains interface TrackableCampaignInterface
 *
 * @package     Artkonekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Campaign;


/**
 * Interface TrackableCampaignInterface
 *
 * @package Artkonekt\Kampaign\Campaign
 */
interface TrackableCampaignInterface
{
    /**
     * Returns the tracking ID of the campaign. It should be unique.
     * A common way to implement it is to just return the numeric ID of the campaign entity.
     *
     * @return string
     */
    public function getTrackingId();

    /**
     * Returns the max impressions per day for a user aka. how many times the campaign can be shown for a user a day.
     *
     * @return int
     */
    public function getMaxImpressionPerDay();

    /**
     * Returns the max overall impressions for a user aka. how many times the campaign can be shown for a user.
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