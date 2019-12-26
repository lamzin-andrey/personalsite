<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * P2psimple
 *
 * @ORM\Table(name="p2psimple", indexes={@ORM\Index(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class P2psimple
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
     * @ORM\Column(name="uid", type="string", length=32, nullable=true, options={"comment"="sha1( ip + ua + datetime + rand + uniq_id) кука авторизации пользователя"})
     */
    private $uid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"comment"="Удален или нет."})
     */
    private $isDeleted = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="offer", type="text", length=65535, nullable=true, options={"comment"="Данные для инициализации p2p соединения не инициатором"})
     */
    private $offer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=true, options={"comment"="Данные для инициализации p2p соединения инициатором"})
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

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

    public function getOffer(): ?string
    {
        return $this->offer;
    }

    public function setOffer(?string $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }


}
