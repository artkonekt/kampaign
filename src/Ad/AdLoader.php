<?php
/**
 * Contains class AdLoader
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 */

namespace Konekt\Kampaign\Ad;

use Konekt\Kampaign\Common\DataResolver;

/**
 * Class AdLoader
 *
 */
class AdLoader
{
    /** @var AdRepositoryInterface */
    private $adRepository;

    /** @var DataResolver */
    private $dataResolver;

    /**
     * AdLoader constructor.
     *
     * @param $adRepository
     * @param $dataResolver
     */
    public function __construct(AdRepositoryInterface $adRepository, DataResolver $dataResolver)
    {
        $this->adRepository = $adRepository;
        $this->dataResolver = $dataResolver;
    }

    public function getCurrentTrackable()
    {
        return $this->adRepository->findCurrent();
    }

    public function getTracked()
    {
        $adId = $this->dataResolver->getAdId();
        return $this->adRepository->findById($adId);
    }
}