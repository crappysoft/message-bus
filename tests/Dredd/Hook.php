<?php
declare(strict_types = 1);

namespace Dredd;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
class Hook implements HookInterface
{
    /**
     * {@inheritDoc}
     */
    public function beforeAll(&$transaction): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function beforeEach(&$transaction): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function before(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function beforeEachValidation(&$transaction): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function beforeValidation(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function after(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function afterEach(&$transaction): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function afterAll(&$transaction): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return 0;
    }
}
