<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PortfolioPages
 *
 * @ORM\Table(name="portfolio_pages", uniqueConstraints={@ORM\UniqueConstraint(name="unq_page_related", columns={"page_id", "work_id"})})
 * @ORM\Entity
 */
class PortfolioPages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица для хранения связанных с работой портфолио статей"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="work_id", type="integer", nullable=false, options={"comment"="id работы портфолио portfolio.id"})
     */
    private $workId;

    /**
     * @var int
     *
     * @ORM\Column(name="page_id", type="integer", nullable=false, options={"comment"="id статьи pages.id"})
     */
    private $pageId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkId(): ?int
    {
        return $this->workId;
    }

    public function setWorkId(int $workId): self
    {
        $this->workId = $workId;

        return $this;
    }

    public function getPageId(): ?int
    {
        return $this->pageId;
    }

    public function setPageId(int $pageId): self
    {
        $this->pageId = $pageId;

        return $this;
    }


}
