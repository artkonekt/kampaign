<?php
/**
 * Contains interface RendererAwareGenerator
 *
 * @package     Artkonekt\Kampaign\JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Artkonekt\Kampaign\Popup\JsGenerator;

use Artkonekt\Kampaign\Popup\PopupRenderer;

/**
 * Interface RendererAwareGenerator
 *
 * @package Artkonekt\Kampaign\JsGenerator
 */
interface RendererAwareGenerator
{
    /**
     * @param PopupRenderer $popupRenderer
     *
     * @return mixed
     */
    public function setPopupRenderer(PopupRenderer $popupRenderer);
}