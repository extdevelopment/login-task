<?php

namespace Netpositive\LoginBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @AttributeOverrides({
 *      @AttributeOverride(name="lastLogin",
 *          column=@ORM\Column(
 *              name = "last_login_datetime",
 *              type="datetime",
 *              nullable=true,
 *              options={"default" = null}
 *          )
 *      )
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * // max length: IPV6 (8*4)+7
     * @ORM\Column(type="string", name="last_login_client_ip", nullable=true, length=39, options={"default" = null})
     * @Assert\Ip(version="all")
     */
    protected $lastloginClientIp;

    /**
     * @var
     *
     * @ORM\Column(type="string", name="full_name", nullable=true, length=255, options={"default" = null})
     */
    protected $fullName;

    /**
     * @var
     *
     * @ORM\Column(type="string", name="phone", nullable=true, length=100, options={"default" = null})
     * @Assert\Regex("/^\d+$/")
     */
    protected $phone;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="create_datetime")
     */
    private $createDatetime;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="update_datetime")
     */
    private $updateDatetime;

    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * override original setter, with set username with email
     * {@inheritDoc}
     *
     * @see \FOS\UserBundle\Model\User::setEmail()
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * Set createDatetime.
     *
     * @param \DateTime $createDatetime
     *
     * @return User
     */
    public function setCreateDatetime($createDatetime)
    {
        $this->createDatetime = $createDatetime;

        return $this;
    }

    /**
     * Get createDatetime.
     *
     * @return \DateTime
     */
    public function getCreateDatetime()
    {
        return $this->createDatetime;
    }

    /**
     * Set updateDatetime.
     *
     * @param \DateTime $updateDatetime
     *
     * @return User
     */
    public function setUpdateDatetime($updateDatetime)
    {
        $this->updateDatetime = $updateDatetime;

        return $this;
    }

    /**
     * Get updateDatetime.
     *
     * @return \DateTime
     */
    public function getUpdateDatetime()
    {
        return $this->updateDatetime;
    }

    /**
     * Set lastloginClientIp.
     *
     * @param string $lastloginClientIp
     *
     * @return User
     */
    public function setLastloginClientIp($lastloginClientIp)
    {
        $this->lastloginClientIp = $lastloginClientIp;

        return $this;
    }

    /**
     * Get lastloginClientIp.
     *
     * @return string
     */
    public function getLastloginClientIp()
    {
        return $this->lastloginClientIp;
    }

    /**
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
