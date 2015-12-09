<?php
/**
 * Contains interface TransformerInterface
 *
 * @package     Artkonekt\Kampaign\Renderer
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Transformer;


use Artkonekt\Kampaign\Impressions;
use Artkonekt\Kampaign\TrackableCampaign;

interface TransformerInterface
{
    /**
     * @param TrackableCampaign                                                                $campaign
     * @param Impressions                                                                      $impressions
     * @param                                                                                  $template
     *
     * @return mixed
     */
    public function transform(TrackableCampaign $campaign, Impressions $impressions, $template);
}