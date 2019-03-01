<?php
declare(strict_types = 1);

namespace App\Messenger\Middleware;

use App\Service\EventRecorder;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class FireEventsMiddleware implements MiddlewareInterface
{
    /**
     * @var EventRecorder
     */
    private $eventRecorder;

    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * @param EventRecorder       $eventRecorder
     * @param MessageBusInterface $eventBus
     */
    public function __construct(EventRecorder $eventRecorder, MessageBusInterface $eventBus)
    {
        $this->eventRecorder = $eventRecorder;
        $this->eventBus      = $eventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $result = $stack->next()->handle($envelope, $stack);

        foreach ($this->eventRecorder->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }

        return $result;
    }
}
