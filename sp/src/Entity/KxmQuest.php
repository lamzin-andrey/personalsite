<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KxmQuestRepository")
 */
class KxmQuest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $var1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $var2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $var3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $var4;

    /**
     * @ORM\Column(type="integer")
     */
    private $var_right;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $delta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getVar1(): ?string
    {
        return $this->var1;
    }

    public function setVar1(string $var1): self
    {
        $this->var1 = $var1;

        return $this;
    }

    public function getVar2(): ?string
    {
        return $this->var2;
    }

    public function setVar2(string $var2): self
    {
        $this->var2 = $var2;

        return $this;
    }

    public function getVar3(): ?string
    {
        return $this->var3;
    }

    public function setVar3(string $var3): self
    {
        $this->var3 = $var3;

        return $this;
    }

    public function getVar4(): ?string
    {
        return $this->var4;
    }

    public function setVar4(string $var4): self
    {
        $this->var4 = $var4;

        return $this;
    }

    public function getVarRight(): ?int
    {
        return $this->var_right;
    }

    public function setVarRight(int $var_right): self
    {
        $this->var_right = $var_right;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDelta(): ?int
    {
        return $this->delta;
    }

    public function setDelta(int $delta): self
    {
        $this->delta = $delta;

        return $this;
    }
}
