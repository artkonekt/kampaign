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

namespace Konekt\Kampaign\Tests;


use Konekt\Kampaign\Common\DataResolver;
use Konekt\Kampaign\Popup\JsGenerator\FancyboxAjaxGenerator;
use Konekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_TestCase;

class FancyBoxAjaxTest extends PHPUnit_Framework_TestCase
{
    public function testScriptIsIIFE()
    {
        $c = Factory::cc();
        $generator = new FancyboxAjaxGenerator('http://test.lcl/testpopup.php');
        $js = $generator->getScript($c, "URL", 1);

        $trimmedJs = trim($js);

        $this->assertNotEquals(false, strpos($trimmedJs, '(function () {'));
        $this->assertNotEquals(false, strpos($trimmedJs, '());'));
    }

    public function testJsVariablesAreOk()
    {
        $c = Factory::cci(10, 100, 27);
        $generator = new FancyboxAjaxGenerator('http://test.lcl/testpopup.php');

        $js = $generator->getScript($c, 54);

        $this->assertContains('var ' . DataResolver::CAMPAIGN_ID_KEY . ' = "27"', $js);
        $this->assertContains('var url = "http://test.lcl/testpopup.php"', $js);
        $this->assertContains('var tout = 54000', $js);
    }
}