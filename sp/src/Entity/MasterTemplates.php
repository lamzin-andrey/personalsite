<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MasterTemplates
 *
 * @ORM\Table(name="master_templates")
 * @ORM\Entity
 */
class MasterTemplates
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="short_name", type="string", length=128, nullable=true, options={"comment"="Имя для распознавания шаблона человеком"})
     */
    private $shortName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="html_file_path", type="string", length=1024, nullable=true, options={"comment"="Путь к файлу шаблона относительно корня сервера"})
     */
    private $htmlFilePath;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"comment"="Удален или нет"})
     */
    private $isDeleted = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="pos", type="integer", nullable=true, options={"default"="1","comment"="Позиция"})
     */
    private $pos = '1';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getHtmlFilePath(): ?string
    {
        return $this->htmlFilePath;
    }

    public function setHtmlFilePath(?string $htmlFilePath): self
    {
        $this->htmlFilePath = $htmlFilePath;

        return $this;
    }

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getPos(): ?int
    {
        return $this->pos;
    }

    public function setPos(?int $pos): self
    {
        $this->pos = $pos;

        return $this;
    }


}
