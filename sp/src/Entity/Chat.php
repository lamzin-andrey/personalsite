<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chat
 *
 * @ORM\Table(name="chat", indexes={@ORM\Index(name="from_id", columns={"from_id"}), @ORM\Index(name="from_id_2", columns={"from_id"})})
 * @ORM\Entity
 */
class Chat
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
     * @ORM\Column(name="message", type="string", length=2048, nullable=true, options={"comment"="Сообщение"})
     */
    private $message;

    /**
     * @var int|null
     *
     * @ORM\Column(name="from_id", type="integer", nullable=true, options={"comment"="Отправитель сообщения"})
     */
    private $fromId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="to_id", type="integer", nullable=true, options={"comment"="Получатель сообщения"})
     */
    private $toId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_read", type="boolean", nullable=true, options={"comment"="1 если прочтено"})
     */
    private $isRead = '0';

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getFromId(): ?int
    {
        return $this->fromId;
    }

    public function setFromId(?int $fromId): self
    {
        $this->fromId = $fromId;

        return $this;
    }

    public function getToId(): ?int
    {
        return $this->toId;
    }

    public function setToId(?int $toId): self
    {
        $this->toId = $toId;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): self
    {
        $this->isRead = $isRead;

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


}
