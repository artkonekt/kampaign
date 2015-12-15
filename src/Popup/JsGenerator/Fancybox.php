<?php
/**
 * Contains class Fancybox
 *
 * @package     Artkonekt\Kampaign\JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Artkonekt\Kampaign\Popup\JsGenerator;


use Artkonekt\Kampaign\Campaign\TrackableCampaign;
use Artkonekt\Kampaign\Popup\PopupRenderer;
use Artkonekt\Kampaign\Popup\JsGenerator\Util\ScriptExtractor;

/**
 * Class Fancybox
 *
 * @package Artkonekt\Kampaign\JsGenerator
 */
class Fancybox implements JsGeneratorInterface, RendererAwareGenerator
{
    /**
     * @var PopupRenderer
     */
    private $popupRenderer;

    /**
     * @param TrackableCampaign $campaign
     * @param int          $timeout
     *
     * @return string
     */
    public function getScript(TrackableCampaign $campaign, $timeout)
    {
        $popupContents = $this->popupRenderer->render($campaign);
        $scriptExtractor = new ScriptExtractor($popupContents);
        $contentsWithoutScripts = $scriptExtractor->getHtmlWithoutScripts();
        $scripts = $scriptExtractor->getExtractedScripts();

        $jsEscapedContent = addslashes(str_replace("\n", '', sprintf('<div id="kampaign-popup-wrapper" style="display: none;">%s</div>', $contentsWithoutScripts)));

        $js = sprintf('
        (function () {
            var tout = %s;
            $(document).ready(function () {
                $("body").append("' . $jsEscapedContent . '");
                setTimeout(function () {
                    if (!$.fancybox.isOpen) {
                        $.fancybox.open({
                            href: "#kampaign-popup-wrapper",
                        });
                    }
                }, tout);
            })
        }());', $timeout * 1000);

        $scripts[] = "<script>$js</script>";

        return implode("\n", $scripts);
    }

    /**
     * @param PopupRenderer $popupRenderer
     *
     * @return mixed|void
     */
    public function setPopupRenderer(PopupRenderer $popupRenderer)
    {
        $this->popupRenderer = $popupRenderer;
    }
}