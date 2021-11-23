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

    /**
     * @var array of DrvFile
     * @ORM\OneToMany(targetEntity="DrvFile", mappedBy="catalogEntity")
    */

    private $files;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=false, options={"comment"="Размер каталога", "default"="0"})
     */
    private $size = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity_catalogs", type="integer", nullable=false, options={"comment"="Количество вложенных каталогов", "default"="0"})
     */
    private $quantityCatalogs = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime", nullable=false, options={"comment"="Время создания", "default"="2021-11-23 00:00:00"})
     */
    private $createdTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_time", type="datetime", nullable=false, options={"comment"="Время изменения", "default"="2021-11-23 00:00:00"})
     */
    private $updatedTime;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity_files", type="integer", nullable=false, options={"comment"="Количество вложенных файлов", "default"="0"})
     */
    private $quantityFiles = 0;

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

    public function getFiles()
    {
        return $this->files;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $n): self
    {
        $this->size = $n;

        return $this;
    }

    public function getQuantityCatalogs(): int
    {
        return $this->quantityCatalogs;
    }

    public function setQuantityCatalogs(int $n): self
    {
        $this->quantityCatalogs = $n;

        return $this;
    }

    public function getQuantityFiles(): int
    {
        return $this->quantityFiles;
    }

    public function setQuantityFiles(int $n): self
    {
        $this->quantityFiles = $n;

        return $this;
    }


    public function getCreatedTime(): ?\DateTime
    {
        return $this->createdTime;
    }

    public function setCreatedTime(?\DateTime $n): self
    {
        $this->createdTime = $n;

        return $this;
    }

    public function getUpdatedTime(): ?\DateTime
    {
        return $this->updatedTime;
    }

    public function setUpdatedTime(?\DateTime $n): self
    {
        $this->updatedTime = $n;

        return $this;
    }
}
