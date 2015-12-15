<?php
/**
 * Contains class CampaignDirectorFactory
 *
 * @package     Artkonekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */

namespace Artkonekt\Kampaign;


use Artkonekt\Kampaign\Campaign\CampaignLoader;
use Artkonekt\Kampaign\Campaign\CampaignRepository;
use Artkonekt\Kampaign\Common\DataResolver;
use Artkonekt\Kampaign\Impression\CookieImpressionsRepository;
use Artkonekt\Kampaign\Impression\ImpressionsOperator;
use Artkonekt\Kampaign\Impression\ImpressionsRepositoryInterface;
use Artkonekt\Kampaign\Popup\JsGenerator\FancyboxGenerator;
use Artkonekt\Kampaign\Popup\JsGenerator\JsGeneratorInterface;
use Artkonekt\Kampaign\Popup\JsGenerator\RendererAwareGenerator;
use Artkonekt\Kampaign\Popup\PopupHandler;
use Artkonekt\Kampaign\Popup\PopupInitiator;
use Artkonekt\Kampaign\Popup\PopupRenderer;
use Artkonekt\Kampaign\Popup\Transformer\DebugTransformer;
use Artkonekt\Kampaign\Subscriber\MailchimpNewsletterSubscriber;
use Artkonekt\Kampaign\Subscriber\SubscriptionHandler;

/**
 * Class CampaignDirectorBuilder
 *
 * @package Artkonekt\Kampaign\Prototype
 */
class CampaignDirectorBuilder
{
    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * @var ImpressionsRepositoryInterface
     */
    private $impressionsRepository;

    /** @var */
    private $dataResolver;

    /** @var */
    private $popupRenderer;

    /** @var bool */
    private $isDebugModeEnabled = false;

    /** @var */
    private $popupInitiator;

    /** @var */
    private $jsGenerator;

    /**
     * CampaignDirectorBuilder constructor.
     *
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param array $get
     * @param array $post
     * @param array $cookie
     *
     * @return $this
     */
    public function setDataResolverContainers($get, $post, $cookie)
    {
        $this->dataResolver = new DataResolver($get, $post, $cookie);
        return $this;
    }

    /**
     * @param JsGeneratorInterface $jsGenerator
     *
     * @return $this
     */
    public function setJsGenerator(JsGeneratorInterface $jsGenerator)
    {
        $this->jsGenerator = $jsGenerator;
        return $this;
    }

    /**
     * @return $this
     */
    public function enableDebugMode()
    {
        $this->isDebugModeEnabled = true;
        return $this;
    }

    /**
     * @return DataResolver
     */
    private function getDataResolver()
    {
        if (!$this->dataResolver) {
            $this->dataResolver = new DataResolver($_GET, $_POST, $_COOKIE);
        }

        return $this->dataResolver;
    }

    /**
     * @return \Artkonekt\Kampaign\Impression\CookieImpressionsRepository
     */
    private function getImpressionsRepository()
    {
        if (!$this->impressionsRepository) {
            $this->impressionsRepository = new CookieImpressionsRepository($this->getDataResolver());
        }

        return $this->impressionsRepository;
    }

    /**
     * @return PopupInitiator
     */
    private function getPopupInitiator()
    {
        if (!$this->popupInitiator) {
            $this->popupInitiator = new PopupInitiator(
                $this->getImpressionsOperator(),
                $this->getJsGenerator(),
                $this->isDebugModeEnabled
            );
        }

        return $this->popupInitiator;
    }

    /**
     * @return PopupRenderer
     */
    private function getPopupRenderer()
    {
        if (!$this->popupRenderer) {

            $transformer = null;

            if ($this->isDebugModeEnabled) {
                $transformer = new DebugTransformer();
            }
            $this->popupRenderer = new PopupRenderer($this->getImpressionsOperator(), $transformer);
        }

        return $this->popupRenderer;
    }

    private function getImpressionsOperator()
    {
        return new ImpressionsOperator($this->getImpressionsRepository());
    }
    /**
     * @return JsGeneratorInterface
     */
    private function getJsGenerator()
    {
        if (!$this->jsGenerator) {
            $this->jsGenerator = new FancyboxGenerator();
        }

        if ($this->jsGenerator instanceof RendererAwareGenerator) {
            $this->jsGenerator->setPopupRenderer($this->getPopupRenderer());
        }

        return $this->jsGenerator;
    }

    private function getPopupHandler()
    {
        return new PopupHandler(
            $this->getCampaignLoader(),
            $this->getPopupInitiator(),
            $this->getPopupRenderer()
        );
    }

    private function getSubscriptionHandler()
    {
        return new SubscriptionHandler(
            $this->getCampaignLoader(),
            new MailchimpNewsletterSubscriber('apiK', 'someListId', false, []),
            $this->getImpressionsOperator(),
            $this->getDataResolver()
        );
    }

    private function getCampaignLoader()
    {
        return new CampaignLoader($this->campaignRepository, $this->getDataResolver());
    }

    /**
     * @return CampaignDirector
     */
    public function build()
    {
        return new CampaignDirector(
            $this->getPopupHandler(),
            $this->getSubscriptionHandler()
        );
    }
}