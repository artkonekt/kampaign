<?php
/**
 * Contains class PopupRenderer
 *
 * @package     Artkonekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign\Popup;


use Artkonekt\Kampaign\Campaign\TrackableCampaign;
use Artkonekt\Kampaign\Common\DataResolver;
use Artkonekt\Kampaign\Impression\ImpressionLoaderTrait;
use Artkonekt\Kampaign\Impression\ImpressionsOperator;
use Artkonekt\Kampaign\Popup\Transformer\TransformerInterface;

/**
 * Class PopupRenderer
 *
 * @package Artkonekt\Kampaign
 */
class PopupRenderer
{
    /**
     * @var ImpressionsOperator
     */
    private $impressionsOperator;

    /**
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * PopupRenderer constructor.
     *
     * @param ImpressionsOperator  $impressionsOperator
     * @param TransformerInterface $transformer
     */
    public function __construct(ImpressionsOperator $impressionsOperator, TransformerInterface $transformer = null)
    {
        $this->transformer = $transformer;
        $this->impressionsOperator = $impressionsOperator;
    }

    /**
     * @param TrackableCampaign $campaign
     *
     * @return bool|string
     */
    public function render(TrackableCampaign $campaign)
    {
        $impressions = $this->impressionsOperator->loadOrCreateFor($campaign);

        $template = '';

        if ($impressions->canBeIncreasedToday()) {
            $template = $this->renderFormTemplate($campaign);
        }

        if ($this->transformer) {
            $content = $this->transformer->transform($campaign, $impressions, $template);
        } else {
            $content = $template;
        }

        $this->impressionsOperator->increase($impressions);

        return $content;
    }

    /**
     * @param TrackableCampaign $campaign
     *
     * @return string
     */
    private function renderFormTemplate(TrackableCampaign $campaign)
    {
        $impressions = $this->impressionsOperator->loadOrCreateFor($campaign);

        $emailKey = DataResolver::SUBSCRIBER_EMAIL_KEY;
        $campaignIdKey = DataResolver::CAMPAIGN_ID_KEY;

        ob_start();
        include __DIR__ . '/templates/form.php';
        $content = ob_get_clean();

        return $content;
    }
}