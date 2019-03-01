<?php
declare(strict_types = 1);

namespace Dredd;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Dredd
{
    /**
     * @var HookCollection
     */
    private $hookCollection;

    /**
     * @param HookCollection $hookCollection
     */
    public function __construct(HookCollection $hookCollection)
    {
        $this->hookCollection = $hookCollection;
    }

    /**
     * Registers all hooks
     */
    public function boot(): void
    {
        foreach ($this->hookCollection->getHooks() as $hook) {
            Hooks::beforeAll([$hook, 'beforeAll']);
            Hooks::beforeEach([$hook, 'beforeEach']);
            Hooks::beforeEachValidation([$hook, 'beforeEachValidation']);
            Hooks::afterEach([$hook, 'afterEach']);
            Hooks::afterAll([$hook, 'afterAll']);

            foreach ($hook->before() as $transactionName => $callable) {
                Hooks::before($transactionName, $callable);
            }

            foreach ($hook->after() as $transactionName => $callable) {
                Hooks::after($transactionName, $callable);
            }

            foreach ($hook->beforeValidation() as $transactionName => $callable) {
                Hooks::beforeValidation($transactionName, $callable);
            }
        }
    }
}
