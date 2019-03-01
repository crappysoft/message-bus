<?php
declare(strict_types = 1);

namespace Dredd;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class HookCollection
{
    /**
     * @var HookInterface[]
     */
    private $hooks = [];

    /**
     * @param HookInterface[] $hooks
     */
    public function __construct(array $hooks)
    {
        $this->hooks = $hooks;

        usort($this->hooks, function (HookInterface $hookA, HookInterface $hookB) {
            return $hookA->getPriority() > $hookB->getPriority();
        });
    }

    /**
     * @return HookInterface[]
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }
}
