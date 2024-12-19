<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="stat_viewport",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(name="viewport", columns={"viewport"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\StatViewportRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class StatViewport
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="viewport", type="string", length=64, nullable=false)
     */
    private string $viewport;


    public function getId() : ?int
    {
        return $this->id;
    }

    public function getViewport() : string
    {
        return $this->viewport;
    }

    public function setViewport(string $viewport) : self
    {
        $this->viewport = $viewport;

        return $this;
    }
}
