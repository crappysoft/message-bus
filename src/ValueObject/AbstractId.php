<?php
declare(strict_types = 1);

namespace App\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
abstract class AbstractId
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @var UuidInterface
     */
    protected $id;

    /**
     * @param \Ramsey\Uuid\UuidInterface $uuid
     */
    protected function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return static
     */
    public static function generate(): AbstractId
    {
        return new static(Uuid::uuid4());
    }

    /**
     * @param string $uuid
     *
     * @return static
     */
    public static function fromString(string $uuid): AbstractId
    {
        return new static(Uuid::fromString($uuid));
    }
}
