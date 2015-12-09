<?php
/**
 * Contains trait ImpressionLoaderTrait
 *
 * @package     Artkonekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign;

/**
 * TODO: Find a better solution than using traits.
 */
trait ImpressionLoaderTrait
{
    /**
     * Loads the impressions object for the campaign from the repository, if it doesn't yet exists, it creates it.
     *
     * TODO: separate into its own component, or do the creation in the repo (probably not a good idea)?
     *
     * @param \Artkonekt\Kampaign\TrackableCampaign $campaign
     *
     * @return \Artkonekt\Kampaign\Impressions
     */
    private function loadImpressionsFor(TrackableCampaign $campaign)
    {
        $impressions = $this->impressionsRepository->findImpressionsByCampaign($campaign);

        if (!$impressions) {
            $impressions = new Impressions($campaign, 0, 0, true);
            $this->impressionsRepository->save($impressions);
        }

        return $impressions;
    }
}