<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DrvFileRepository")
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
     * @ORM\Column(name="catalog_id", type="integer", options={"comment"="drv_catalog.id", "default"="0"})
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
}