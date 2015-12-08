<?php
/**
 * Contains class CookieImpressionsRepository
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
 * Class CookieImpressionsRepository
 */
class CookieImpressionsRepository implements ImpressionsRepositoryInterface
{
    const COOKIE_NAME = 'nci';
    const TOTAL_IMPRESSIONS_KEY = 't';
    const IS_SHOWING_ALLOWED_KEY = 'a';
    const CAMPAIGN_ID_KEY = 'c';

    const COOKIE_LIFETIME_DAYS = 365;

    /**
     * @inheritdoc
     *
     * @return Impressions
     */
    public function findImpressionsByCampaign(Campaign $campaign)
    {
        $data = $this->getData($campaign->getId());
        if (empty($data)) {
            return null;
        }

        return new Impressions($campaign, $this->getImpressionsForToday($data), $this->getTotalImpressions($data), $this->isShowingAllowed($data));
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
            self::IS_SHOWING_ALLOWED_KEY => $impressions->isShowingAllowed()
        ];

        $allData[$impressions->getCampaignId()] = $array;

        $_COOKIE[self::COOKIE_NAME] = $this->encode($allData);

        setcookie(self::COOKIE_NAME, $_COOKIE[self::COOKIE_NAME], $this->getCookieLifetime(), '/');
    }

    /**
     * @inheritdoc
     */
    private function getImpressionsForToday($data)
    {
        $todaysImpressionsKey = $this->getTodaysImpressionsKey($data);
        return isset($data[$todaysImpressionsKey]) ? $data[$todaysImpressionsKey] : 0;
    }

    /**
     * @inheritdoc
     */
    private function getTotalImpressions($data)
    {
        return isset($data[self::TOTAL_IMPRESSIONS_KEY]) ? $data[self::TOTAL_IMPRESSIONS_KEY] : 0;
    }

    /**
     * @inheritdoc
     */
    private function isShowingAllowed($data)
    {
        return isset($data[self::IS_SHOWING_ALLOWED_KEY]) ? $data[self::IS_SHOWING_ALLOWED_KEY] : true;
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
        if (!isset($_COOKIE[self::COOKIE_NAME])) {
            return [];
        }

        return $this->decode($_COOKIE[self::COOKIE_NAME], true);
    }

    /**
     * Returns the key in the impressions array which identifies the impressions for today.
     *
     * @return string
     */
    private function getTodaysImpressionsKey()
    {
        $today = new \DateTime();
        return $today->format('Y-m-d');
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