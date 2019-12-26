<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatFilter
 *
 * @ORM\Table(name="stat_filter", indexes={@ORM\Index(name="_srch", columns={"cookie", "res_id", "type", "_date"})})
 * @ORM\Entity
 */
class StatFilter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения факта, что некий юзер уже в эти сутки читал тот или иной ресурс"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cookie", type="string", length=64, nullable=true, options={"comment"="Уникальная метка пользователя"})
     */
    private $cookie;

    /**
     * @var int|null
     *
     * @ORM\Column(name="res_id", type="integer", nullable=true, options={"comment"="Идентификатор ресурса, статья или работа портфолио"})
     */
    private $resId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="type", type="boolean", nullable=true, options={"comment"="1 - работа в портфолио, 2 - статья в блоге"})
     */
    private $type;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="_date", type="date", nullable=true, options={"comment"="Дата посещения"})
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCookie(): ?string
    {
        return $this->cookie;
    }

    public function setCookie(?string $cookie): self
    {
        $this->cookie = $cookie;

        return $this;
    }

    public function getResId(): ?int
    {
        return $this->resId;
    }

    public function setResId(?int $resId): self
    {
        $this->resId = $resId;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


}
