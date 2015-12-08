<?php
/**
 * Contains class UserImpressions
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
 * Entity for impressions of a user for a specific campaign.
 */
class Impressions
{
    /**
     * @var Campaign
     */
    private $campaign;

    /**
     * @var int
     */
    private $today;

    /**
     * @var int
     */
    private $total;

    /**
     * @var bool
     */
    private $isShowingAllowed;

    /**
     * UserImpressions constructor.
     *
     * @param Campaign $campaign
     * @param int      $today
     * @param int      $total
     * @param          $isShowingAllowed
     */
    public function __construct(Campaign $campaign, $today, $total, $isShowingAllowed)
    {
        $this->isShowingAllowed = $isShowingAllowed;
        $this->campaign = $campaign;
        $this->today = $today;
        $this->total = $total;
    }

    /**
     * Disables future impressions.
     */
    public function disable()
    {
        $this->isShowingAllowed = false;
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
     * Returns whether (future) showing of the campaign is allowed.
     *
     * @return bool
     */
    public function isShowingAllowed()
    {
        return $this->isShowingAllowed;
    }

    /**
     * Returns whether the campaign still can be shown for the user today.
     *
     * @return bool
     */
    public function hasRemainingForToday()
    {
        return ($this->isShowingAllowed && $this->getRemainingForToday() > 0);
    }

    /**
     * Returns whether the campaign still can be shown for the user now or in the future.
     *
     * @return bool
     */
    public function hasRemaining()
    {
        return ($this->isShowingAllowed && $this->getRemainingTotal() > 0);
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
     * Returns the count of remaining impressions for the user for today aka. how many times the campaign can still be
     * shown for the user today.
     *
     * @return int
     */
    private function getRemainingForToday()
    {
        $maxImpressionForToday = $this->campaign->getMaxImpressionPerDay();
        $remainingForToday = $maxImpressionForToday - $this->getForToday();

        if ($remainingForToday > $this->getRemainingTotal()) {
            $remainingForToday = $this->getRemainingTotal();
        }

        return $remainingForToday;
    }

    /**
     * Returns how many overall impressions the user still has for the campaign.
     *
     * @return int
     */
    private function getRemainingTotal()
    {
        return ($this->campaign->getMaxImpressions() - $this->getTotal());
    }
}