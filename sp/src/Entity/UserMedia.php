<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMedia
 *
 * @ORM\Table(name="user_media")
 * @ORM\Entity
 */
class UserMedia
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
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"comment"="Удален или нет."})
     */
    private $isDeleted = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=true, options={"comment"="время создания"})
     */
    private $dateCreate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true, options={"comment"="время обновления"})
     */
    private $dateUpdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="delta", type="integer", nullable=true, options={"comment"="Позиция. "})
     */
    private $delta;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true, options={"comment"="Имя изображения исходное"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true, options={"comment"="Путь к изображению"})
     */
    private $path;

    /**
     * @var string|null
     *
     * @ORM\Column(name="album", type="string", length=255, nullable=true, options={"comment"="Альбом"})
     */
    private $album;

    /**
     * @var string|null
     *
     * @ORM\Column(name="author", type="string", length=255, nullable=true, options={"comment"="Автор"})
     */
    private $author;

    /**
     * @var string|null
     *
     * @ORM\Column(name="artist", type="string", length=255, nullable=true, options={"comment"="Испонитель"})
     */
    private $artist;

    /**
     * @var string|null
     *
     * @ORM\Column(name="band", type="string", length=255, nullable=true, options={"comment"="Название группы"})
     */
    private $band;

    /**
     * @var string|null
     *
     * @ORM\Column(name="director", type="string", length=255, nullable=true, options={"comment"="Режиссер"})
     */
    private $director;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true, options={"comment"="Идентификатор пользователя"})
     */
    private $userId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="musical_styles_id", type="integer", nullable=true, options={"comment"="Идентификатор музвкального стиля"})
     */
    private $musicalStylesId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(?\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(?string $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getBand(): ?string
    {
        return $this->band;
    }

    public function setBand(?string $band): self
    {
        $this->band = $band;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(?string $director): self
    {
        $this->director = $director;

        return $this;
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

    public function getMusicalStylesId(): ?int
    {
        return $this->musicalStylesId;
    }

    public function setMusicalStylesId(?int $musicalStylesId): self
    {
        $this->musicalStylesId = $musicalStylesId;

        return $this;
    }


}
