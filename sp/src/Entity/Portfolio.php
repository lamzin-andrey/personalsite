<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolio
 *
 * @ORM\Table(name="portfolio", indexes={@ORM\Index(name="rating", columns={"rating"})})
 * @ORM\Entity
 */
class Portfolio
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=512, nullable=true, options={"comment"="url html страницы"})
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content_block", type="text", length=65535, nullable=true, options={"comment"="html содержимого страницы"})
     */
    private $contentBlock;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=256, nullable=true, options={"comment"="title страницы"})
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="heading", type="string", length=256, nullable=true, options={"comment"="заголовок страницы"})
     */
    private $heading;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"default"="0", "comment"="Удален или нет"})
     */
    private $isDeleted = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="delta", type="integer", nullable=true, options={"default"="1","comment"="Позиция"})
     */
    private $delta = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="logo", type="integer", nullable=true, options={"comment"="Id файла из таблицы logos"})
     */
    private $logo;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_hidden", type="boolean", nullable=true, options={"default"="0","comment"="Скрыт или нет"})
     */
    private $isHidden = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=true, options={"comment"="META - тег description страницы"})
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="keywords", type="string", length=512, nullable=true, options={"comment"="META - тег keywords страницы"})
     */
    private $keywords;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP","comment"="Время создания"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="1970-01-01 00:00:00","comment"="Время обновления"})
     */
    private $updatedAt = '1970-01-01 00:00:00';

    /**
     * @var string|null
     *
     * @ORM\Column(name="og_title", type="string", length=512, nullable=true, options={"comment"="META - тег og:title страницы"})
     */
    private $ogTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="og_description", type="string", length=512, nullable=true, options={"comment"="META - тег og:description страницы"})
     */
    private $ogDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="og_image", type="string", length=512, nullable=true, options={"comment"="META - тег og:image страницы"})
     */
    private $ogImage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true, options={"comment"=" Ссылка на раздел статьи"})
     */
    private $categoryId;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_self_section", type="boolean", nullable=false, options={"comment"="Для возможности установки поля формы ""Продукт имеет отдельный раздел на сайте"""})
     */
    private $hasSelfSection = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="dont_create_page", type="boolean", nullable=false, options={"comment"="Для возможности установки поля формы ""Не создавать отдельную страницу"""})
     */
    private $dontCreatePage = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="sha256", type="string", length=64, nullable=true, options={"comment"="Хэш файла программы установки"})
     */
    private $sha256;

    /**
     * @var string|null
     *
     * @ORM\Column(name="product_file", type="string", length=512, nullable=true, options={"comment"="Путь к установочному файлу программы"})
     */
    private $productFile;

    /**
     * @var bool
     *options={"default"="1","comment"="Позиция"}
     * @ORM\Column(name="hide_from_productlist", type="boolean", nullable=false, options={"default"="0", "comment"="Показывать ли в спике на странице Портфолио"})
     */
    private $hideFromProductlist = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="hours", type="integer", nullable=false, options={"comment"="Общее время, затраченное на разработку, в часах"})
     */
    private $hours;

    /**
     * @var string|null
     *
     * @ORM\Column(name="shortdesc", type="string", length=4028, nullable=true, options={"comment"="Краткое описание работы для списка"})
     */
    private $shortdesc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="outer_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на работу в стороннем сервисе"})
     */
    private $outerLink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="outer_link_text", type="string", length=512, nullable=true, options={"comment"="Текст ссылки на работу в стороннем сервисе"})
     */
    private $outerLinkText;

    /**
     * @var string|null
     *
     * @ORM\Column(name="product_file_textlink", type="string", length=255, nullable=true, options={"comment"="Текст ссылки на файл на сайте"})
     */
    private $productFileTextlink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="custom_download_content", type="text", length=65535, nullable=true, options={"comment"="html блока загрузка, используется в том случае если ссылки больше чем одна"})
     */
    private $customDownloadContent;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=false, options={"comment"="Количество просмотров страницы работы", "default"="0"})
     */
    private $rating = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="right_menu_heading", type="string", length=255, nullable=false, options={"comment"="Текст ссылки в правом меню"})
     */
    private $rightMenuHeading;

    /**
     * @var string|null
     *
     * @ORM\Column(name="right_menu_secondary_text", type="string", length=255, nullable=true, options={"comment"="Вторичный текст в правом меню"})
     */
    private $rightMenuSecondaryText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getContentBlock(): ?string
    {
        return $this->contentBlock;
    }

    public function setContentBlock(?string $contentBlock): self
    {
        $this->contentBlock = $contentBlock;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setHeading(?string $heading): self
    {
        $this->heading = $heading;

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

    public function getDelta(): ?int
    {
        return $this->delta;
    }

    public function setDelta(?int $delta): self
    {
        $this->delta = $delta;

        return $this;
    }

    public function getLogo(): ?int
    {
        return $this->logo;
    }

    public function setLogo(?int $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(?bool $isHidden): self
    {
        $this->isHidden = $isHidden;

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

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgTitle(?string $ogTitle): self
    {
        $this->ogTitle = $ogTitle;

        return $this;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;

        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(?string $ogImage): self
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getHasSelfSection(): ?bool
    {
        return $this->hasSelfSection;
    }

    public function setHasSelfSection(bool $hasSelfSection): self
    {
        $this->hasSelfSection = $hasSelfSection;

        return $this;
    }

    public function getDontCreatePage(): ?bool
    {
        return $this->dontCreatePage;
    }

    public function setDontCreatePage(bool $dontCreatePage): self
    {
        $this->dontCreatePage = $dontCreatePage;

        return $this;
    }

    public function getSha256(): ?string
    {
        return $this->sha256;
    }

    public function setSha256(?string $sha256): self
    {
        $this->sha256 = $sha256;

        return $this;
    }

    public function getProductFile(): ?string
    {
        return $this->productFile;
    }

    public function setProductFile(?string $productFile): self
    {
        $this->productFile = $productFile;

        return $this;
    }

    public function getHideFromProductlist(): ?bool
    {
        return $this->hideFromProductlist;
    }

    public function setHideFromProductlist(bool $hideFromProductlist): self
    {
        $this->hideFromProductlist = $hideFromProductlist;

        return $this;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getShortdesc(): ?string
    {
        return $this->shortdesc;
    }

    public function setShortdesc(?string $shortdesc): self
    {
        $this->shortdesc = $shortdesc;

        return $this;
    }

    public function getOuterLink(): ?string
    {
        return $this->outerLink;
    }

    public function setOuterLink(?string $outerLink): self
    {
        $this->outerLink = $outerLink;

        return $this;
    }

    public function getOuterLinkText(): ?string
    {
        return $this->outerLinkText;
    }

    public function setOuterLinkText(?string $outerLinkText): self
    {
        $this->outerLinkText = $outerLinkText;

        return $this;
    }

    public function getProductFileTextlink(): ?string
    {
        return $this->productFileTextlink;
    }

    public function setProductFileTextlink(?string $productFileTextlink): self
    {
        $this->productFileTextlink = $productFileTextlink;

        return $this;
    }

    public function getCustomDownloadContent(): ?string
    {
        return $this->customDownloadContent;
    }

    public function setCustomDownloadContent(?string $customDownloadContent): self
    {
        $this->customDownloadContent = $customDownloadContent;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRightMenuHeading(): ?string
    {
        return $this->rightMenuHeading;
    }

    public function setRightMenuHeading(string $rightMenuHeading): self
    {
        $this->rightMenuHeading = $rightMenuHeading;

        return $this;
    }

    public function getRightMenuSecondaryText(): ?string
    {
        return $this->rightMenuSecondaryText;
    }

    public function setRightMenuSecondaryText(?string $rightMenuSecondaryText): self
    {
        $this->rightMenuSecondaryText = $rightMenuSecondaryText;

        return $this;
    }


}
