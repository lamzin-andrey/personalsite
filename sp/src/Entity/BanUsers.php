<?php

namespace App\Entity;

use App\Service\BitReader;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 *
 * @ORM\Table(name="ban_users", indexes={@ORM\Index(name="weight_from", columns={"weight_from"}), @ORM\Index(name="growth_from", columns={"growth_from"}), @ORM\Index(name="weight_to", columns={"weight_to"}), @ORM\Index(name="growth_to", columns={"growth_to"})})
 * @ORM\Entity(repositoryClass="App\Repository\BanUserRepository")
 */
class BanUsers implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Первичный ключ."})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pwd", type="string", length=32, nullable=true, options={"comment"="пароль"})
     */
    private $pwd;

    /**
     * @var string|null
	 * @Assert\NotBlank()
	 * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=64, nullable=true, options={"comment"="email"})
     */
    private $email;

    /**
     * @var string|null
	 * @Assert\Regex(pattern = "/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/s", message="Password must containts symbols in upper and lower case and numbers")
     * @ORM\Column(name="password", type="string", length=100, nullable=true, options={"comment"="password hash"})
     */
    private $password;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="salt", type="string", length=255, nullable=true, options={"comment"="Соль для Symfony"})
	 */
	private $salt;

	/**
	 * @var string|null
	 * @Assert\NotBlank()
	 * @ORM\Column(name="$username", type="string", length=255, nullable=true, options={"comment"="Имя пользователя для Symfony"})
	 */
	private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="guest_id", type="string", length=32, nullable=true, options={"comment"="sha1( ip + ua + datetime + rand + uniq_id) кука авторизации пользователя"})
     */
    private $guestId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_access_time", type="datetime", nullable=true, options={"comment"="время последнего посещения сайта"})
     */
    private $lastAccessTime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_deleted", type="integer", nullable=true, options={"comment"="Удален или нет."})
     */
    private $isDeleted = '0';

	/**
	 * @var int|null
	 *
	 * @ORM\Column(name="logotype_id", type="integer", nullable=false, options={"comment"="Логотип user_media.id","default"="0"})
	 */
	private $logotypeId = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=true, options={"comment"="время создания"})
     */
    private $dateCreate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true, options={"comment"="время обновления"})
     */
    private $dateUpdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="delta", type="integer", nullable=true, options={"comment"="Позиция. "})
     */
    private $delta;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=64, nullable=true, options={"comment"="Имя пользователя"})
     */
    private $name;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @ORM\Column(name="surname", type="string", length=64, nullable=true, options={"comment"="Фамилия пользователя"})
     */
    private $surname;

    /**
     * @var int|null
     *
     * @ORM\Column(name="role", type="integer", nullable=true, options={"comment"="Роль пользователя 0 - пользователь 1 - модератор  2 - админ 3 - админ раздела конвертация psd в html+css 4 - пользователь web-usb", "default"="0"})
     */
    private $role = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="sex", type="string", length=7, nullable=true, options={"comment"="Пол пользователя 1 man, 2 woman"})
     */
    private $sex;

    /**
     * @var string|null
     *
     * @ORM\Column(name="age", type="string", length=7, nullable=true, options={"comment"="Возраст пользователя"})
     */
    private $age;

    /**
     * @var int|null
     *
     * @ORM\Column(name="growth_from", type="integer", nullable=true, options={"comment"="Рост от, см."})
     */
    private $growthFrom;

    /**
     * @var int|null
     *
     * @ORM\Column(name="growth_to", type="integer", nullable=true, options={"comment"="Рост до, см."})
     */
    private $growthTo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="weight_from", type="integer", nullable=true, options={"comment"="Рост от, кг."})
     */
    private $weightFrom;

    /**
     * @var int|null
     *
     * @ORM\Column(name="weight_to", type="integer", nullable=true, options={"comment"="Рост до, кг."})
     */
    private $weightTo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="health", type="integer", nullable=true, options={"comment"="Здоровье 1 не пью, 2 не курю, 3 Наркотикам нет"})
     */
    private $health;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="children", type="boolean", nullable=true, options={"comment"="Дети 1 есть, 2 нет"})
     */
    private $children;

    /**
     * @var string|null
     *
     * @ORM\Column(name="recovery_hash", type="string", length=100, nullable=true, options={"comment"="Хэш md5 для восстановления пароля"})
     */
    private $recoveryHash;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="recovery_hash_created", type="datetime", nullable=true, options={"comment"="Время которое хеш действителен"})
     */
    private $recoveryHashCreated;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="is_accept_all_cookies_time", type="datetime", nullable=true, options={"comment"="Метка, когда пользователь согласился использовать куки"})
     */
    private $isAcceptAllCookiesTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="country_id", type="datetime", nullable=true, options={"comment"="Страна"})
     */
    private $countryId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="region_id", type="datetime", nullable=true, options={"comment"="Регион"})
     */
    private $regionId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="city_id", type="datetime", nullable=true, options={"comment"="Город"})
     */
    private $cityId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=17, nullable=true, options={"comment"="Телефон"})
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_subscribed", type="boolean", nullable=false, options={"comment"="1 когда пользователь согласен получать письма от ЛАНд-а","default"="0"})
    */
    private $isSubscribed = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="is_accept_all_cookies", type="boolean", nullable=false, options={"comment"="1 когда пользователь согласен хранить куки","default"="0"})
     */
    private $isAcceptAllCookies = '0';
    
    /**
     * @var int
     *
     * @ORM\Column(name="bsettings", type="integer", nullable=false, options={"comment"="Настройки типа bool. Хранятся в битах числа. Бит 0: бот в чате Полиглот 0 - выключен, 1 - включен. Бит 1: язык webUSB. 0 - en, 1 - ru","default"="0"})
    */
    private $bsettings = 0;

    /**
     * @ORM\Column(name="ban_cnt", type="integer", nullable=false, options={"comment"="Счетчик забаненых файлов пользователя.","default"=0})
     */
    private int $banCnt = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(?string $pwd): self
    {
        $this->pwd = $pwd;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGuestId(): ?string
    {
        return $this->guestId;
    }

    public function setGuestId(?string $guestId): self
    {
        $this->guestId = $guestId;

        return $this;
    }

    public function getLastAccessTime(): ?\DateTimeInterface
    {
        return $this->lastAccessTime;
    }

    public function setLastAccessTime(?\DateTimeInterface $lastAccessTime): self
    {
        $this->lastAccessTime = $lastAccessTime;

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
	public function getLogotypeId(): ?int
	{
		return $this->logotypeId;
	}

	public function setLogotypeId(?int $logotypeId): self
	{
		$this->logotypeId = $logotypeId;
		return $this;
	}

    
    public function getBSettings(): ?int
    {
        return $this->bsettings;
    }

    public function setBSettings(?int $v): self
    {
        $this->bsettings = $v;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(?\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getGrowthFrom(): ?int
    {
        return $this->growthFrom;
    }

    public function setGrowthFrom(?int $growthFrom): self
    {
        $this->growthFrom = $growthFrom;

        return $this;
    }

    public function getGrowthTo(): ?int
    {
        return $this->growthTo;
    }

    public function setGrowthTo(?int $growthTo): self
    {
        $this->growthTo = $growthTo;

        return $this;
    }

    public function getWeightFrom(): ?int
    {
        return $this->weightFrom;
    }

    public function setWeightFrom(?int $weightFrom): self
    {
        $this->weightFrom = $weightFrom;

        return $this;
    }

    public function getWeightTo(): ?int
    {
        return $this->weightTo;
    }

    public function setWeightTo(?int $weightTo): self
    {
        $this->weightTo = $weightTo;

        return $this;
    }

    public function getHealth(): ?int
    {
        return $this->health;
    }

    public function setHealth(?int $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getChildren(): ?bool
    {
        return $this->children;
    }

    public function setChildren(?bool $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getRecoveryHash(): ?string
    {
        return $this->recoveryHash;
    }

    public function setRecoveryHash(?string $recoveryHash): self
    {
        $this->recoveryHash = $recoveryHash;

        return $this;
    }

    public function getRecoveryHashCreated(): ?\DateTimeInterface
    {
        return $this->recoveryHashCreated;
    }

    public function setRecoveryHashCreated(?\DateTimeInterface $recoveryHashCreated): self
    {
        $this->recoveryHashCreated = $recoveryHashCreated;

        return $this;
    }

    public function getCountryId(): ?\DateTimeInterface
    {
        return $this->countryId;
    }

    public function setCountryId(?\DateTimeInterface $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getRegionId(): ?\DateTimeInterface
    {
        return $this->regionId;
    }

    public function setRegionId(?\DateTimeInterface $regionId): self
    {
        $this->regionId = $regionId;

        return $this;
    }

    public function getCityId(): ?\DateTimeInterface
    {
        return $this->cityId;
    }

    public function setCityId(?\DateTimeInterface $cityId): self
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIsSubscribed(): ?bool
    {
        return $this->isSubscribed;
    }

    public function setIsSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    public function getIsAcceptAllCookies(): ?bool
    {
        return $this->isAcceptAllCookies;
    }

    public function setIsAcceptAllCookies(bool $acceptCookies): self
    {
        $this->isAcceptAllCookies = $acceptCookies;

        return $this;
    }

    public function getIsAcceptAllCookiesTime(): ?\DateTimeInterface
    {
        return $this->isAcceptAllCookiesTime;
    }

    public function setIsAcceptAllCookiesTime(?\DateTimeInterface $time): self
    {
        $this->isAcceptAllCookiesTime = $time;

        return $this;
    }

	public function getSalt(): ?string
	{
		return $this->salt;
	}

	public function setSalt(?string $salt): self
	{
		$this->salt = $salt;

		return $this;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(?string $username): self
	{
		$this->username = $username;

		return $this;
	}

	public function getRoles()
	{
		return ['ROLE_USER'];
	}

	public function eraseCredentials()
	{
		;
	}

	public function setLang(string $lang) {
        $n = intval($this->getBSettings());
        $targetValue = 0;
        if ('ru' == $lang) {
            $targetValue = 1;
        }

        $currentValue = BitReader::get($n, 1);
        if ($currentValue != $targetValue) {
            $n = $n ^ 2;// Для 2 бит номер 1 равен 1. Если в бите номер 1 $n 0 XOR даст 1. Если в бите номер 1 $n 1 XOR даст 0. Бит номер 2 $n не будет затронут, проверено.
        }
        $this->setBSettings($n);
    }

    public function getBanCount(): int
    {
        return $this->banCnt;
    }

    public function setBanCount(int $banCnt): self
    {
        $this->banCnt = $banCnt;

        return $this;
    }

    public function setAdvAgree(int $agreeFlag)
    {
        $n = intval($this->getBSettings());
        $currentValue = BitReader::get($n, 3);
        if ($currentValue != $agreeFlag) {
            $n = $n ^ 4;
            $this->setBSettings($n);
        }
    }

    public function getAdvAgree(): int
    {
        $n = intval($this->getBSettings());
        $r =  BitReader::get($n, 3);
        return $r;
    }
}
