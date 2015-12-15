<?php

namespace Artkonekt\Kampaign\Subscriber;

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
class MailchimpNewsletterSubscriber implements NewsletterSubscriber
{
    private $apiKey;
    private $listId;
    private $doubleOptin;
    private $mergeVars;
    private $emailType;

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
     */
    public function subscribe($email)
    {
        return true;
    }
}