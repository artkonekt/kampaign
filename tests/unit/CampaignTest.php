<?php

/**
 * Contains class CampaignTest
 *
 * @package     Konekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-07
 * @version     2015-12-07
 */

namespace Konekt\Kampaign\Tests;

use Konekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_TestCase;

class CampaignTest extends PHPUnit_Framework_TestCase
{
    public function testCampaignActivation()
    {
        $c = Factory::cc(true);
        $this->assertTrue($c->isActive());

        $c->inactivate();
        $this->assertFalse($c->isActive());
    }

    public function testCampaignInactivation()
    {
        $c = Factory::cc(false);
        $this->assertFalse($c->isActive());

        $c->activate();
        $this->assertTrue($c->isActive());
    }
}