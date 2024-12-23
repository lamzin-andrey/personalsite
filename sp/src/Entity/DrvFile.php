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
        @ORM\Index(name="moderatus", columns={"moderatus"}),
        @ORM\Index(name="wd_public", columns={"wd_public"})
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

    /**
     *
     * @ORM\Column(name="wd_path", type="string", length=255, nullable=false, options={"comment"="Вечный Путь на диске web_dav.", "default"=""})
     */
    private string $wdPath = '';

    /**
     *
     * @ORM\Column(name="wd_public", type="smallint", nullable=false, options={"comment"="1 если успешный public, 2 - если не найден на локале, 3 - загружен, но не опубликован, 4 - удален в webDav спустя пол-года, 5 - не надо двигать на диск, файл админский и является частью приложения, 6 - надо распубликовать, так как файл удален", "default"="0"})
     */
    private int $wdPublic = 0;

    /**
     *
     * @ORM\Column(name="wd_error", type="string", length=8192, nullable=false, options={"comment"="Сообщение об ошибка от webdav сервера", "default"=""})
     */
    private string $wdError = '';

    /**
     * @ORM\Column(name="wd_link", type="string", length=255, nullable=false, options={"comment"="Записываем общую ссылку на файл", "default"=""})
     */
    private string $wdLink = '';

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

    public function getWdPublic(): int
    {
        return $this->wdPublic;
    }

    public function setWdPublic(int $wdPublic): self
    {
        $this->wdPublic = $wdPublic;

        return $this;
    }

    public function getWdPath(): string
    {
        return $this->wdPath;
    }

    public function setWdPath(string $wdPath): self
    {
        $this->wdPath = $wdPath;

        return $this;
    }

    public function getWdLink(): string
    {
        return $this->wdLink;
    }

    public function setWdLink(string $wdLink): self
    {
        $this->wdLink = $wdLink;

        return $this;
    }

    public function getWdError(): string
    {
        return $this->wdError;
    }

    public function setWdError(string $wdError): self
    {
        $this->wdError = $wdError;

        return $this;
    }
}
