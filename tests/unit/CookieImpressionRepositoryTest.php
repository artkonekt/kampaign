<?php
/**
 * Contains class CookieImpressionsRepositoryTest
 *
 * @package     Konekt\Kampaign\Tests
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-08
 * @version     2015-12-08
 */

namespace Konekt\Kampaign\Tests;


use Konekt\Kampaign\Ad\TrackableAdInterface;
use Konekt\Kampaign\Common\DataResolver;
use Konekt\Kampaign\Impression\CookieImpressionRepository;
use Konekt\Kampaign\Impression\Impressions;
use Konekt\Kampaign\Tests\Helper\Factory;
use PHPUnit_Framework_Error_Warning;
use PHPUnit_Framework_TestCase;

class CookieImpressionRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CookieImpressionRepository
     */
    private $repo;

    /**
     * Initialize the repo and clean the $_COOKIE superglobal before each test.
     */
    public function setUp()
    {
        parent::setUp();
        $_COOKIE = [];
        $this->repo = new CookieImpressionRepository(new DataResolver([], [], []));
    }

    /**
     * Saves an impression with the repo.
     *
     * The call to save() is wrapped in a try..catch block, because we also set a cookie in the save method, causing
     * phpunit to emit an exception.
     *
     * @param Impressions $impressions
     *
     * @throws \PHPUnit_Framework_Error_Warning If it is not caused by the header emitting setcookie() method.
     */
    private function save(Impressions $impressions)
    {
        try {
            $this->repo->save($impressions);
        } catch (PHPUnit_Framework_Error_Warning $e) {
            if (0 !== strpos($e->getMessage(), 'Cannot modify header information - headers already sent')) {
                throw $e;
            }
        }
    }

    private function findByAd(TrackableAdInterface $ad)
    {
        return $this->repo->findImpressionsByAd($ad);
    }


    public function testFindExistingImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 2, 567);

        $this->save($impressions);

        $impressions = $this->findByAd($impressions->getAd());

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());
        $this->assertEquals(567, $impressions->getAdTrackingId());
    }

    public function testFindExistingImpressionsWithShowingDisabled()
    {
        $impressions = Factory::cici(3, 10, 1, 2);

        $this->save($impressions);

        $impressions = $this->findByAd($impressions->getAd());

        $this->assertEquals(1, $impressions->getForToday());
        $this->assertEquals(2, $impressions->getTotal());
    }


    public function testFindNonExistingImpressionsReturnsNull()
    {
        $c = Factory::cci(3, 10);
        $impressions = $this->findByAd($c);

        $this->assertNull($impressions);
    }

    public function testSaveExistingImpressions()
    {
        $impressions = Factory::cici(3, 10, 1, 2);
        $this->save($impressions);

        $impressions = $this->findByAd($impressions->getAd());
        $impressions->increment();

        $this->save($impressions);

        $impressions = $this->findByAd($impressions->getAd());

        $this->assertEquals(2, $impressions->getForToday());
        $this->assertEquals(3, $impressions->getTotal());

    }

    public function testSaveNotExistingImpressions()
    {
        $adId = 555;
        $c = Factory::cci(3, 10, $adId);
        $this->assertNull($this->findByAd($c));

        $impressions = Factory::cici(3, 10, 6, 8, $adId);
        $this->save($impressions);

        $impressions = $this->findByAd($impressions->getAd());

        $this->assertEquals(6, $impressions->getForToday());
        $this->assertEquals(8, $impressions->getTotal());

    }

    public function testSaveSendsCookie()
    {
        $this->setExpectedException(
            'PHPUnit_Framework_Error_Warning', 'Cannot modify header information - headers already sent'
        );

        $impressions = Factory::cici(3, 10, 1, 2);
        $this->repo->save($impressions);
    }

    public function testSupportsMultipleAds()
    {
        $impressions1 = Factory::cici(3, 10, 6, 8, 1);
        $impressions2 = Factory::cici(3, 10, 6, 8, 2);

        $this->save($impressions1);
        $this->save($impressions2);

        $this->assertNotNull($this->findByAd($impressions1->getAd()));
        $this->assertNotNull($this->findByAd($impressions2->getAd()));
    }

    public function testMultipleAdsDataAreOk()
    {
        $i1 = Factory::cici(3, 10, 2, 8, 555);
        $i2 = Factory::cici(2, 8, 1, 3, 556);

        $this->save($i1);
        $this->save($i2);

        $impressions1 = $this->findByAd($i1->getAd());
        $impressions2 = $this->findByAd($i2->getAd());

        $this->assertEquals(2, $impressions1->getForToday());
        $this->assertEquals(8, $impressions1->getTotal());
        $this->assertEquals(555, $impressions1->getAdTrackingId());

        $this->assertEquals(1, $impressions2->getForToday());
        $this->assertEquals(3, $impressions2->getTotal());
        $this->assertEquals(556, $impressions2->getAdTrackingId());
    }
}