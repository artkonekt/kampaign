<?php

/**
 * Contains class ManagerTest
 *
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-07
 * @version     2015-12-07
 */

use Artkonekt\Kampaign\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase {

    public function testInitReturnsTrue()
    {
        $manager = new Manager();
        $this->assertTrue($manager->init());
    }

}