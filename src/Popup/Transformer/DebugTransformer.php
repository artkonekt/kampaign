<?php
/**
 * Contains class DebugTransformer
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
use Konekt\Kampaign\Impression\ImpressionsOperator;

/**
 * Class DebugTransformer
 *
 * @package Konekt\Kampaign\Transformer
 */
class DebugTransformer implements TransformerInterface
{
    /**
     * @var ImpressionsOperator
     */
    private $impressionsOperator;

    /**
     * DebugTransformer constructor.
     *
     * @param ImpressionsOperator $impressionsOperator
     */
    public function __construct(ImpressionsOperator $impressionsOperator)
    {
        $this->impressionsOperator = $impressionsOperator;
    }

    /**
     * @param TrackableCampaignInterface                                                  $campaign
     * @param Impressions                                                                 $impressions
     * @param                                                                             $template
     *
     * @return mixed
     */
    public function transform(TrackableCampaignInterface $campaign, Impressions $impressions, $template)
    {
        $visibilityText = $this->getVisibilityText($campaign, $impressions);
        return $visibilityText . '<hr>' . $template;
    }

    /**
     * @param TrackableCampaignInterface $campaign
     * @param Impressions                $impressions
     *
     * @return string
     */
    private function getVisibilityText(TrackableCampaignInterface $campaign, Impressions $impressions)
    {
        if ($this->impressionsOperator->areImpressionsEnabled()) {
            if ($impressions->canBeIncreasedToday()) {
                $text = 'Popup is showed in normal mode. This is impression ' . ($impressions->getForToday()+1) . ' out of ' . $campaign->getMaxImpressionPerDay() . ' for today';
            } else {
                $text = 'Popup is not showed in normal mode. The ' . $campaign->getMaxImpressionPerDay() . ' impressions for today  were exhausted.';
            }
        }  else {
            $text = 'Popup is not showed in normal mode. All impressions were disabled.';
        }

        ob_start();
        require_once(__DIR__ . '/../templates/debug.php');
        $content = ob_get_clean();
        return $content;
    }
}