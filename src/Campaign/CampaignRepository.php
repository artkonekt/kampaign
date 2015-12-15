<?php
/**
 * Contains interface CampaignRepository
 *
 * @package     Artkonekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Artkonekt\Kampaign\Campaign;


/**
 * Interface CampaignRepository
 *
 * @package Artkonekt\Kampaign\Campaign
 */
interface CampaignRepository
{
    /**
     * @return TrackableCampaign
     */
    public function findCurrent();

    /**
     * @param $campaignId
     *
     * @return mixed
     */
    public function findById($campaignId);
}