<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="drv_ua",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(name="ua", columns={"ua"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Stat\Repository\DrvUaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DrvUa
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="ua", type="string", length=255, nullable=false)
     */
    private string $ua = '';



    public function getId() : ?int
    {
        return $this->id;
    }

    public function getUa() : string
    {
        return $this->ua;
    }

    public function setUa(string $ua) : self
    {
        $this->ua = $ua;

        return $this;
    }

}
