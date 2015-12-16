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


use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;
use Artkonekt\Kampaign\Impression\ImpressionLoaderTrait;
use Artkonekt\Kampaign\Impression\ImpressionsOperator;
use Artkonekt\Kampaign\Popup\Transformer\NewsletterFormTransformer;
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
     * @var NewsletterFormTransformer
     */
    private $newsletterFormTransformer;

    /**
     * PopupRenderer constructor.
     *
     * @param ImpressionsOperator       $impressionsOperator
     * @param NewsletterFormTransformer $newsletterFormTransformer
     * @param TransformerInterface      $transformer
     */
    public function __construct(
        ImpressionsOperator $impressionsOperator,
        NewsletterFormTransformer $newsletterFormTransformer,
        TransformerInterface $transformer = null)
    {
        $this->transformer = $transformer;
        $this->newsletterFormTransformer = $newsletterFormTransformer;
        $this->impressionsOperator = $impressionsOperator;

    }

    /**
     * @param TrackableCampaignInterface $campaign
     *
     * @return bool|string
     */
    public function render(TrackableCampaignInterface $campaign)
    {
        $impressions = $this->impressionsOperator->loadOrCreateFor($campaign);

        $template = '';

        if ($impressions->canBeIncreasedToday()) {
            $template = $this->newsletterFormTransformer->transform($campaign, $impressions, $campaign->getContent());
        }

        if ($this->transformer) {
            $content = $this->transformer->transform($campaign, $impressions, $template);
        } else {
            $content = $template;
        }

        $this->impressionsOperator->increase($impressions);

        return $content;
    }
}