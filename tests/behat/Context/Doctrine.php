<?php

namespace Context;

use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Behat\Behat\Hook\Call\BeforeScenario;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
class Doctrine implements KernelAwareContext
{
    use AppTrait;

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        (new ORMPurger($this->em()))->purge();
    }
}
