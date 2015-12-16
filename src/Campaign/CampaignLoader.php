<?php
/**
 * Contains class CampaignLoader
 *
 * @package     Artkonekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 * @version     2015-12-15
 */

namespace Artkonekt\Kampaign\Campaign;

use Artkonekt\Kampaign\Common\DataResolver;

/**
 * Class CampaignLoader
 *
 * @package Artkonekt\Kampaign\Prototype
 */
class CampaignLoader
{
    /** @var CampaignRepositoryInterface */
    private $campaignRepository;

    /** @var DataResolver */
    private $dataResolver;

    /**
     * CampaignLoader constructor.
     *
     * @param $campaignRepository
     * @param $dataResolver
     */
    public function __construct(CampaignRepositoryInterface $campaignRepository, DataResolver $dataResolver)
    {
        $this->campaignRepository = $campaignRepository;
        $this->dataResolver = $dataResolver;
    }

    public function getCurrentTrackable()
    {
        return $this->campaignRepository->findCurrent();
    }

    public function getTracked()
    {
        $campaignId = $this->dataResolver->getCampaignId();
        return $this->campaignRepository->findById($campaignId);
    }
}