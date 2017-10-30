<?php
/**
 * Contains interface CampaignRepositoryInterface
 *
 * @package     Konekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Konekt\Kampaign\Campaign;


/**
 * Interface CampaignRepositoryInterface
 *
 * @package Konekt\Kampaign\Campaign
 */
interface CampaignRepositoryInterface
{
    /**
     * @return TrackableCampaignInterface
     */
    public function findCurrent();

    /**
     * @param $campaignId
     *
     * @return mixed
     */
    public function findById($campaignId);
}