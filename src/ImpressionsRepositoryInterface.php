<?php
/**
 * Contains interface ImpressionsRepositoryInterface
 *
 * @package     Artkonekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Artkonekt\Kampaign;


/**
 * Interface ImpressionsRepositoryInterface.
 *
 * @package Artkonekt\Kampaign
 */
interface ImpressionsRepositoryInterface
{
    /**
     * Returns the impressions for a campaign.
     *
     * @param TrackableCampaign $campaign
     *
     * @return Impressions
     */
    public function findImpressionsByCampaign(TrackableCampaign $campaign);

    /**
     * Saves an Impressions instance.
     *
     * @param Impressions $impressions
     */
    public function save(Impressions $impressions);

}