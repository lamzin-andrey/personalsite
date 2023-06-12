<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use DateTimeImmutable;

/**
 * @ORM\Table(name="drv_file_permissions")
 * @ORM\Entity(repositoryClass="App\WebUSB\Repository\DrvFilePermissionsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DrvFilePermissions
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="file_id", type="integer", nullable=true, options={"default"=NULL})
     */
    private $fileId;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=true, options={"default"=NULL})
     */
    private $userId;

    /**
     * @ORM\Column(name="created_time", type="datetime", nullable=true, options={"default"=NULL})
     */
    private DateTimeInterface $createdTime;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getFileId() : int
    {
        return $this->fileId;
    }

    public function setFileId(int $fileId) : self
    {
        $this->fileId = $fileId;

        return $this;
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

    public function getCreatedTime() : DateTimeInterface
    {
        return $this->createdTime;
    }

    public function setCreatedTime(DateTimeInterface $createdTime) : self
    {
        $this->createdTime = $createdTime;

        return $this;
    }
}
