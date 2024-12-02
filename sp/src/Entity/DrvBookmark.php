<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="drv_bookmarks",
 *     indexes={
        @ORM\Index(name="user_id", columns={"user_id"})
       }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DrvBookmarkRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DrvBookmark
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private int $userId = 0;

    /**
     * @ORM\Column(name="bm", type="string", length=4096, nullable=false)
     */
    private string $bm = '';



    public function getId() : ?int
    {
        return $this->id;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function setUserId(int $userId) : self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getBm() : string
    {
        return $this->bm;
    }

    public function setBm(string $bm) : self
    {
        $this->bm = $bm;

        return $this;
    }
}
