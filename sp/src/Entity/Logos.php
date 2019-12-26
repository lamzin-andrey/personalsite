<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logos
 *
 * @ORM\Table(name="logos")
 * @ORM\Entity
 */
class Logos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Первичный ключ."})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=512, nullable=true, options={"comment"="Путь к файлу относительно корня сервера"})
     */
    private $path;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true, options={"comment"="Исходное имя файла"})
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"comment"="Удален или нет. Может называться по другому, но тогда в cdbfrselectmodel надо указать, как именно"})
     */
    private $isDeleted = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="delta", type="integer", nullable=true, options={"comment"="Позиция.  Может называться по другому, но тогда в cdbfrselectmodel надо указать, как именно"})
     */
    private $delta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDelta(): ?int
    {
        return $this->delta;
    }

    public function setDelta(?int $delta): self
    {
        $this->delta = $delta;

        return $this;
    }


}
