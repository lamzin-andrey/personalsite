<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhdMessages
 *
 * @ORM\Table(name="phd_messages")
 * @ORM\Entity
 */
class PhdMessages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Запросы от пользователей на конвертацию"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment"="Время создания записи"})
     */
    private $createdAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="result_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на архив с файлами верстки"})
     */
    private $resultLink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="psd_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на PSD файл"})
     */
    private $psdLink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="service_notes", type="text", length=65535, nullable=true, options={"comment"="Замечания от сервиса"})
     */
    private $serviceNotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="preview_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на превью работы"})
     */
    private $previewLink;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="notice_preview_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на превью работы с замечаниями"})
	 */
	private $noticePreviewLink;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="css_preview_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на превью css работы "})
	 */
	private $cssPreviewLink;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="html_example_link", type="string", length=255, nullable=true, options={"comment"="Ссылка на архив с html работы (демо-архив)"})
	 */
	private $htmlExampleLink;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_payed", type="boolean", nullable=true, options={"comment"="1 когда конвертация оплачена"})
     */
    private $isPayed = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="operation_id", type="integer", nullable=true, options={"comment"="Ссылка на запись в таблице phd_operations"})
     */
    private $operationId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="operator_id", type="integer", nullable=true, options={"comment"="ausers.id - Оператор, обработавший конвертацию", "default"="0"})
    */
    private $operatorId = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_closed", type="boolean", nullable=true, options={"comment"="1 когда тикет закрыт оператором (оплачено и отправлено)", "default"="0"})
     */
    private $isClosed = false;

    /**
     * @var int|null
     *
     * @ORM\Column(name="_state", type="integer", nullable=true, options={"comment"="0 файл в очереди, 1 файл загружается на сервер конвертации, 2 файл конвертируется, 3 показ превью и замечания,	4 ввод email, 5 выбор оплаты, разрешить показывать в примерах работ и получить скидку, 6 как платить, яндекс или киви, 7 ожидание оплаты, 8 ссылка отправлена на email и показана на странице, 9 вернулся к загрузке нового psd"})
     */
    private $state = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="uid", type="integer", nullable=true, options={"comment"="phd_user.id."})
     */
    private $uid;

	/**
	 * @var bool|null
	 *
	 * @ORM\Column(name="is_email_user", type="boolean", nullable=true, options={"comment"="1 когда пользователь не дождался реакции оператора, но оставил свой email", "default"="0"})
	 */
	private $is_email_user;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_publish", type="boolean", nullable=false, options={"comment"="Разрешить публиковать работу в примерах работ"})
     */
    private $isPublish = '0';

	/**
	 * @var PhdUsers
	 * //Эта красота ломает работу метода PhdMessages->setUid($nId); Ошибки не происходит, но и значение не сохраняется.
	 * //Поэтому надо вместо  PhdMessages->setUid($nId) использовать PhdMessages->setPhdUser($oPhdUser);
	 * @ORM\ManyToOne(targetEntity="PhdUsers", inversedBy="phdMessages")
	 * @ORM\JoinColumn(name="uid", referencedColumnName="id")
	*/
	private $phdUser;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getResultLink(): ?string
    {
        return $this->resultLink;
    }

    public function setResultLink(?string $resultLink): self
    {
        $this->resultLink = $resultLink;

        return $this;
    }

    public function getPsdLink(): ?string
    {
        return $this->psdLink;
    }

    public function setPsdLink(?string $psdLink): self
    {
        $this->psdLink = $psdLink;

        return $this;
    }

    public function getServiceNotes(): ?string
    {
        return $this->serviceNotes;
    }

    public function setServiceNotes(?string $serviceNotes): self
    {
        $this->serviceNotes = $serviceNotes;

        return $this;
    }

    public function getPreviewLink(): ?string
    {
        return $this->previewLink;
    }

    public function setPreviewLink(?string $previewLink): self
    {
        $this->previewLink = $previewLink;

        return $this;
    }

	public function getCssPreviewLink(): ?string
	{
		return $this->cssPreviewLink;
	}

	public function setCssPreviewLink(?string $cssPreviewLink): self
	{
		$this->cssPreviewLink = $cssPreviewLink;

		return $this;
	}

	public function getHtmlExampleLink(): ?string
	{
		return $this->htmlExampleLink;
	}

	public function setHtmlExampleLink(?string $htmlExampleLink): self
	{
		$this->htmlExampleLink = $htmlExampleLink;

		return $this;
	}

	public function getNoticePreviewLink(): ?string
	{
		return $this->noticePreviewLink;
	}

	public function setNoticePreviewLink(?string $noticePreviewLink): self
	{
		$this->noticePreviewLink = $noticePreviewLink;

		return $this;
	}

    public function getIsPayed(): ?bool
    {
        return $this->isPayed;
    }

    public function setIsPayed(?bool $isPayed): self
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    public function getOperationId(): ?int
    {
        return $this->operationId;
    }

    public function setOperationId(?int $operationId): self
    {
        $this->operationId = $operationId;

        return $this;
    }

    public function getOperatorId(): ?int
    {
        return $this->operatorId;
    }

    public function setOperatorId(?int $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(?bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

	public function getIsEmailUser(): ?bool
	{
		return $this->is_email_user;
	}

	public function setIsEmailUser(?bool $is_email_user): self
	{
		$this->is_email_user = $is_email_user;
		return $this;
	}

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getIsPublish(): ?bool
    {
        return $this->isPublish;
    }

    public function setIsPublish(bool $isPublish): self
    {
        $this->isPublish = $isPublish;

        return $this;
    }

	public function getPhdUser()
	{
		return $this->phdUser;
	}

	public function setPhdUser($oPhdUser)
	{
		$this->phdUser = $oPhdUser;
		return $this;
	}

}
