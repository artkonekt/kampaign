<?php
/**
 * Contains interface TransformerInterface
 *
 * @package     Konekt\Kampaign\Renderer
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Konekt\Kampaign\Popup\Transformer;


use Konekt\Kampaign\Campaign\TrackableCampaignInterface;
use Konekt\Kampaign\Impression\Impressions;

interface TransformerInterface
{
    /**
     * @param TrackableCampaignInterface                                                       $campaign
     * @param Impressions                                                                      $impressions
     * @param                                                                                  $template
     *
     * @return mixed
     */
    public function transform(TrackableCampaignInterface $campaign, Impressions $impressions, $template);
}