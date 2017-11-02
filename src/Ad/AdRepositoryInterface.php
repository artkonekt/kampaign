<?php
/**
 * Contains interface AdRepositoryInterface
 *
 * @package     Konekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 */

namespace Konekt\Kampaign\Ad;


/**
 * Interface AdRepositoryInterface
 *
 * @package Konekt\Kampaign\Ad
 */
interface AdRepositoryInterface
{
    /**
     * @return TrackableAdInterface
     */
    public function findCurrent();

    /**
     * @param $adId
     *
     * @return mixed
     */
    public function findById($adId);
}