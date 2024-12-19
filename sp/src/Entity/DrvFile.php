<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DrvFileRepository")
 * @ORM\Table(
    name="drv_file",
    indexes={
        @ORM\Index(name="user_id", columns={"user_id"}),
        @ORM\Index(name="catalog_id", columns={"catalog_id"}),
        @ORM\Index(name="moderatus", columns={"moderatus"})
    }
  )
 */
class DrvFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="catalog_id", nullable=true, type="integer", options={"comment"="drv_catalog.id", "default"="0"})
     */
    private $catalogId = 0;

    /**
     * @ORM\Column(name="type", type="string", length=32, options={"comment"="unknown, zip, audio, text, image, pdf", "default"="unknown"})
     */
    private $type;

    /**
     * @ORM\Column(name="name", type="string", length=255, options={"comment"="Original file name", "default"=""})
     */
    private $name;

    /**
     * @ORM\Column(name="is_deleted", type="boolean", options={"comment"="1 - is deleted, 0 - in no deleted", "default"="0"})
     */
    private $isDeleted = false;

    /**
     * @ORM\Column(name="is_public", type="boolean", options={"comment"="1 - allow for all, 0 - no allow for all", "default"="0"})
     */
    private $isPublic = false;

    /**
     * @var DrvCatalogs
     * @ORM\ManyToOne(targetEntity="DrvCatalogs", inversedBy="files")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id")
    */

    private $catalogEntity;

    /**
     * @ORM\Column(name="is_hidden", type="boolean", options={"comment"="1 - is hidden, 0 - in no hidden", "default"="0"})
     */
    private $isHidden = false;

    /**
     * @ORM\Column(name="user_id", type="integer", options={"comment"="ausers.id"})
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=false, options={"comment"="Размер файла", "default"="0"})
     */
    private $size = 0;

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
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=false, options={"comment"="Хеш файла", "default"=""})
     */
    private $hash;

    /**
     * @ORM\Column(name="moderatus", type="smallint", options={"comment"="0 - ок (не требует модерации), 1 - требует модерации, 2 - одобрено, 3 - запрещено", "default"="0"})
     */
    private $moderatus = 0;

    /**
     * @ORM\Column(name="is_no_erased", type="boolean", options={"comment"="1 - physical file no erase, 0 - physical file is erase", "default"="1"})
     */
    private bool $isNoErased = true;

    /**
     * @ORM\Column(name="dwn_cnt", type="integer", options={"comment"="Quantity downloads!", "default"=0})
     */
    private $dwnCnt = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatalogEntity(): ?DrvCatalogs
    {
        return $this->catalogEntity;
    }

    public function setCatalogEntity(?DrvCatalogs $catalog): self
    {
        $this->catalogEntity = $catalog;
        if (is_null($catalog)) {
            $this->catalogId = 0;
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $is_deleted): self
    {
        $this->isDeleted = $is_deleted;

        return $this;
    }

    public function getIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $is_hidden): self
    {
        $this->isHidden = $is_hidden;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $user_id): self
    {
        $this->userId = $user_id;

        return $this;
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

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return DrvFile
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     * @return DrvFile
     */
    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getIsNoErased(): bool
    {
        return $this->isNoErased;
    }

    /**
     * @return DrvFile
     */
    public function setIsNoErased(bool $isNoErased): self
    {
        $this->isNoErased = $isNoErased;

        return $this;
    }

    public function getModeratus(): ?int
    {
        return $this->moderatus;
    }

    public function setModeratus(int $moderatus): self
    {
        $this->moderatus = $moderatus;

        return $this;
    }

    public function getDwnCnt(): int
    {
        return $this->dwnCnt;
    }

    public function setDwnCnt(int $dwnCnt): self
    {
        $this->dwnCnt = $dwnCnt;

        return $this;
    }
}
