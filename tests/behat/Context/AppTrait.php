<?php
declare(strict_types = 1);

namespace Context;

use App\Message\MessageInterface;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Behat\Behat\Hook\Call\BeforeScenario;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
trait AppTrait
{
    use KernelDictionary;

    /**
     * @return ContainerInterface
     */
    public function getTestContainer()
    {
        return $this->getContainer()->get('test.service_container');
    }

    /**
     * Returns test service by id
     *
     * @param string $id
     *
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->getTestContainer()->get($id);
    }

    /**
     * @return EntityManagerInterface
     */
    public function em()
    {
        return $this->get(EntityManagerInterface::class);
    }

    /**
     * @param MessageInterface $message
     */
    public function dispatch(MessageInterface $message)
    {
        /** @var MessageBusInterface $bus */
        $bus = $this->get(MessageBusInterface::class);
        $bus->dispatch($message);
    }

    /**
     * @return \Swift_Plugins_MessageLogger
     */
    public function getSwiftMailerLogger(): \Swift_Plugins_MessageLogger
    {
        return $this->get('swiftmailer.mailer.default.plugin.messagelogger');
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->getSwiftMailerLogger()->clear();
    }
}
