<?php
/**
 * Contains class FancyboxGenerator
 *
 * @package     Artkonekt\Kampaign\JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Artkonekt\Kampaign\Popup\JsGenerator;


use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;
use Artkonekt\Kampaign\Popup\PopupRenderer;
use Artkonekt\Kampaign\Popup\JsGenerator\Util\ScriptExtractor;

/**
 * Class FancyboxGenerator
 *
 * @package Artkonekt\Kampaign\JsGenerator
 */
class FancyboxGenerator implements JsGeneratorInterface, RendererAwareInterface
{
    const WRAPPER_DIV_ID = 'kampaign-popup-wrapper';

    /**
     * @var PopupRenderer
     */
    private $popupRenderer;

    /**
     * @param PopupRenderer $popupRenderer
     *
     * @return mixed|void
     */
    public function setPopupRenderer(PopupRenderer $popupRenderer)
    {
        $this->popupRenderer = $popupRenderer;
    }

    /**
     * Renders the script which contains the renderable popup's HTML code.
     *
     * Note: the HTML code can contain <script>...</script> tags, these are extracted and rendered separately.
     *
     * @param TrackableCampaignInterface $campaign
     * @param int                        $timeout
     *
     * @return string
     */
    public function getScript(TrackableCampaignInterface $campaign, $timeout)
    {
        $rawPopupContents = $this->popupRenderer->render($campaign);

        $scriptExtractor = new ScriptExtractor($rawPopupContents);

        $scripts = $scriptExtractor->getExtractedScripts();
        $contentWithoutScripts = $scriptExtractor->getHtmlWithoutScripts();

        $wrappedContent = sprintf('<div id="%s" style="display: none;">%s</div>', self::WRAPPER_DIV_ID, $contentWithoutScripts);
        $jsEscapedWrappedContent = $this->escapeToValidJs($wrappedContent);

        $js = sprintf('
        (function () {
            var tout = %s;
            $(document).ready(function() {
                $("body").append("' . $jsEscapedWrappedContent . '");
                setTimeout(function() {
                    if (!$.fancybox.isOpen) {
                        $.fancybox.open({
                            href: "#' . self::WRAPPER_DIV_ID . '",
                        });
                    }
                }, tout);
            })
        }());', $timeout * 1000);

        $scripts[] = "<script>$js</script>";

        return implode("\n", $scripts);
    }

    /**
     * @param $html
     *
     * @return string
     */
    private function escapeToValidJs($html)
    {
        return addslashes(str_replace("\n", '', $html));
    }
}