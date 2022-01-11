<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SocnetUserRepository")
 * @ORM\Table(
    name="socnet_user",
    indexes={
      @ORM\Index(name="user_id", columns={"user_id"}),
      @ORM\Index(name="socnet_id", columns={"socnet_id"})
    }
   )
 */

class SocnetUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private $socnetId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSocnetId(): ?int
    {
        return $this->socnetId;
    }

    public function setSocnetId(int $socnetId): self
    {
        $this->socnetId = $socnetId;

        return $this;
    }
}
