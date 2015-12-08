<?php
/**
 * Contains class ImpressionsTest
 *
 * @package     Artkonekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Artkonekt\Kampaign\Tests;

use Artkonekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_TestCase;

class ImpressionsTest extends PHPUnit_Framework_TestCase
{
    public function testNotYetViewedCampaignHasRemainingImpressions()
    {
        $impressions = Factory::cici(3, 10, 0, 0);
        $this->assertTrue($impressions->hasRemainingForToday());
        $this->assertTrue($impressions->hasRemaining());
    }

    public function testHasRemainingForToday()
    {
        $impressions = Factory::cici(3, 10, 2, 2);
        $this->assertTrue($impressions->hasRemaining());
        $this->assertTrue($impressions->hasRemainingForToday());
    }

    public function testHasRemainingForToday2()
    {
        $impressions = Factory::cici(3, 10, 2, 8);
        $this->assertTrue($impressions->hasRemainingForToday());
        $this->assertTrue($impressions->hasRemaining());
    }

    public function testDoesntHaveRemainingForToday()
    {
        $impressions = Factory::cici(3, 10, 3, 3);
        $this->assertTrue($impressions->hasRemaining());
        $this->assertFalse($impressions->hasRemainingForToday());
    }

    public function testDoesntHaveRemainingForToday2()
    {
        $impressions = Factory::cici(3, 10, 3, 8);
        $this->assertTrue($impressions->hasRemaining());
        $this->assertFalse($impressions->hasRemainingForToday());
    }

    public function testDoesntHaveRemainingIfTotalIsReached()
    {
        $impressions = Factory::cici(3, 10, 1, 10);
        $this->assertFalse($impressions->hasRemaining());
    }

    public function testDoesntHaveRemainingForTodayIfTotalIsReached()
    {
        $impressions = Factory::cici(3, 10, 1, 10);
        $this->assertFalse($impressions->hasRemainingForToday());
    }

    public function testShowingNotAllowedDoesntHaveRemaining()
    {
        $impressions = Factory::cici(3, 10, 1, 1, false);
        $this->assertFalse($impressions->hasRemainingForToday());
        $this->assertFalse($impressions->hasRemaining());
    }

    public function testDisableFutureImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 1);
        $this->assertTrue($impressions->hasRemainingForToday());
        $this->assertTrue($impressions->hasRemaining());

        $impressions->disable();

        $this->assertFalse($impressions->hasRemainingForToday());
        $this->assertFalse($impressions->hasRemaining());
    }

    public function testIncrementImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 2);

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());

        $impressions->increment();

        $this->assertEquals(2, $impressions->getForToday());
        $this->assertEquals(3, $impressions->getTotal());
    }

    public function testCampaignId()
    {
        $impressions = Factory::cici(3, 10, 1, 2);
        $this->assertEquals(1, $impressions->getCampaignId());
    }
}