<?php
/**
 * Contains class CookieSimulator
 *
 * @package     unit\Helper
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Artkonekt\Kampaign\Tests\Helper;


use Artkonekt\Kampaign\CookieImpressionsRepository;

class CookieSimulator
{
    /**
     * Sets data in the superglobal $_COOKIE array for impressions.
     *
     * @param int  $todaysImpressions
     * @param int  $totalImpressions
     * @param bool $isShowingAllowed
     */
    public static function setCookie($todaysImpressions, $totalImpressions, $isShowingAllowed = true)
    {
        $array = [
            '2015-12-08' => $todaysImpressions,
            CookieImpressionsRepository::TOTAL_IMPRESSIONS_KEY => $totalImpressions,
            CookieImpressionsRepository::IS_SHOWING_ALLOWED_KEY => $isShowingAllowed
        ];
        $_COOKIE[CookieImpressionsRepository::COOKIE_NAME] = json_encode($array);
    }

    public static function emptyCookies()
    {
        $_COOKIE = [];
    }
}