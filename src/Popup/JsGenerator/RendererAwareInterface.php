<?php
/**
 * Contains interface RendererAwareInterface
 *
 * @package     Konekt\Kampaign\JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Konekt\Kampaign\Popup\JsGenerator;

use Konekt\Kampaign\Popup\PopupRenderer;

/**
 * Interface RendererAwareInterface
 *
 * @package Konekt\Kampaign\JsGenerator
 */
interface RendererAwareInterface
{
    /**
     * @param PopupRenderer $popupRenderer
     *
     * @return mixed
     */
    public function setPopupRenderer(PopupRenderer $popupRenderer);
}