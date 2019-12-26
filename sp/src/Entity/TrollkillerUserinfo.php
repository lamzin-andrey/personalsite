<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrollkillerUserinfo
 *
 * @ORM\Table(name="trollkiller_userinfo", uniqueConstraints={@ORM\UniqueConstraint(name="unq_aid_uid", columns={"uid", "a_mail_id"})}, indexes={@ORM\Index(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class TrollkillerUserinfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="trollkiller_userinfo - таблица для хранения данных троллейбусов"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="a_mail_id", type="integer", nullable=false, options={"comment"="Идентификатор троллейбуса в стороннем сервисе"})
     */
    private $aMailId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imgpath", type="string", length=255, nullable=true, options={"comment"="Путь к аватару троллейбуса, скорпированного из стороннего сервиса"})
     */
    private $imgpath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true, options={"comment"="Ник троллейбуса в сторонем сервисе, может быть отредактирован админом trollkiller"})
     */
    private $name;

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

    public function getImgpath(): ?string
    {
        return $this->imgpath;
    }

    public function setImgpath(?string $imgpath): self
    {
        $this->imgpath = $imgpath;

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
