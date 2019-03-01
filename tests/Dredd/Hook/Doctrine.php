<?php
declare(strict_types = 1);

namespace Dredd\Hook;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Dredd\Hook;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Doctrine extends Hook
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeEach(&$transaction): void
    {
        (new ORMPurger($this->em))->purge();
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return -10;
    }
}
