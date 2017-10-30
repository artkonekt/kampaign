<?php
/**
 * Contains class ScriptExtractor
 *
 * @package     Konekt\Kampaign\JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Konekt\Kampaign\Popup\JsGenerator\Util;


/**
 * Class ScriptExtractor
 *
 * @package Konekt\Kampaign\JsGenerator
 */
class ScriptExtractor
{
    /** @var string */
    private $html;

    /** @var array */
    private $scripts = [];

    /** @var string */
    private $cleanedHtml;

    /**
     * ScriptExtractor constructor.
     *
     * @param $html
     */
    public function __construct($html)
    {
        $this->html = $html;
        $this->process();
    }

    /**
     * @return array
     */
    public function getExtractedScripts()
    {
        return $this->scripts;
    }

    /**
     * @return string
     */
    public function getHtmlWithoutScripts()
    {
        return $this->cleanedHtml;
    }

    /**
     *
     */
    private function process()
    {
        $dom = new \DOMDocument();

        @$dom->loadHTML($this->html);

        $this->scripts = [];

        while (($r = $dom->getElementsByTagName("script")) && $r->length) {
            $this->scripts[] = '<script>' . $r->item(0)->nodeValue .'</script>';
            $r->item(0)->parentNode->removeChild($r->item(0));
        }

        $this->cleanedHtml = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
    }
}