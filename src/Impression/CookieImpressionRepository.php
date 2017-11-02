<?php
/**
 * Contains class CookieImpressionsRepository
 *
 * @package     Konekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Konekt\Kampaign\Impression;

use Konekt\Kampaign\Campaign\TrackableCampaignInterface;
use Konekt\Kampaign\Common\DataResolver;

/**
 * Class CookieImpressionsRepository
 */
class CookieImpressionRepository implements ImpressionRepositoryInterface
{
    const TOTAL_IMPRESSIONS_KEY = 't';
    const IS_SHOWING_ALLOWED_KEY = 'a';
    const CAMPAIGN_ID_KEY = 'c';

    const COOKIE_LIFETIME_DAYS = 365;

    private $dataResolver;

    /**
     * CookieImpressionsRepository constructor.
     *
     * @param DataResolver $dataResolver
     */
    public function __construct(DataResolver $dataResolver)
    {
        $this->dataResolver = $dataResolver;
    }

    /**
     * @inheritdoc
     *
     * @return Impressions
     */
    public function findImpressionsByCampaign(TrackableCampaignInterface $campaign)
    {
        $data = $this->getData($campaign->getTrackingId());
        if (empty($data)) {
            return null;
        }

        return new Impressions($campaign, $this->getImpressionsForToday($data), $this->getTotalImpressions($data));
    }

    /**
     * @inheritdoc
     */
    public function save(Impressions $impressions)
    {
        $allData = $this->getAllData();

        $array = [
            $this->getTodaysImpressionsKey() => $impressions->getForToday(),
            self::TOTAL_IMPRESSIONS_KEY => $impressions->getTotal(),
        ];

        $allData[$impressions->getCampaignTrackingId()] = $array;

        $encodedCookieData = $this->encode($allData);

        $this->dataResolver->setCookie(DataResolver::COOKIE_NAME, $encodedCookieData, time() + $this->getCookieLifetime(), '/');
    }

    /**
     * Returns whether impressions are globally enabled for any campaigns.
     *
     * @return mixed
     */
    public function areEnabled()
    {
        $allData = $this->getAllData();
        return isset($allData[self::IS_SHOWING_ALLOWED_KEY]) ? ((bool)$allData[self::IS_SHOWING_ALLOWED_KEY]) : true;
    }

    /**
     * Disables all impressions in the future.
     *
     * @return mixed
     */
    public function disableFutureImpressions()
    {
        $allData = $this->getAllData();
        $allData[self::IS_SHOWING_ALLOWED_KEY] = 0;
        $encodedCookieData = $this->encode($allData);
        $this->dataResolver->setCookie(DataResolver::COOKIE_NAME, $encodedCookieData, time() + $this->getCookieLifetime(), '/');
    }

    /**
     * Returns the impressions for today from the data.
     *
     * @param array $data
     *
     * @return int
     */
    private function getImpressionsForToday($data)
    {
        $todaysImpressionsKey = $this->getTodaysImpressionsKey($data);
        return isset($data[$todaysImpressionsKey]) ? $data[$todaysImpressionsKey] : 0;
    }

    /**
     * Returns the total impressions from the data.
     *
     * @param array $data
     *
     * @return int
     */
    private function getTotalImpressions($data)
    {
        return isset($data[self::TOTAL_IMPRESSIONS_KEY]) ? $data[self::TOTAL_IMPRESSIONS_KEY] : 0;
    }

    /**
     * Returns an array which contains the data with the impressions data for a specific campaign.
     *
     * @param $campaignId
     *
     * @return array
     */
    private function getData($campaignId)
    {
        $allData = $this->getAllData();

        if (!array_key_exists($campaignId, $allData)) {
            return [];
        }

        return $allData[$campaignId];
    }

    /**
     * Returns the array from the cookie which contains the impressions data for all campaigns.
     *
     * @return array
     */
    private function getAllData()
    {
        $impressionsData = $this->dataResolver->getImpressionsDataFromCookie();
        if (!$impressionsData) {
            return [];
        }

        return $this->decode($impressionsData, true);
    }

    /**
     * Returns the key in the impressions array which identifies the impressions for today.
     *
     * @return string
     */
    private function getTodaysImpressionsKey()
    {
        $today = new \DateTime();
        return $today->format('m-d');
    }

    /**
     * Returns the cookie lifetime in seconds.
     *
     * @return int
     */
    private function getCookieLifetime()
    {
        return self::COOKIE_LIFETIME_DAYS * 24 * 60 * 60;
    }

    /**
     * Encodes the cookie data.
     *
     * @param $data
     *
     * @return string
     */
    private function encode($data)
    {
        return base64_encode(json_encode($data));
    }

    /**
     * Decodes the cookie data.
     *
     * @param $data
     *
     * @return mixed
     */
    private function decode($data)
    {
        return json_decode(base64_decode($data), true);
    }
}