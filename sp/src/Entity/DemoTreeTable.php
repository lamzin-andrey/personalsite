<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DemoTreeTable
 *
 * @ORM\Table(name="demo_tree_table", indexes={@ORM\Index(name="parent_id_idx", columns={"parent_id"}), @ORM\Index(name="sys_idx", columns={"system"})})
 * @ORM\Entity
 */
class DemoTreeTable
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
     * @var bool
     *
     * @ORM\Column(name="system", type="boolean", nullable=false, options={"comment"="Системное поле, которое нельзя удалить"})
     */
    private $system = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true, options={"comment"="Название страны"})
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_moderate", type="integer", nullable=true, options={"comment"="Промодерирован или нет."})
     */
    private $isModerate = '0';

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

    public function getSystem(): ?bool
    {
        return $this->system;
    }

    public function setSystem(bool $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;

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

    public function getIsModerate(): ?int
    {
        return $this->isModerate;
    }

    public function setIsModerate(?int $isModerate): self
    {
        $this->isModerate = $isModerate;

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
