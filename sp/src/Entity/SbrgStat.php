<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SbrgStat
 *
 * @ORM\Table(name="sbrg_stat")
 * @ORM\Entity
 */
class SbrgStat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для сбора пожеланий потенциальных пользоватеолей"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true, options={"comment"="email"})
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true, options={"comment"="Имя пользователя"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="wtf", type="text", length=65535, nullable=true, options={"comment"="Комменарий пользователя, для чего этот сайт"})
     */
    private $wtf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var int
     *
     * @ORM\Column(name="delta", type="integer", nullable=false)
     */
    private $delta;

    /**
     * @var string|null
     *
     * @ORM\Column(name="host", type="string", length=255, nullable=true, options={"comment"="host site скрытенбург или нет"})
     */
    private $host;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getWtf(): ?string
    {
        return $this->wtf;
    }

    public function setWtf(?string $wtf): self
    {
        $this->wtf = $wtf;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDelta(): ?int
    {
        return $this->delta;
    }

    public function setDelta(int $delta): self
    {
        $this->delta = $delta;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }


}
