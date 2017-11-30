<?php
/**
 * Contains interface ImpressionsRepositoryInterface
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 */

namespace Konekt\Kampaign\Impression;
use Konekt\Kampaign\Ad\TrackableAdInterface;


/**
 * Interface ImpressionsRepositoryInterface.
 *
 */
interface ImpressionRepositoryInterface
{
    /**
     * Returns the impressions for a ad.
     *
     * @param TrackableAdInterface $ad
     *
     * @return Impressions
     */
    public function findImpressionsByAd(TrackableAdInterface $ad);

    /**
     * Saves an Impressions instance.
     *
     * @param Impressions $impressions
     */
    public function save(Impressions $impressions);

    /**
     * Returns whether impressions are globally enabled for any ads.
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