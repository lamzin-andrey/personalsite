<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrnTasks
 *
 * @ORM\Table(name="crn_tasks")
 * @ORM\Entity
 */
class CrnTasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения наименования задачи. В rel подях хранится время учитывающее только реально затраченные на работу часы."})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true, options={"comment"="Полное наименование задачи, например Оплата возможности поднятий объявления с помощью Я-денег или Сайт для подачи объявлений о грузоперевозках"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=4096, nullable=true, options={"comment"="Описание, на всякий пожарный"})
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codename", type="string", length=255, nullable=true, options={"comment"="Кодовое имя задачи для более быстрого поиска. Например gzs для сайта о перевозках на газели написанного на Symfony 3.4"})
     */
    private $codename;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start_datetime", type="datetime", nullable=true, options={"comment"="время, когда всё началось."})
     */
    private $startDatetime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_datetime", type="datetime", nullable=true, options={"comment"="время, когда все закончилось (обновляется по мере изменений в crn_intervals)"})
     */
    private $endDatetime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true, options={"comment"="Ссылка на родительскую задачу, например для Форма оплаты через Я-деньги может быть родителем Сайт про газели"})
     */
    private $parentId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rel_years", type="integer", nullable=true, options={"comment"="Общее количество затраченных лет"})
     */
    private $relYears;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rel_months", type="integer", nullable=true, options={"comment"="количество затраченных месяцев после количества лет"})
     */
    private $relMonths;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rel_weeks", type="integer", nullable=true, options={"comment"="количество затраченных недель после количества месяцев"})
     */
    private $relWeeks;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rel_days", type="integer", nullable=true, options={"comment"="количество затраченных суток после количества недель"})
     */
    private $relDays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rel_hours", type="integer", nullable=true, options={"comment"="количество затраченных часов после количества суток"})
     */
    private $relHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_hours", type="integer", nullable=true, options={"comment"="Общее количество затраченных часов"})
     */
    private $totalHours;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_executed", type="boolean", nullable=true, options={"comment"="1 когда задача выполняется. такая для одного пользователя должна быть одна и только одна"})
     */
    private $isExecuted = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=true, options={"comment"="1 когда задача публичная и доступна для всеобщего поиска (вдруг народ захочет сравнить)"})
     */
    private $isPublic = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ausers_id", type="integer", nullable=true, options={"comment"="ausers.id"})
     */
    private $ausersId;

	/**
	 * @var int|null
	 *
	 * @ORM\Column(name="delta", type="integer", nullable=true, options={"comment"="Позиция"})
	 */
	private $delta;

    public function getId(): ?int
    {
        return $this->id;
    }

	public function setId(?int $id): self
	{
		$this->id = $id;
		return $this;
	}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCodename(): ?string
    {
        return $this->codename;
    }

    public function setCodename(?string $codename): self
    {
        $this->codename = $codename;

        return $this;
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

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getRelYears(): ?int
    {
        return $this->relYears;
    }

    public function setRelYears(?int $relYears): self
    {
        $this->relYears = $relYears;

        return $this;
    }

    public function getRelMonths(): ?int
    {
        return $this->relMonths;
    }

    public function setRelMonths(?int $relMonths): self
    {
        $this->relMonths = $relMonths;

        return $this;
    }

    public function getRelWeeks(): ?int
    {
        return $this->relWeeks;
    }

    public function setRelWeeks(?int $relWeeks): self
    {
        $this->relWeeks = $relWeeks;

        return $this;
    }

    public function getRelDays(): ?int
    {
        return $this->relDays;
    }

    public function setRelDays(?int $relDays): self
    {
        $this->relDays = $relDays;

        return $this;
    }

    public function getRelHours(): ?int
    {
        return $this->relHours;
    }

    public function setRelHours(?int $relHours): self
    {
        $this->relHours = $relHours;

        return $this;
    }

    public function getTotalHours(): ?int
    {
        return $this->totalHours;
    }

    public function setTotalHours(?int $totalHours): self
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    public function getIsExecuted(): ?bool
    {
        return $this->isExecuted;
    }

    public function setIsExecuted(?bool $isExecuted): self
    {
        $this->isExecuted = $isExecuted;

        return $this;
    }

	public function getDelta(): ?int
	{
		return $this->delta;
	}

	public function setDelta(?int $n): self
	{
		$this->delta = $n;
		return $this;
	}

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getAusersId(): ?int
    {
        return $this->ausersId;
    }

    public function setAusersId(?int $ausersId): self
    {
        $this->ausersId = $ausersId;

        return $this;
    }


}
