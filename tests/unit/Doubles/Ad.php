<?php
/**
 * Contains class Ad
 *
 * @package     Konekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-07
 * @version     2015-12-07
 */

namespace Konekt\Kampaign\Tests\Doubles;

use Konekt\Kampaign\Ad\TrackableAdInterface;
use DateTime;

/**
 * Represents a ad entity.
 */
class Ad implements TrackableAdInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var DateTime
     */
    private $startsOn;

    /**
     * @var DateTime
     */
    private $endsOn;

    /**
     * @var int
     */
    private $maxImpressions;

    /**
     * @var int
     */
    private $maxImpressionsPerDay;

    /**
     * Ad constructor.
     *
     * @param int      $id
     * @param string   $name
     * @param string   $description
     * @param bool     $isActive
     * @param DateTime $startsOn
     * @param DateTime $endsOn
     * @param int      $maxImpressions
     * @param int      $maxImpressionsPerDay
     */
    public function __construct(
        $id,
        $name,
        $description,
        $isActive,
        DateTime $startsOn,
        DateTime $endsOn,
        $maxImpressions,
        $maxImpressionsPerDay
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->isActive = $isActive;
        $this->startsOn = $startsOn;
        $this->endsOn = $endsOn;
        $this->maxImpressions = $maxImpressions;
        $this->maxImpressionsPerDay = $maxImpressionsPerDay;
    }

    /**
     * Returns the ID of the ad.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns whether the ad is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Activates the ad.
     */
    public function activate()
    {
        $this->isActive = true;
    }

    /**
     * Inactivates the ad.
     */
    public function inactivate()
    {
        $this->isActive = false;
    }

    /**
     * Returns the max impressions per day for a user aka. how many times the ad can be shown for a user a day.
     *
     * @return int
     */
    public function getMaxImpressionPerDay()
    {
        return $this->maxImpressionsPerDay;
    }

    /**
     * Returns the max overall impressions for a user aka. how many times the ad can be shown for a user.
     *
     * @return int
     */
    public function getMaxImpressions()
    {
        return $this->maxImpressions;
    }

    /**
     * Returns the tracking ID of the ad. It should be unique.
     * A common way to implement it is to just return the numeric ID of the ad entity.
     *
     * @return string
     */
    public function getTrackingId()
    {
        return $this->getId();
    }

    /**
     * Returns the contents to be rendered in the popup
     *
     * @return string
     */
    public function getContent()
    {
        return str_replace('#ad_no#', $this->getId(), file_get_contents(__DIR__ . '/../../../examples/template.php'));
    }

    /**
     * Returns the list id.
     *
     * @return mixed
     */
    public function getListId()
    {
        return null;
    }
}