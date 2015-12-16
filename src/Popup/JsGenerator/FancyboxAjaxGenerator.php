<?php
/**
 * Contains class FancyboxAjaxGenerator
 *
 * @package     ScriptGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Popup\JsGenerator;


use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;
use Artkonekt\Kampaign\Common\DataResolver;

/**
 * Class FancyboxAjaxGenerator
 *
 * @package Artkonekt\Kampaign\JsGenerator
 */
class FancyboxAjaxGenerator implements JsGeneratorInterface, AjaxAwareGenerator
{
    /** @var string */
    private $url;

    /**
     * FancyboxAjaxGenerator constructor.
     *
     * @param $url The url from where the Ajax script will get the popup's contents.
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
     * @param TrackableCampaignInterface                     $campaign
     * @param                                                $timeout The timeout after which the popup should appear in seconds.
     *
     * @return string
     */
    public function getScript(TrackableCampaignInterface $campaign, $timeout)
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

        return sprintf("<script>%s</script>", $js);
    }
}