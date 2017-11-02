<?php
/**
 * Contains class ImpressionsTest
 *
 * @package     Konekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Konekt\Kampaign\Tests;

use Konekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_TestCase;

class ImpressionsTest extends PHPUnit_Framework_TestCase
{
    public function testNotYetViewedAdHasRemainingImpressions()
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

    public function testIncrementImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 2);

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());

        $impressions->increment();

        $this->assertEquals(2, $impressions->getForToday());
        $this->assertEquals(3, $impressions->getTotal());
    }

    public function testAdTrackingId()
    {
        $impressions = Factory::cici(3, 10, 1, 2);
        $this->assertEquals(1, $impressions->getAdTrackingId());
    }

    public function testImpressionsCanBeIncreasedToday()
    {
        $impressions = Factory::cici(3, 10, 1, 2);
        $this->assertTrue($impressions->canBeIncreasedToday());
    }

    public function testImpressionsCannotBeIncreasedTodayIfHasNoRemainingImpressionsForToday()
    {
        $impressions = Factory::cici(3, 10, 3, 3);
        $this->assertFalse($impressions->canBeIncreasedToday());
    }
}