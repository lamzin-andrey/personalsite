<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pages
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 */
class Pages
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
     * @var int|null
     *
     * @ORM\Column(name="master", type="integer", nullable=true, options={"comment"="мастер-шаблон"})
     */
    private $master;

    /**
     * @var int|null
     *
     * @ORM\Column(name="hat", type="integer", nullable=true, options={"comment"="шапка"})
     */
    private $hat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adv", type="string", length=256, nullable=true, options={"comment"="рекламный код, имеется ввиду сетевой"})
     */
    private $adv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="banner", type="string", length=256, nullable=true, options={"comment"="баннерный код"})
     */
    private $banner;

    /**
     * @var int|null
     *
     * @ORM\Column(name="selfadvblock_id_top", type="integer", nullable=true, options={"comment"="Идентификатор верхнего рекламного блока"})
     */
    private $selfadvblockIdTop;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quest_id", type="integer", nullable=true, options={"comment"="идентификатор блока опроса"})
     */
    private $questId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="content_block", type="text", length=65535, nullable=true, options={"comment"="html содержимого страницы"})
     */
    private $contentBlock;

    /**
     * @var int|null
     *
     * @ORM\Column(name="selfadvblock_id_bottom", type="integer", nullable=true, options={"comment"="Идентификатор нижнего рекламного блока"})
     */
    private $selfadvblockIdBottom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="right_column", type="string", length=512, nullable=true, options={"comment"="*.htm правого блока"})
     */
    private $rightColumn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="footer", type="string", length=512, nullable=true, options={"comment"="*.htm подвала, включая счетчики"})
     */
    private $footer;

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
     * @var string|null
     *
     * @ORM\Column(name="menu_heading", type="string", length=256, nullable=true, options={"comment"="заголовок пункта меню, ссылающегося на страницу"})
     */
    private $menuHeading;

    /**
     * @var string|null
     *
     * @ORM\Column(name="meta", type="string", length=1024, nullable=true, options={"comment"="мета теги"})
     */
    private $meta;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_menu", type="integer", nullable=true, options={"comment"="Является ли это пунктом главного меню"})
     */
    private $isMenu;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_left_menu", type="integer", nullable=true, options={"comment"="Является ли это пунктом левого меню"})
     */
    private $isLeftMenu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="heading2", type="string", length=256, nullable=true, options={"comment"="подзаголовок страницы"})
     */
    private $heading2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"comment"="Удален или нет"})
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
     * @ORM\Column(name="is_hidden", type="boolean", nullable=true)
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
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=false, options={"comment"="Количество просмотров статьи"})
     */
    private $rating = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="right_menu_secondary_text", type="string", length=255, nullable=true, options={"comment"="Вторичный текст в правом меню"})
     */
    private $rightMenuSecondaryText;

    /**
     * @var bool
     *
     * @ORM\Column(name="hidden_in_list", type="boolean", nullable=false, options={"comment"="1 - Скрывать в списке статей"})
     */
    private $hiddenInList = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="force_recompiled", type="boolean", nullable=false, options={"comment"="1 когда надо всегода перекомипилировать страницу (например, для главной страницы это очень важно)"})
     */
    private $forceRecompiled = '0';

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

    public function getMaster(): ?int
    {
        return $this->master;
    }

    public function setMaster(?int $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getHat(): ?int
    {
        return $this->hat;
    }

    public function setHat(?int $hat): self
    {
        $this->hat = $hat;

        return $this;
    }

    public function getAdv(): ?string
    {
        return $this->adv;
    }

    public function setAdv(?string $adv): self
    {
        $this->adv = $adv;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getSelfadvblockIdTop(): ?int
    {
        return $this->selfadvblockIdTop;
    }

    public function setSelfadvblockIdTop(?int $selfadvblockIdTop): self
    {
        $this->selfadvblockIdTop = $selfadvblockIdTop;

        return $this;
    }

    public function getQuestId(): ?int
    {
        return $this->questId;
    }

    public function setQuestId(?int $questId): self
    {
        $this->questId = $questId;

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

    public function getSelfadvblockIdBottom(): ?int
    {
        return $this->selfadvblockIdBottom;
    }

    public function setSelfadvblockIdBottom(?int $selfadvblockIdBottom): self
    {
        $this->selfadvblockIdBottom = $selfadvblockIdBottom;

        return $this;
    }

    public function getRightColumn(): ?string
    {
        return $this->rightColumn;
    }

    public function setRightColumn(?string $rightColumn): self
    {
        $this->rightColumn = $rightColumn;

        return $this;
    }

    public function getFooter(): ?string
    {
        return $this->footer;
    }

    public function setFooter(?string $footer): self
    {
        $this->footer = $footer;

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

    public function getMenuHeading(): ?string
    {
        return $this->menuHeading;
    }

    public function setMenuHeading(?string $menuHeading): self
    {
        $this->menuHeading = $menuHeading;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(?string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getIsMenu(): ?int
    {
        return $this->isMenu;
    }

    public function setIsMenu(?int $isMenu): self
    {
        $this->isMenu = $isMenu;

        return $this;
    }

    public function getIsLeftMenu(): ?int
    {
        return $this->isLeftMenu;
    }

    public function setIsLeftMenu(?int $isLeftMenu): self
    {
        $this->isLeftMenu = $isLeftMenu;

        return $this;
    }

    public function getHeading2(): ?string
    {
        return $this->heading2;
    }

    public function setHeading2(?string $heading2): self
    {
        $this->heading2 = $heading2;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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

    public function getHiddenInList(): ?bool
    {
        return $this->hiddenInList;
    }

    public function setHiddenInList(bool $hiddenInList): self
    {
        $this->hiddenInList = $hiddenInList;

        return $this;
    }

    public function getForceRecompiled(): ?bool
    {
        return $this->forceRecompiled;
    }

    public function setForceRecompiled(bool $forceRecompiled): self
    {
        $this->forceRecompiled = $forceRecompiled;

        return $this;
    }


}
