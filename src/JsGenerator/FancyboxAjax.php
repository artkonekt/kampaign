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

namespace Artkonekt\Kampaign\JsGenerator;


class FancyboxAjax implements JsGeneratorInterface
{

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
    public function getScript($campaignTrackingId, $url, $timeout)
    {
        $js = sprintf('
        (function () {
            var cid = "%s";
            var url = "%s";
            var tout = %s;
            $(document).ready(function () {
                setTimeout(function () {
                    if (!$.fancybox.isOpen) {
                        $.fancybox.open({
                            type: "ajax",
                            ajax: {
                                data: "cid=" + cid
                            },
                            href: url,
                        });
                    }
                }, tout);
            })
        }());', $campaignTrackingId, $url, $timeout * 1000);

        return $js;
    }
}