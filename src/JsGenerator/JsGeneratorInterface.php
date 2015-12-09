<?php
/**
 * Contains interface JsGeneratorInterface
 *
 * @package     JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\JsGenerator;


interface JsGeneratorInterface
{
    /**
     * Returns the Javascript code which deals with the showing of the popup.
     *
     * @param $campaignTrackingId The tracking ID of the campaign
     * @param $url The url which returns the content of our popup
     * @param $timeout The timeout after which the popup should appear in seconds.
     *
     * @return string
     */
    public function getScript($campaignTrackingId, $url, $timeout);
}