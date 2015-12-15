<?php
/**
 * Contains class DebugTransformer
 *
 * @package     Artkonekt\Kampaign\Renderer
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Popup\Transformer;


use Artkonekt\Kampaign\Campaign\TrackableCampaign;
use Artkonekt\Kampaign\Impression\Impressions;

/**
 * Class DebugTransformer
 *
 * @package Artkonekt\Kampaign\Transformer
 */
class DebugTransformer implements TransformerInterface
{

    /**
     * @param TrackableCampaign                                                           $campaign
     * @param Impressions                                                                 $impressions
     * @param                                                                             $template
     *
     * @return mixed
     */
    public function transform(TrackableCampaign $campaign, Impressions $impressions, $template)
    {
        $visibilityText = $this->getVisibilityText($campaign, $impressions);
        return $visibilityText . '<hr>' . $template;
    }

    /**
     * @param TrackableCampaign $campaign
     * @param Impressions       $impressions
     *
     * @return string
     */
    private function getVisibilityText(TrackableCampaign $campaign, Impressions $impressions)
    {
        if ($impressions->canBeIncreasedToday()) {
            $text = 'Popup is showed in normal mode. This is impression ' . ($impressions->getForToday()+1) . ' out of ' . $campaign->getMaxImpressionPerDay() . ' for today';
        } elseif ($impressions->isShowingAllowed()) {
            $text = 'Popup is not showed in normal mode. The ' . $campaign->getMaxImpressionPerDay() . ' impressions for today  were exhausted.';
        } else {
            $text = 'Popup is not showed in normal mode. Impressions were disabled';
        }

        return sprintf('<div style="background-color: red; color: black; padding: 10px;"><b>DEBUG INFO</b>: %s</div>', $text);
    }
}