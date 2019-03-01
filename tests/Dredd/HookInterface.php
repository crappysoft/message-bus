<?php
declare(strict_types = 1);

namespace Dredd;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
interface HookInterface
{
    /**
     * @param mixed $transaction
     */
    public function beforeAll(&$transaction): void;

    /**
     * @param mixed $transaction
     */
    public function beforeEach(&$transaction): void;

    /**
     * @return callable[]
     */
    public function before(): array;

    /**
     * @param mixed $transaction
     */
    public function beforeEachValidation(&$transaction): void;

    /**
     * @return callable[]
     */
    public function beforeValidation(): array;

    /**
     * @return callable[]
     */
    public function after(): array;

    /**
     * @param mixed $transaction
     */
    public function afterEach(&$transaction): void;

    /**
     * @param mixed $transaction
     */
    public function afterAll(&$transaction): void;

    /**
     * @return integer
     */
    public function getPriority(): int;
}
