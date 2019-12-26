<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trollkiller
 *
 * @ORM\Table(name="trollkiller", uniqueConstraints={@ORM\UniqueConstraint(name="unq_aid_uid", columns={"uid", "a_mail_id"})}, indexes={@ORM\Index(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class Trollkiller
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="trollkiller - таблица для хранения списков забаненных троллей"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="a_mail_id", type="integer", nullable=false, options={"comment"="Идентификатор забаненого пользователя в стороннем сервисе"})
     */
    private $aMailId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reason", type="string", length=255, nullable=true, options={"comment"="Причина блокировки"})
     */
    private $reason;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nick", type="string", length=64, nullable=true, options={"comment"="Ник забаненого пользователя в сторонем сервисе, может быть отредактирован модератором"})
     */
    private $nick;

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false, options={"comment"="ausers.uid"})
     */
    private $uid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAMailId(): ?int
    {
        return $this->aMailId;
    }

    public function setAMailId(int $aMailId): self
    {
        $this->aMailId = $aMailId;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(?string $nick): self
    {
        $this->nick = $nick;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }


}
