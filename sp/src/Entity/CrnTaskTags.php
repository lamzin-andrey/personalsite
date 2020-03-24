<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * CrnTaskTags
 *
 * @ORM\Table(name="crn_task_tags", uniqueConstraints={
 *        @UniqueConstraint(name="tagtask_unique",
 *            columns={"tag_id", "task_id"})
 *    })
 * @ORM\Entity
 * @UniqueEntity(fields={"taskId", "tagId"})
 */
class CrnTaskTags
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения связей задачи и тегов"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="task_id", type="integer", nullable=true, options={"comment"="crn_tasks.id"})
     */
    private $taskId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tag_id", type="integer", nullable=true, options={"comment"="crn_tags.id"})
     */
    private $tagId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): ?int
    {
        return $this->taskId;
    }

    public function setTaskId(?int $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getTagId(): ?int
    {
        return $this->tagId;
    }

    public function setTagId(?int $tagId): self
    {
        $this->tagId = $tagId;

        return $this;
    }


}
