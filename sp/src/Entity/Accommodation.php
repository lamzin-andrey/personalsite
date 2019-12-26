<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Accommodation
 *
 * @ORM\Table(name="accommodation")
 * @ORM\Entity
 */
class Accommodation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name_male", type="string", length=255, nullable=true, options={"comment"="В этом поле метка чекбокса для мужского рода"})
     */
    private $nameMale;

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

    public function getNameMale(): ?string
    {
        return $this->nameMale;
    }

    public function setNameMale(?string $nameMale): self
    {
        $this->nameMale = $nameMale;

        return $this;
    }


}
