<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhdMessagesLog
 *
 * @ORM\Table(name="phd_messages_log")
 * @ORM\Entity
 */
class PhdMessagesLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="История обработки одной транзакции из phd_message"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment"="Время операции"})
     */
    private $createdAt;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="phd_messages_state", type="boolean", nullable=true, options={"comment"="См. phd_messages._state комментарий"})
     */
    private $phdMessagesState;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="operator_state", type="boolean", nullable=true, options={"comment"="1 - принято в обработку, 2 - загружены результативные данные ( результат скрин и замечания), 3 тикет помечен как закрытый"})
     */
    private $operatorState = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPhdMessagesState(): ?bool
    {
        return $this->phdMessagesState;
    }

    public function setPhdMessagesState(?bool $phdMessagesState): self
    {
        $this->phdMessagesState = $phdMessagesState;

        return $this;
    }

    public function getOperatorState(): ?bool
    {
        return $this->operatorState;
    }

    public function setOperatorState(?bool $operatorState): self
    {
        $this->operatorState = $operatorState;

        return $this;
    }


}
