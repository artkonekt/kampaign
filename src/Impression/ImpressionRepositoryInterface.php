<?php
/**
 * Contains interface ImpressionsRepositoryInterface
 *
 * @package     Konekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Konekt\Kampaign\Impression;
use Konekt\Kampaign\Campaign\TrackableCampaignInterface;


/**
 * Interface ImpressionsRepositoryInterface.
 *
 * @package Konekt\Kampaign
 */
interface ImpressionRepositoryInterface
{
    /**
     * Returns the impressions for a campaign.
     *
     * @param TrackableCampaignInterface $campaign
     *
     * @return Impressions
     */
    public function findImpressionsByCampaign(TrackableCampaignInterface $campaign);

    /**
     * Saves an Impressions instance.
     *
     * @param Impressions $impressions
     */
    public function save(Impressions $impressions);

    /**
     * Returns whether impressions are globally enabled for any campaigns.
     *
     * @return mixed
     */
    public function areEnabled();

    /**
     * Disables all impressions in the future.
     * @return mixed
     */
    public function disableFutureImpressions();

}