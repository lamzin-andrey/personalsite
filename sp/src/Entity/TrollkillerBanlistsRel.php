<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrollkillerBanlistsRel
 *
 * @ORM\Table(name="trollkiller_banlists_rel", uniqueConstraints={@ORM\UniqueConstraint(name="unq_client_id_subject_id", columns={"client_id", "subject_id"})}, indexes={@ORM\Index(name="client_idx", columns={"client_id"})})
 * @ORM\Entity
 */
class TrollkillerBanlistsRel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица хранит связь ausers.id подписчика ->ausers.id издателей"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false, options={"comment"="ausers.id пользователя, который подписан на чужие бан-списки"})
     */
    private $clientId;

    /**
     * @var int
     *
     * @ORM\Column(name="subject_id", type="integer", nullable=false, options={"comment"="ausers.id пользователя, на бан-списки котрого подписан client_id"})
     */
    private $subjectId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(int $subjectId): self
    {
        $this->subjectId = $subjectId;

        return $this;
    }


}
