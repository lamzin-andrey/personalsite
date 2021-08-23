<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DrvCatalogs
 *
 * @ORM\Table(name="drv_catalogs", indexes={
 *     @ORM\Index(name="parent_id", columns={"parent_id"}),
 *     @ORM\Index(name="is_deleted", columns={"is_deleted"}),
 *     @ORM\Index(name="user_id", columns={"user_id"}),
 *     @ORM\Index(name="is_hide", columns={"is_hide"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\DrvCatalogsRepository")
 */
class DrvCatalogs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица с деревом каталогов пользователя"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true, options={"comment"="ausers.id"})
     */
    private $userId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true, options={"comment"="drv_catalog.id"})
     */
    private $parentId = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true, options={"comment"="Имя каталога"})
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true, options={"comment"="Флаг удален или нет", "default"="0"})
     */
    private $isDeleted = false;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_hide", type="boolean", nullable=true, options={"comment"="Флаг скрытый или нет", "default"="0"})
     */
    private $isHide = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getIsHide(): ?bool
    {
        return $this->isHide;
    }

    public function setIsHide(?bool $isHide): self
    {
        $this->isHide = $isHide;

        return $this;
    }


}
