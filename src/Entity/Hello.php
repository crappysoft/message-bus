<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Hello
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", name="id", length=255, nullable=true)
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="name", length=255, nullable=true)
     *
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }



    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }



}
