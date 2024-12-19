<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Table(
 *     name="stat_ip",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(name="ip", columns={"ip"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Stat\Repository\StatIpRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class StatIp
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="ip", type="string", length=255, nullable=false)
     */
    private string $ip = '';


    public function getId() : ?int
    {
        return $this->id;
    }

    public function getIp() : string
    {
        return $this->ip;
    }

    public function setIp(string $ip) : self
    {
        $this->ip = $ip;

        return $this;
    }
}
