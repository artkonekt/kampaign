<?php

namespace Konekt\Kampaign\Subscriber;

use Mailchimp;

/**
 * Contains class MailchimpNewsletterSubscriber
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */
class MailchimpNewsletterSubscriber implements NewsletterSubscriberInterface
{
    private $apiKey;
    private $listId;
    private $doubleOptin;
    private $mergeVars;
    private $emailType;

    private $errorMessage;

    /**
     * MailchimpNewsletterSubscriber constructor.
     *
     * @param $listId
     * @param $doubleOptin
     * @param $mergeVars
     * @param $emailType
     */
    public function __construct($apiKey, $listId, $doubleOptin, $mergeVars, $emailType = 'html')
    {
        $this->apiKey = $apiKey;
        $this->listId = $listId;
        $this->doubleOptin = $doubleOptin;
        $this->mergeVars = $mergeVars;
        $this->emailType = $emailType;
    }


    /**
     * @param string $email
     *
     * @return bool
     */
    public function subscribe($email, $listId)
    {
        if (!$listId) {
            $listId = $this->listId;
        }

        try {
            $api = new Mailchimp($this->apiKey, []);
            $api->lists->subscribe($listId, ['email' => $email], $this->mergeVars, $this->emailType, $this->doubleOptin);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }

        return true;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}