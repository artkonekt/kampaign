<?php
/**
 * Contains class DataResolver
 *
 * @package     Artkonekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Artkonekt\Kampaign\Common;


/**
 * Class DataResolver
 *
 * @package Artkonekt\Kampaign\Prototype
 */
class DataResolver
{

    const COOKIE_NAME = 'nci';
    const CAMPAIGN_ID_KEY = 'kampaigncid';
    const SUBSCRIBER_EMAIL_KEY = 'kampaignemail';

    /** @var array */
    private $get;

    /** @var array */
    private $post;
    /** @var array */
    private $cookie;

    /**
     * DataResolver constructor.
     *
     * @param array $get
     * @param array $post
     * @param array $cookie
     */
    public function __construct(array $get, array $post, array $cookie)
    {
        $this->get = $get;
        $this->post = $post;
        $this->cookie = $cookie;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->post[self::SUBSCRIBER_EMAIL_KEY];
    }

    /**
     * @return mixed
     */
    public function getCampaignId()
    {
        if (isset($_POST[self::CAMPAIGN_ID_KEY])) {
            $campaignId = $_POST[self::CAMPAIGN_ID_KEY];
        } elseif (isset($_GET[self::CAMPAIGN_ID_KEY])) {
            $campaignId = $_GET[self::CAMPAIGN_ID_KEY];
        }

        return $campaignId;
    }

    /**
     * @return bool
     */
    public function getImpressionsDataFromCookie()
    {
        if (!isset($this->cookie[self::COOKIE_NAME])) {
            return false;
        }

        return $this->cookie[self::COOKIE_NAME];
    }

    /**
     * @param string $name
     * @param string $value
     * @param int    $expire
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     */
    public function setCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        //TODO: This is only for the tests to work properly, find a solution for it
        $this->cookie[self::COOKIE_NAME] = $value;

        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

}