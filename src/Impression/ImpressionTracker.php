<?php
/**
 * Contains class ImpressionTracker
 *
 * @package     Konekt\Kampaign\Impression
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 * @version     2015-12-15
 */

namespace Konekt\Kampaign\Impression;


use Konekt\Kampaign\Ad\TrackableAdInterface;

/**
 * Class ImpressionTracker
 *
 * @package Konekt\Kampaign\Impression
 */
class ImpressionTracker
{
    /** @var \Konekt\Kampaign\Impression\ImpressionRepositoryInterface */
    private $impressionsRepository;

    /**
     * ImpressionTracker constructor.
     *
     * @param $impressionsRepository
     */
    public function __construct(ImpressionRepositoryInterface $impressionsRepository)
    {
        $this->impressionsRepository = $impressionsRepository;
    }

    /**
     * Loads the impressions object for the ad from the repository, if it doesn't yet exists, it creates it.
     *
     * TODO: smelly. do the creation in the repo? (probably not a good idea)?
     *
     * @param TrackableAdInterface $ad
     *
     * @return Impressions
     */
    public function loadOrCreateFor(TrackableAdInterface $ad)
    {
        $impressions = $this->impressionsRepository->findImpressionsByAd($ad);

        if (!$impressions) {
            $impressions = new Impressions($ad, 0, 0);
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
     * Disables all future impressions of all ads.
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