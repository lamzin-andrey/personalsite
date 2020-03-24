<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PhdUsers
 *
 * @ORM\Table(name="phd_users")
 * @ORM\Entity
 * @UniqueEntity(fields={"hash", "email"})
 */
class PhdUsers
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Пользователи сервиса конвертации"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(name="hash", type="string", length=64, nullable=true, options={"comment"="md5(IP) + md5(UTIME + ua)"})
     */
    private $hash;

    /**
     * @var string|null
     *
     * @ORM\Column(name="auth_hash", type="string", length=256, nullable=true, options={"comment"="sha256(md5(IP) + md5(UTIME + ua)) Для авторизации при переходе по ссылке из письма"})
     */
    private $authHash;

	/**
	 * @var array|null
	 *
	 * @ORM\OneToMany(targetEntity="PhdMessages", mappedBy="phdUser")
	 */
	private $phdMessages;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=32, nullable=true, options={"comment"="На него будет отправлена ссылка на результат"})
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getAuthHash(): ?string
    {
        return $this->authHash;
    }

    public function setAuthHash(?string $authHash): self
    {
        $this->authHash = $authHash;

        return $this;
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

	public function getPhdMessages(): ?array
	{
		return $this->phdMessages;
	}
}
