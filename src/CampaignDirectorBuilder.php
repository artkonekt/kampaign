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
use Artkonekt\Kampaign\Campaign\CampaignRepositoryInterface;
use Artkonekt\Kampaign\Common\DataResolver;
use Artkonekt\Kampaign\Impression\CookieImpressionsRepository;
use Artkonekt\Kampaign\Impression\ImpressionsOperator;
use Artkonekt\Kampaign\Impression\ImpressionsRepositoryInterface;
use Artkonekt\Kampaign\Popup\JsGenerator\FancyboxGenerator;
use Artkonekt\Kampaign\Popup\JsGenerator\JsGeneratorInterface;
use Artkonekt\Kampaign\Popup\JsGenerator\RendererAwareInterface;
use Artkonekt\Kampaign\Popup\PopupHandler;
use Artkonekt\Kampaign\Popup\PopupInitiator;
use Artkonekt\Kampaign\Popup\PopupRenderer;
use Artkonekt\Kampaign\Popup\Transformer\DebugTransformer;
use Artkonekt\Kampaign\Popup\Transformer\NewsletterFormTransformer;
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
     * @var CampaignRepositoryInterface
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
     * @var string
     */
    private $subscribeCallbackUrl = 'subscribe.php';

    private $formTemplate;

    private $mailchimpParams = [];

    /**
     * CampaignDirectorBuilder constructor.
     *
     * @param CampaignRepositoryInterface $campaignRepository
     */
    public function __construct(CampaignRepositoryInterface $campaignRepository, $mailchimpParams)
    {
        $this->campaignRepository = $campaignRepository;
        $this->mailchimpParams = $mailchimpParams;
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
     * @param $subscribeCallbackUrl
     *
     * @return $this
     */
    public function setupNewsletterForm($subscribeCallbackUrl, $formTemplate = null)
    {
        $this->formTemplate = $formTemplate;
        $this->subscribeCallbackUrl = $subscribeCallbackUrl;
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
            $this->popupRenderer = new PopupRenderer(
                $this->getImpressionsOperator(),
                $this->getNewsletterFormTransformer(),
                $transformer
            );
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

        if ($this->jsGenerator instanceof RendererAwareInterface) {
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
            new MailchimpNewsletterSubscriber(
                $this->mailchimpParams['apiKey'],
                $this->mailchimpParams['listId'],
                $this->mailchimpParams['doubleOptin'],
                $this->mailchimpParams['mergeVars']
            ),
            $this->getImpressionsOperator(),
            $this->getDataResolver()
        );
    }

    private function getCampaignLoader()
    {
        return new CampaignLoader($this->campaignRepository, $this->getDataResolver());
    }

    private function getNewsletterFormTransformer()
    {
        return new NewsletterFormTransformer($this->subscribeCallbackUrl, $this->formTemplate);
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