<?php
/**
 * Contains class CookieImpressionsRepositoryTest
 *
 * @package     Artkonekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Artkonekt\Kampaign\Tests;


use Artkonekt\Kampaign\CookieImpressionsRepository;
use Artkonekt\Kampaign\Impressions;
use Artkonekt\Kampaign\Tests\Helper\CookieSimulator;
use Artkonekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_Error_Warning;
use PHPUnit_Framework_TestCase;

class CookieImpressionsRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        CookieSimulator::emptyCookies();
    }

    public function testFindExistingImpressions()
    {
        $c = Factory::cci(3, 10);
        CookieSimulator::setCookie(1, 2);
        $repository = new CookieImpressionsRepository($c);

        $impressions = $repository->findImpressionsForCampaign($c);

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());
        $this->assertTrue($impressions->isShowingAllowed());
    }

    public function testFindExistingImpressionsWithShowingDisabled()
    {
        $c = Factory::cci(3, 10);
        CookieSimulator::setCookie(1, 2, false);
        $repository = new CookieImpressionsRepository($c);

        $impressions = $repository->findImpressionsForCampaign($c);

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());
        $this->assertFalse($impressions->isShowingAllowed());
    }

    public function testFindNonExistingImpressionsReturnsNull()
    {
        $c = Factory::cci(3, 10);
        $repository = new CookieImpressionsRepository($c);

        $this->assertNull($repository->findImpressionsForCampaign($c));
    }

    public function testSaveExistingImpressions()
    {
        $c = Factory::cci(3, 10);
        CookieSimulator::setCookie(1, 2);
        $repository = new CookieImpressionsRepository($c);

        $impressions = $repository->findImpressionsForCampaign($c);
        $impressions->increment();

        try {
            $repository->save($impressions);
        } catch (PHPUnit_Framework_Error_Warning $e) {}

        $impressions = $repository->findImpressionsForCampaign($c);

        $this->assertEquals(2, $impressions->getForToday());
        $this->assertEquals(3, $impressions->getTotal());

    }

    public function testSaveNotExistingImpressions()
    {
        $c = Factory::cci(3, 10);
        $repository = new CookieImpressionsRepository($c);

        $impressions = new Impressions($c, 6, 8, true);

        try {
            $repository->save($impressions);
        } catch (PHPUnit_Framework_Error_Warning $e) {}

        $impressions = $repository->findImpressionsForCampaign($c);

        $this->assertEquals(6, $impressions->getForToday());
        $this->assertEquals(8, $impressions->getTotal());
        $this->assertTrue($impressions->isShowingAllowed());

    }

    public function testSaveSendsCookie()
    {
        $this->setExpectedException(
            'PHPUnit_Framework_Error_Warning', 'Cannot modify header information - headers already sent'
        );

        $c = Factory::cci(3, 10);
        $repository = new CookieImpressionsRepository($c);

        $impressions = new Impressions($c, 6, 8, true);
        $repository->save($impressions);
    }
}