<?php
/**
 * Contains class FancyboxAjax
 *
 * @package     ScriptGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Popup\JsGenerator;


use Artkonekt\Kampaign\Campaign\TrackableCampaign;
use Artkonekt\Kampaign\Common\DataResolver;

/**
 * Class FancyboxAjax
 *
 * @package Artkonekt\Kampaign\JsGenerator
 */
class FancyboxAjax implements JsGeneratorInterface
{
    /** @var string */
    private $url;

    /**
     * FancyboxAjax constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Returns the Javascript code which deals with the showing of the popup.
     *
     * We show a fancybox, which gets its content from the specified URL via AJAX, after a specified timeout.
     *
     * @param $campaignTrackingId The tracking ID of the campaign
     * @param $url The url which returns the content of our popup
     * @param $timeout The timeout after which the popup should appear in seconds.
     *
     * @return string
     */
    public function getScript(TrackableCampaign $campaign, $timeout)
    {
        $js = sprintf('
        (function () {
            var %s = "%s";
            var url = "%s";
            var tout = %s;
            $(document).ready(function () {
                setTimeout(function () {
                    if (!$.fancybox.isOpen) {
                        $.fancybox.open({
                            type: "ajax",
                            ajax: {
                                data: "%1$s=" + %1$s
                            },
                            href: url,
                        });
                    }
                }, tout);
            })
        }());', DataResolver::CAMPAIGN_ID_KEY, $campaign->getTrackingId(), $this->url, $timeout * 1000);

        return '<script>' . $js . '</script>';
    }
}