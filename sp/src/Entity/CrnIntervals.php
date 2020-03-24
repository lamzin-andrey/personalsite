<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrnIntervals
 *
 * @ORM\Table(name="crn_intervals")
 * @ORM\Entity
 */
class CrnIntervals
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения интервалов работы над задачей"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start_datetime", type="datetime", nullable=true, options={"comment"="Начало интервала"})
     */
    private $startDatetime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_datetime", type="datetime", nullable=true, options={"comment"="Окончание интервала"})
     */
    private $endDatetime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="task_id", type="integer", nullable=true, options={"comment"="crn_tasks.id"})
     */
    private $taskId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(?\DateTimeInterface $startDatetime): self
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTimeInterface
    {
        return $this->endDatetime;
    }

    public function setEndDatetime(?\DateTimeInterface $endDatetime): self
    {
        $this->endDatetime = $endDatetime;

        return $this;
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


}
