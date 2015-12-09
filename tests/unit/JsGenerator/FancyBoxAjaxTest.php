<?php
/**
 * Contains class FancyBoxAjaxTest
 *
 * @package     unit\JsGenerator
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Tests;


use Artkonekt\Kampaign\JsGenerator\FancyboxAjax;
use PHPUnit_Framework_TestCase;

class FancyBoxAjaxTest extends PHPUnit_Framework_TestCase
{
    public function testScriptIsIIFE()
    {
        $generator = new FancyboxAjax();
        $js = $generator->getScript("trackingId", "URL", 1);

        $trimmedJs = trim($js);

        $this->assertStringStartsWith('(function () {', $trimmedJs);
        $this->assertStringEndsWith('());', $trimmedJs);
    }

    public function testJsVariablesAreOk()
    {
        $generator = new FancyboxAjax();

        $js = $generator->getScript("trackingId", "URL", 54);

        $this->assertContains('var cid = "trackingId"', $js);
        $this->assertContains('var url = "URL"', $js);
        $this->assertContains('var tout = 54000', $js);
    }
}