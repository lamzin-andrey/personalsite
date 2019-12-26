<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhdEmails
 *
 * @ORM\Table(name="phd_emails", uniqueConstraints={@ORM\UniqueConstraint(name="phduid", columns={"uid"})})
 * @ORM\Entity
 */
class PhdEmails
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения email пользователей конвертора phd"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false, options={"comment"="ausers.id пользователя"})
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false, options={"comment"="На этот адрес будет отправлено письмо с сылкой на результат"})
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


}
