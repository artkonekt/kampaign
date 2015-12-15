<?php
/**
 * Contains class PopupHandler
 *
 * @package     Artkonekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 * @version     2015-12-15
 */

namespace Artkonekt\Kampaign\Popup;

use Artkonekt\Kampaign\Campaign\CampaignLoader;

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
    public function getPopupInitializerJsSnippet()
    {
        $campaign = $this->campaignLoader->getCurrentTrackable();
        //TODO: get the timeout from some setting
        return $this->popupInitiator->getJsSnippet($campaign, 1);
    }

    /**
     * @return bool|string
     */
    public function renderPopup()
    {
        $campaign = $this->campaignLoader->getTracked();
        return $this->popupPresenter->render($campaign);
    }
}