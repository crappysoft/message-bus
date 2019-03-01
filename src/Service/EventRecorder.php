<?php
declare(strict_types = 1);

namespace App\Service;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class EventRecorder
{
    /**
     * @var array
     */
    private $events = [];

    /**
     * @param object $event
     */
    public function recordEvent($event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
