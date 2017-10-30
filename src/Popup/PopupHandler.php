<?php
/**
 * Contains class PopupHandler
 *
 * @package     Konekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 * @version     2015-12-15
 */

namespace Konekt\Kampaign\Popup;

use Konekt\Kampaign\Campaign\CampaignLoader;

class PopupHandler
{
    /**
     * @var CampaignLoader
     */
    private $campaignLoader;

    /**
     * @var PopupInitiator
     */
    private $popupInitiator;

    /**
     * @var PopupRenderer
     */
    private $popupPresenter;

    /**
     * PopupHandler constructor.
     *
     * @param CampaignLoader $campaignLoader
     * @param PopupInitiator $popupInitiator
     * @param PopupRenderer  $popupPresenter
     */
    public function __construct(CampaignLoader $campaignLoader, PopupInitiator $popupInitiator, PopupRenderer $popupPresenter)
    {
        $this->campaignLoader = $campaignLoader;
        $this->popupInitiator = $popupInitiator;
        $this->popupPresenter = $popupPresenter;
    }


    /**
     * Returns the JQuery snippet which should be rendered in every the page the campaign popup should appear.
     *
     * @return string
     */
    public function getPopupInitializerJsSnippet($timeout)
    {
        $campaign = $this->campaignLoader->getCurrentTrackable();

        //If no campaign is to be tracked, we have no js snippet.
        if (!$campaign) {
            return '';
        }

        return $this->popupInitiator->getJsSnippet($campaign, $timeout);
    }

    /**
     * @return string
     * @throws PopupException
     */
    public function renderAjaxPopup()
    {
        //TODO: check if request is an ajax call?

        if (!$this->popupInitiator->hasInitiatedAjaxPopup()) {
            throw new PopupException('Rendering of the popup via Ajax is not allowed. The PopupInitiator generated a script without an Ajax call.');
        }

        $campaign = $this->campaignLoader->getTracked();
        return $this->popupPresenter->render($campaign);
    }
}