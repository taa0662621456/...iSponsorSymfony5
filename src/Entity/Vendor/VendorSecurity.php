<?php
declare(strict_types=1);

namespace App\Entity\Vendor;

use App\Entity\BaseTrait;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Exception;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints\Length;


/**
 * @ORM\Table(name="vendors_security", indexes={
 * @ORM\Index(name="vendor_security_idx", columns={"slug", "email", "phone"})}, uniqueConstraints={
 * @ORM\UniqueConstraint(name="vendor_security_idx", columns={"slug", "email", "phone"})})
 * @UniqueEntity("email",
 *        errorPath="email",
 *        message="You have an account already or this email already in use!")
 * @UniqueEntity("phone",
 *        errorPath="phone",
 *        message="You have an account already or this phone already in use!")
 * @ORM\Entity(repositoryClass="App\Repository\Vendor\VendorSecurityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class VendorSecurity implements UserInterface, Serializable, PasswordAuthenticatedUserInterface
{
	use BaseTrait;

    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="vendors_security.blank_content")
     * @Assert\Length(min=3)
     * @Assert\Email(message = "The email '{{ value }}' is not a valid.")
     */
    private string $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     * @Assert\NotBlank(message="vendors.message.error.phone")
     * @Length(min=10, minMessage="vendors_security.too_short_content")
     * @Length(max=12, maxMessage="vendors_security.too_long_content")
     */
    private string $phone = '';

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true, options={"default"="isponsor"})
     * @Assert\Length(min=3)
     * @Assert\Length(max=16)
     */
    private string $username = 'isponsor';

	/**
	 * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     * @Assert\Length(max=16)
     * Assert\NotCompromisedPassword
     *
	 * @ORM\Column(name="password", type="string", unique=false, nullable=false, options={"default" : 00000000})
     * Assert\NotCompromisedPassword
	 */
	protected string $password = '00000000';

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Length(min=8)
	 * @Assert\Length(max=16)
     * Assert\NotCompromisedPassword
	 *
	 */
	private string $plainPassword = '00000000';

	/**
	 * @var array
	 *
	 * @ORM\Column(name="roles", type="json", nullable=false)
	 */
	private array $roles = [];


	/**
	 * @var boolean|null
	 *
	 * @ORM\Column(name="send_email", type="boolean", nullable=true)
	 */
	private ?bool $sendEmail = null;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="last_visit_date", type="string", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
	 */
	private string $lastVisitDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="activation_code", type="string", nullable=false, options={"default"="0"})
	 */
	private string $activationCode = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="locale", type="string", nullable=false, options={"default"="en"})
	 */
	private string $locale = 'en';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="params", type="string", nullable=false, options={"default"="params"})
	 */
	private string $params = 'params';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="last_reset_time", type="string", nullable=false, options={"default":"CURRENT_TIMESTAMP",
     *     "comment"="Date of last password reset"})
	 */
	private string $lastResetTime;

	/**
	 * @var integer|null
	 *
	 * @ORM\Column(name="reset_count", type="integer", options={"comment"="Count of password resets
     * since lastResetTime"})
     *
	 */
	private ?int $resetCount = 0;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="otp_key", type="string", nullable=false, options={"default"="","comment"="Two factor
     * authentication encrypted keys"})
     *
	 */
	private string $otpKey = '';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="otep", type="string", nullable=false, options={"default"="","comment"="One time emergency
     * passwords"})
     *
	 */
	private string $otep = '';

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="require_reset", type="boolean", nullable=false, options={"comment"="Require user to reset
     * password on next login"})
     *
	 */
	private int|bool $requireReset = 0;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="api_key", type="string", nullable=false, options={"comment"="API key"})
	 */
	private string $apiKey = 'api_key';

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\Vendor\Vendor",
	 *     cascade={"persist", "remove"},
	 *     inversedBy="vendorSecurity")
	 */
	private mixed $vendorSecurity;


	/**
	 * VendorsSecurity constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->roles = [self::ROLE_USER];
        $t = new DateTime();
		$this->lastResetTime = $t->format('Y-m-d H:i:s');
		$this->lastVisitDate = $t->format('Y-m-d H:i:s');

	}

	/**
	 * @ORM\Column(name="salt", type="string")
	 */
	private string $salt = '0';

	/**
	 * @return string
	 */
	public function getApiKey(): string
	{
		return $this->apiKey;
	}

	/**
	 * @param string $apiKey
	 *
	 * @return VendorSecurity
	 */
	public function setApiKey(string $apiKey): self
	{
		$this->apiKey = $apiKey;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 *
	 * @return VendorSecurity
	 */
	public function setEmail(string $email): self
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone(string $phone): void
	{
		$this->phone = $phone;
	}


	/**
	 * @return bool|null
	 */
	public function getSendEmail(): ?bool
	{
		return $this->sendEmail;
	}

	/**
	 * @param bool|null $sendEmail
	 *
	 * @return VendorSecurity
	 */
	public function setSendEmail(?bool $sendEmail): self
	{
		$this->sendEmail = $sendEmail;
		return $this;
	}

	/**
	 * @return string
     */
	public function getLastVisitDate(): string
    {
		return $this->lastVisitDate;
	}

	/**
	 * @param string $lastVisitDate
	 *
	 * @return VendorSecurity
	 */
	public function setLastVisitDate(string $lastVisitDate): self
	{
		$this->lastVisitDate = $lastVisitDate;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getActivationCode(): string
	{
		return $this->activationCode;
	}

	/**
	 * @param string $activationCode
	 *
	 * @return VendorSecurity
	 */
	public function setActivationCode(string $activationCode): self
	{
		$this->activationCode = $activationCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLocale(): string
	{
		return $this->locale;
	}

	/**
	 * @param string $locale
	 */
	public function setLocale(string $locale): void
	{
		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getParams(): string
	{
		return $this->params;
	}

	/**
	 * @param string $params
	 *
	 * @return VendorSecurity
	 */
	public function setParams(string $params): self
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @return string
     */
	public function getLastResetTime(): string
    {
		return $this->lastResetTime;
	}

	/**
	 * @param string $lastResetTime
	 *
	 * @return VendorSecurity
	 */
	public function setLastResetTime(string $lastResetTime): self
	{
		$this->lastResetTime = $lastResetTime;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getResetCount(): int
	{
		return $this->resetCount;
	}

	/**
	 * @param int $resetCount
	 *
	 * @return VendorSecurity
	 */
	public function setResetCount(int $resetCount): self
	{
		$this->resetCount = $resetCount;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOtpKey(): string
	{
		return $this->otpKey;
	}

	/**
	 * @param string $otpKey
	 *
	 * @return VendorSecurity
	 */
	public function setOtpKey(string $otpKey): self
	{
		$this->otpKey = $otpKey;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOtep(): string
	{
		return $this->otep;
	}

	/**
	 * @param string $otep
	 *
	 * @return VendorSecurity
	 */
	public function setOtep(string $otep): self
	{
		$this->otep = $otep;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isRequireReset(): bool
	{
		return $this->requireReset;
	}

	/**
	 * @param bool $requireReset
	 *
	 * @return VendorSecurity
	 */
	public function setRequireReset(bool $requireReset): self
	{
		$this->requireReset = $requireReset;
		return $this;
	}

	/**
	 * Returns the roles granted to the user.
	 *
	 *     public function getRoles()
	 *     {
	 *         return ['ROLE_USER'];
	 *     }
	 *
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return array
	 */
	public function getRoles(): array
	{
		return [
			'ROLE_USER'
		];
	}

	/**
	 * @param array $roles
	 */
	public function setRoles(array $roles): void
	{
		$this->roles = $roles;
	}



	/**
	 * Returns the password used to authenticate the user.
	 *
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 *
	 * @return VendorSecurity
	 */
	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPlainPassword(): string
	{
		return $this->plainPassword;
	}

    /**
     * @param string $password
     * @return VendorSecurity
     */
	public function setPlainPassword(string $password): self
	{
		$this->plainPassword = $password;

		return $this;
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 */
	public function getSalt()
	{
		// TODO: Implement getSalt() method.
		return null;
	}

	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials(): string
	{
		$this->plainPassword = '';
		return '';
	}

	/**
	 * String representation of object
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize(): string
	{
		return serialize([
			$this->id,
			$this->email,
			$this->password
		]);

	}

	/**
	 * Constructs the object
	 *
	 * @link  https://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $data <p>
	 *
	 * @return void
	 * @since 5.1.0
	 */
	public function unserialize($data): void
	{
		[
			$this->id,
			$this->email,
			$this->password
		] = unserialize($data, ['allowed_class' => false]);
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername(): string
	{
		return $this->email;
	}

	/**
	 * @return mixed
	 */
	public function getVendorSecurity(): mixed
    {
		return $this->vendorSecurity;
	}


	/**
	 * @param Vendor $vendorSecurity
	 */
	public function setVendorSecurity(Vendor $vendorSecurity): void
	{
		$this->vendorSecurity = $vendorSecurity;
	}


}
