<?php
/**
 * Contains class ImpressionOperator
 *
 * @package     Artkonekt\Kampaign\Impression
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 * @version     2015-12-15
 */

namespace Artkonekt\Kampaign\Impression;


use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;

/**
 * Class ImpressionsOperator
 *
 * @package Artkonekt\Kampaign\Impression
 */
class ImpressionsOperator
{
    /** @var \Artkonekt\Kampaign\Impression\ImpressionsRepositoryInterface */
    private $impressionsRepository;

    /**
     * ImpressionsOperator constructor.
     *
     * @param $impressionsRepository
     */
    public function __construct(ImpressionsRepositoryInterface $impressionsRepository)
    {
        $this->impressionsRepository = $impressionsRepository;
    }

    /**
     * Loads the impressions object for the campaign from the repository, if it doesn't yet exists, it creates it.
     *
     * TODO: smelly. do the creation in the repo? (probably not a good idea)?
     *
     * @param TrackableCampaignInterface $campaign
     *
     * @return Impressions
     */
    public function loadOrCreateFor(TrackableCampaignInterface $campaign)
    {
        $impressions = $this->impressionsRepository->findImpressionsByCampaign($campaign);

        if (!$impressions) {
            $impressions = new Impressions($campaign, 0, 0, true);
            $this->impressionsRepository->save($impressions);
        }

        return $impressions;
    }

    /**
     * @param Impressions $impressions
     */
    public function increase(Impressions $impressions)
    {
        $impressions->increment();
        $this->impressionsRepository->save($impressions);
    }

    /**
     * Disables all future impressions of all campaigns.
     */
    public function disableFutureImpressions()
    {
        $this->impressionsRepository->disableFutureImpressions();
    }

    /**
     * Returns whether impressions are enabled.
     */
    public function areImpressionsEnabled()
    {
        return $this->impressionsRepository->areEnabled();
    }
}