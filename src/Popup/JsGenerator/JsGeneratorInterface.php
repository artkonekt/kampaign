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

namespace Artkonekt\Kampaign\Popup\JsGenerator;


use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;

interface JsGeneratorInterface
{
    /**
     * Returns the Javascript code which deals with the showing of the popup.
     *
     * @param TrackableCampaignInterface                     $campaign
     * @param                                                $timeout The timeout after which the popup should appear in seconds.
     *
     * @return string
     */
    public function getScript(TrackableCampaignInterface $campaign, $timeout);
}