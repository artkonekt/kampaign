<?php
/**
 * Contains class UserImpressions
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 */

namespace Konekt\Kampaign\Impression;
use Konekt\Kampaign\Ad\TrackableAdInterface;

/**
 * Entity for impressions of a user for a specific ad.
 */
class Impressions
{
    /**
     * @var TrackableAdInterface
     */
    private $ad;

    /**
     * @var int
     */
    private $today;

    /**
     * @var int
     */
    private $total;

    /**
     * UserImpressions constructor.
     *
     * @param TrackableAdInterface $ad
     * @param int                  $today
     * @param int                  $total
     */
    public function __construct(TrackableAdInterface $ad, $today, $total)
    {
        $this->ad = $ad;
        $this->today = $today;
        $this->total = $total;
    }

    /**
     * Return the ID of the ad.
     *
     * @return int
     */
    public function getAdTrackingId()
    {
        return $this->ad->getTrackingId();
    }

    /**
     * Returns the ad for the impressions.
     *
     * @return \Konekt\Kampaign\Ad
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * Increments the impressions.
     */
    public function increment()
    {
        $this->total += 1;
        $this->today += 1;
    }

    /**
     * Return whether the impression for today can be increased. In other words it returns whether we can show the ad
     * today for our user.
     *
     * @return bool
     */
    public function canBeIncreasedToday()
    {
        return $this->hasRemainingForToday();
    }

    /**
     * Returns whether the ad still can be shown for the user today.
     *
     * @return bool
     */
    public function hasRemainingForToday()
    {
        return $this->getRemainingForToday() > 0;
    }

    /**
     * Returns whether the ad still can be shown for the user now or in the future.
     *
     * @return bool
     */
    public function hasRemaining()
    {
        return $this->getRemainingTotal() > 0;
    }

    /**
     * Returns the count of past impressions for today.
     *
     * @return int
     */
    public function getForToday()
    {
        return $this->today;
    }

    /**
     * Returns the total count of past impressions.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns the count of remaining impressions for the user for today aka. how many times the ad can still be
     * shown for the user today.
     *
     * @return int
     */
    private function getRemainingForToday()
    {
        $maxImpressionForToday = $this->ad->getMaxImpressionPerDay();
        $remainingForToday = $maxImpressionForToday - $this->getForToday();

        if ($remainingForToday > $this->getRemainingTotal()) {
            $remainingForToday = $this->getRemainingTotal();
        }

        return $remainingForToday;
    }

    /**
     * Returns how many overall impressions the user still has for the ad.
     *
     * @return int
     */
    private function getRemainingTotal()
    {
        return ($this->ad->getMaxImpressions() - $this->getTotal());
    }
}