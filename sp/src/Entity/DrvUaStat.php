<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Table(name="stat_ua_view",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="record", columns={"user_id", "screen_id", "viewport_id", "url", "ua_id", "iip", "created_time"})
 *     },
 *     indexes={
 *       @ORM\Index(name="user_id", columns={"user_id"}),
 *       @ORM\Index(name="iip", columns={"iip"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Stat\Repository\DrvUaStatRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DrvUaStat
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
    private int $userId;

    /**
     * @ORM\Column(name="screen_id", type="integer", nullable=false)
     */
    private int $screenId;

    /**
     * @ORM\Column(name="viewport_id", type="integer", nullable=false)
     */
    private int $viewportId;

    /**
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private string $url = '';

    /**
     * @ORM\Column(name="ua_id", type="integer", nullable=false)
     */
    private int $uaId;

    /**
     * @ORM\Column(name="iip", type="integer", nullable=false)
     */
    private int $iip;

    /**
     * @ORM\Column(name="created_time", type="date", nullable=false)
     */
    private DateTime $createdTime;



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

    public function getScreenId() : int
    {
        return $this->screenId;
    }

    public function setScreenId(int $screenId) : self
    {
        $this->screenId = $screenId;

        return $this;
    }

    public function getViewportId() : int
    {
        return $this->viewportId;
    }

    public function setViewportId(int $viewportId) : self
    {
        $this->viewportId = $viewportId;

        return $this;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function setUrl(string $url) : self
    {
        $this->url = $url;

        return $this;
    }

    public function getUaId() : int
    {
        return $this->uaId;
    }

    public function setUaId(int $uaId) : self
    {
        $this->uaId = $uaId;

        return $this;
    }

    public function getIip() : int
    {
        return $this->iip;
    }

    public function setIip(int $iip) : self
    {
        $this->iip = $iip;

        return $this;
    }

    public function getCreatedTime() : DateTime
    {
        return $this->createdTime;
    }

    public function setCreatedTime(DateTime $createdTime) : self
    {
        $this->createdTime = $createdTime;

        return $this;
    }


    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedTimeNow() : self
    {
        $this->updatedTime = new DateTime();

        return $this;
    }
}
