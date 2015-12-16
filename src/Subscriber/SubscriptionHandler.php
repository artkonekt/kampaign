<?php
/**
 * Contains class SubscriptionHandler
 *
 * @package     Artkonekt\Kampaign\Prototype
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-15
 * @version     2015-12-15
 */

namespace Artkonekt\Kampaign\Subscriber;

use Artkonekt\Kampaign\Campaign\CampaignLoader;
use Artkonekt\Kampaign\Common\DataResolver;
use Artkonekt\Kampaign\Impression\ImpressionsOperator;

/**
 * Class SubscriptionHandler
 *
 * @package Artkonekt\Kampaign\Prototype
 */
class SubscriptionHandler
{
    /**
     * @var CampaignLoader
     */
    private $campaignLoader;

    /**
     * @var NewsletterSubscriberx
     */
    private $newsletterSubscriber;

    /**
     * @var ImpressionsOperator
     */
    private $impressionsOperator;

    /**
     * @var DataResolver
     */
    private $dataResolver;

    /**
     * SubscriptionHandler constructor.
     *
     * @param CampaignLoader                $campaignLoader
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param ImpressionsOperator           $impressionsOperator
     * @param DataResolver                  $dataResolver
     */
    public function __construct(
        CampaignLoader $campaignLoader,
        NewsletterSubscriberInterface $newsletterSubscriber,
        ImpressionsOperator $impressionsOperator,
        DataResolver $dataResolver
    )
    {
        $this->campaignLoader = $campaignLoader;
        $this->newsletterSubscriber = $newsletterSubscriber;
        $this->impressionsOperator = $impressionsOperator;
        $this->dataResolver = $dataResolver;
    }

    /**
     *
     */
    public function subscribeEmail()
    {
        $email = $this->dataResolver->getEmail();
        $campaign = $this->campaignLoader->getTracked();

        if ($this->newsletterSubscriber->subscribe($email)) {
            echo sprintf('Email "%s" subscribed successfully through campaign %s', $email, $campaign->getTrackingId());
            $this->impressionsOperator->disableFor($campaign);
        } else {
            header("HTTP/1.0 400 " . $this->newsletterSubscriber->getErrorMessage());
        }
    }
}