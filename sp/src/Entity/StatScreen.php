<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="stat_screen",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(name="screen", columns={"screen"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Stat\Repository\StatScreenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class StatScreen
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="screen", type="string", length=64, nullable=false)
     */
    private string $screen = '';



    public function getId() : ?int
    {
        return $this->id;
    }

    public function getScreen() : string
    {
        return $this->screen;
    }

    public function setScreen(string $screen) : self
    {
        $this->screen = $screen;

        return $this;
    }

}
