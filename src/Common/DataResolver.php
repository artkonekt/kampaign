<?php
/**
 * Contains class DataResolver
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 */

namespace Konekt\Kampaign\Common;


/**
 * Class DataResolver
 *
 */
class DataResolver
{

    const COOKIE_NAME = 'nci';
    const AD_ID_KEY = 'id';
    const DEBUG_MODE_KEY = 'kmpdbg';

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
    public function getAdId()
    {
        if (isset($_POST[self::AD_ID_KEY])) {
            $adId = $_POST[self::AD_ID_KEY];
        } elseif (isset($_GET[self::AD_ID_KEY])) {
            $adId = $_GET[self::AD_ID_KEY];
        }

        return $adId;
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

    public function isOnDemandDebugModeEnabled()
    {
        return isset($this->get[self::DEBUG_MODE_KEY]);
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