<?php
/**
 * Contains class NewsletterFormTransformer
 *
 * @package     Artkonekt\Kampaign\Popup\Transformer
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-16
 * @version     2015-12-16
 */

namespace Artkonekt\Kampaign\Popup\Transformer;


use Artkonekt\Kampaign\Campaign\TrackableCampaignInterface;
use Artkonekt\Kampaign\Common\DataResolver;
use Artkonekt\Kampaign\Impression\Impressions;

class NewsletterFormTransformer
{
    private $subscriptionUrl;

    private $customTemplate;

    public function __construct($subscriptionUrl, $customTemplate)
    {
        $this->subscriptionUrl = $subscriptionUrl;
        $this->customTemplate = $customTemplate;
    }

    public function transform(TrackableCampaignInterface $campaign, Impressions $impressions, $template)
    {
        $formContent = $this->getParsedFormContent();

        ob_start();
        include __DIR__ . '/../templates/subscribeJs.php';
        $jsContent = ob_get_clean();

        return $template . $formContent . $jsContent;
    }

    private function getParsedFormContent()
    {
        if ($this->customTemplate) {
            $content = $this->customTemplate;
        } else {
            $content = file_get_contents(__DIR__ . '/../templates/defaultform.php');
        }

        $content = str_replace('<form', '<form id="nlc-subscriber-form"', $content);
        $content = str_replace('<input type="input"', '<input type="input" name="' . DataResolver::SUBSCRIBER_EMAIL_KEY . '"', $content);

        return $content;

    }
}