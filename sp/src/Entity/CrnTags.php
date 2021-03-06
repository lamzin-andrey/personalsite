<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * CrnTags
 *
 * @ORM\Table(name="crn_tags", uniqueConstraints={
 *        @UniqueConstraint(name="name_unique",
 *            columns={"name"})
 *    })
 * @ORM\Entity
 *
 * @UniqueEntity(fields={"name"})
 */
class CrnTags
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения тегов"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true, options={"comment"="Полное наименование тега, например ""Symfony"" или ""Symfony 3.4"" или ""vuejs"" или ""Изучение нового"""})
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
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


}
