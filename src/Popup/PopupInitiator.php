<?php

namespace Konekt\Kampaign\Popup;

use Konekt\Kampaign\Campaign\TrackableCampaignInterface;
use Konekt\Kampaign\Impression\ImpressionLoaderTrait;
use Konekt\Kampaign\Impression\ImpressionsOperator;
use Konekt\Kampaign\Popup\JsGenerator\AjaxAwareGenerator;
use Konekt\Kampaign\Popup\JsGenerator\JsGeneratorInterface;

/**
 * Contains class CampaignPresenter
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */
class PopupInitiator
{
    /**
     * @var ImpressionsOperator
     */
    private $impressionsOperator;

    /**
     * @var JsGeneratorInterface
     */
    private $jsGenerator;

    /**
     * @var bool
     */
    private $isDebugModeEnabled;

    /**
     * CampaignPresenter constructor.
     *
     * @param ImpressionsOperator  $impressionsOperator
     * @param JsGeneratorInterface $jsGenerator
     * @param bool                 $enableDebugMode
     */
    public function __construct(ImpressionsOperator $impressionsOperator, JsGeneratorInterface $jsGenerator, $enableDebugMode = false)
    {
        $this->impressionsOperator = $impressionsOperator;
        $this->jsGenerator = $jsGenerator;
        $this->isDebugModeEnabled = $enableDebugMode;
    }

    /**
     * Returns the JQuery snippet which should be rendered in every the page the campaign popup should appear.
     *
     * @param TrackableCampaignInterface $campaign The campaign to be presented
     * @param int                        $timeout  How many seconds after page load should the popup appear
     *
     * @return string
     */
    public function getJsSnippet(TrackableCampaignInterface $campaign, $timeout)
    {
        $impressions = $this->impressionsOperator->loadOrCreateFor($campaign);

        if (($this->impressionsOperator->areImpressionsEnabled() && $impressions->canBeIncreasedToday()) || $this->isDebugModeEnabled) {
            return $this->jsGenerator->getScript($campaign, $timeout);
        } else {
            return '';
        }
    }

    /**
     * @return bool
     */
    public function hasInitiatedAjaxPopup()
    {
        return ($this->jsGenerator instanceof AjaxAwareGenerator);
    }
}