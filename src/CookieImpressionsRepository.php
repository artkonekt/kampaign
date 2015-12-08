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

    const COOKIE_LIFETIME_DAYS = 365;

    /**
     * @inheritdoc
     *
     * @return Impressions
     */
    public function findImpressionsForCampaign(Campaign $campaign)
    {
        if (empty($this->getData())) {
            return null;
        }

        return new Impressions($campaign, $this->getImpressionsForToday(), $this->getTotalImpressions(), $this->isShowingAllowed());
    }

    /**
     * @inheritdoc
     */
    public function save(Impressions $impressions)
    {
        $data = [
            $this->getTodaysImpressionsKey() => $impressions->getForToday(),
            self::TOTAL_IMPRESSIONS_KEY => $impressions->getTotal(),
            self::IS_SHOWING_ALLOWED_KEY => $impressions->isShowingAllowed()
        ];

        $_COOKIE[self::COOKIE_NAME] = json_encode($data);

        setcookie(self::COOKIE_NAME, $_COOKIE[self::COOKIE_NAME], $this->getCookieLifetime(), '/');
    }

    /**
     * @inheritdoc
     */
    private function getImpressionsForToday()
    {
        $data = $this->getData();
        $todaysImpressionsKey = $this->getTodaysImpressionsKey();
        return isset($data[$todaysImpressionsKey]) ? $data[$todaysImpressionsKey] : 0;
    }

    /**
     * @inheritdoc
     */
    private function getTotalImpressions()
    {
        $data = $this->getData();
        return isset($data[self::TOTAL_IMPRESSIONS_KEY]) ? $data[self::TOTAL_IMPRESSIONS_KEY] : 0;
    }

    /**
     * @inheritdoc
     */
    private function isShowingAllowed()
    {
        $data = $this->getData();
        return isset($data[self::IS_SHOWING_ALLOWED_KEY]) ? $data[self::IS_SHOWING_ALLOWED_KEY] : true;
    }

    /**
     * Returns an array which contains the data with the impressions for today and the total impressions.
     *
     * @return array
     */
    private function getData()
    {
        return isset($_COOKIE[self::COOKIE_NAME]) ? json_decode($_COOKIE[self::COOKIE_NAME], true) : [];
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
}