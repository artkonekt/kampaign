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


use Artkonekt\Kampaign\Campaign;
use Artkonekt\Kampaign\CookieImpressionsRepository;
use Artkonekt\Kampaign\Impressions;
use Artkonekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_Error_Warning;
use PHPUnit_Framework_TestCase;

class CookieImpressionsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CookieImpressionsRepository
     */
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $_COOKIE = [];
        $this->repo = new CookieImpressionsRepository();
    }

    private function save(Impressions $impressions)
    {
        try {
            $this->repo->save($impressions);
        } catch (PHPUnit_Framework_Error_Warning $e) {}
    }

    private function findByCampaign(Campaign $campaign)
    {
        return $this->repo->findImpressionsByCampaign($campaign);
    }


    public function testFindExistingImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 2, true, 567);

        $this->save($impressions);

        $impressions = $this->findByCampaign($impressions->getCampaign());

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());
        $this->assertTrue($impressions->isShowingAllowed());
        $this->assertEquals(567, $impressions->getCampaignId());
    }

    public function testFindExistingImpressionsWithShowingDisabled()
    {
        $impressions = Factory::cici(3, 10, 1, 2, false);

        $this->save($impressions);

        $impressions = $this->findByCampaign($impressions->getCampaign());

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());
        $this->assertFalse($impressions->isShowingAllowed());
    }


    public function testFindNonExistingImpressionsReturnsNull()
    {
        $c = Factory::cci(3, 10);
        $impressions = $this->findByCampaign($c);

        $this->assertNull($impressions);
    }

    public function testSaveExistingImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 2);
        $this->save($impressions);

        $impressions = $this->findByCampaign($impressions->getCampaign());
        $impressions->increment();

        $this->save($impressions);

        $impressions = $this->findByCampaign($impressions->getCampaign());

        $this->assertEquals(2, $impressions->getForToday());
        $this->assertEquals(3, $impressions->getTotal());

    }

    public function testSaveNotExistingImpressions()
    {
        $campaignId = 555;
        $c = Factory::cci(3, 10, $campaignId);
        $this->assertNull($this->findByCampaign($c));

        $impressions = Factory::cici(3, 10, 6, 8, true, $campaignId);
        $this->save($impressions);

        $impressions = $this->findByCampaign($impressions->getCampaign());

        $this->assertEquals(6, $impressions->getForToday());
        $this->assertEquals(8, $impressions->getTotal());
        $this->assertTrue($impressions->isShowingAllowed());

    }

    public function testSaveSendsCookie()
    {
        $this->setExpectedException(
            'PHPUnit_Framework_Error_Warning', 'Cannot modify header information - headers already sent'
        );

        $impressions = Factory::cici(3, 10, 1, 2);
        $this->repo->save($impressions);
    }

    public function testSupportsMultipleCampaigns()
    {
        $impressions1 = Factory::cici(3, 10, 6, 8, true, 1);
        $impressions2 = Factory::cici(3, 10, 6, 8, true, 2);

        $this->save($impressions1);
        $this->save($impressions2);

        $this->assertNotNull($this->findByCampaign($impressions1->getCampaign()));
        $this->assertNotNull($this->findByCampaign($impressions2->getCampaign()));
    }

    public function testMultipleCampaignsDataAreOk()
    {
        $i1 = Factory::cici(3, 10, 2, 8, false, 555);
        $i2 = Factory::cici(2, 8, 1, 3, true, 556);

        $this->save($i1);
        $this->save($i2);

        $impressions1 = $this->findByCampaign($i1->getCampaign());
        $impressions2 = $this->findByCampaign($i2->getCampaign());

        $this->assertEquals(2, $impressions1->getForToday());
        $this->assertEquals(8, $impressions1->getTotal());
        $this->assertFalse($impressions1->isShowingAllowed());
        $this->assertEquals(555, $impressions1->getCampaignId());

        $this->assertEquals(1, $impressions2->getForToday());
        $this->assertEquals(3, $impressions2->getTotal());
        $this->assertTrue($impressions2->isShowingAllowed());
        $this->assertEquals(556, $impressions2->getCampaignId());
    }
}