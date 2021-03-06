<?php
/**
 * Contains interface AdRepositoryInterface
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 */

namespace Konekt\Kampaign\Ad;


/**
 * Interface AdRepositoryInterface
 *
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