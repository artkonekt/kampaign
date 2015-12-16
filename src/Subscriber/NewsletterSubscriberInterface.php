<?php

namespace Artkonekt\Kampaign\Subscriber;

/**
 * Contains interface NewsletterSubscriberInterface
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-14
 * @version     2015-12-14
 */
/**
 * Interface NewsletterSubscriberInterface
 *
 * @package Artkonekt\Kampaign\Prototype
 */
interface NewsletterSubscriberInterface
{
    /**
     * @param $email
     *
     * @return mixed
     */
    public function subscribe($email);

    public function getErrorMessage();
}