<?php

namespace Artkonekt\Kampaign;
use Artkonekt\Kampaign\Popup\PopupHandler;
use Artkonekt\Kampaign\Subscriber\SubscriptionHandler;

/**
 * Class CampaignDirector
 *
 * @package Artkonekt\Kampaign
 */
class CampaignDirector
{
    /**
     * @var PopupHandler
     */
    private $popupHandler;

    /**
     * @var SubscriptionHandler
     */
    private $subscriptionHandler;

    /**
     * CampaignDirector constructor.
     *
     * @param PopupHandler                  $popupHandler
     * @param SubscriptionHandler           $subscriptionHandler
     */
    public function __construct(
        PopupHandler $popupHandler,
        SubscriptionHandler $subscriptionHandler
    )
    {
        $this->subscriptionHandler = $subscriptionHandler;
        $this->popupHandler = $popupHandler;
    }

    /**
     * Returns the JQuery snippet which should be rendered in every the page the campaign popup should appear.
     *
     * @return string
     */
    public function getPopupInitializerJsSnippet()
    {
        return $this->popupHandler->getPopupInitializerJsSnippet();
    }

    /**
     * @return bool|string
     */
    public function renderAjaxPopup()
    {
        return $this->popupHandler->renderAjaxPopup();
    }

    /**
     *
     */
    public function subscribeEmail()
    {
        $this->subscriptionHandler->subscribeEmail();
    }
}