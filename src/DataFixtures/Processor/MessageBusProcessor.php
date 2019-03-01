<?php
declare(strict_types = 1);

namespace App\DataFixtures\Processor;

use App\Message\MessageInterface;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class MessageBusProcessor implements ProcessorInterface
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function preProcess(string $id, $object): void
    {
        if ($object instanceof MessageInterface) {
            $this->bus->dispatch($object);
        }
    }

    public function postProcess(string $id, $object): void
    {
    }
}
