<?php

/**
 * This file is part of the LoginBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netpositive\LoginBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

/**
 * User Entity.
 *
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
     * id.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * last login Client Ip.
     *
     * @var string
     *
     * max length: IPV6 (8*4)+7
     *
     * @ORM\Column(type="string", name="last_login_client_ip", nullable=true, length=39, options={"default" = null})
     * @Assert\Ip(version="all")
     */
    protected $lastLoginClientIp;

    /**
     * full Name.
     *
     * @var string
     *
     * @ORM\Column(type="string", name="full_name", nullable=true, length=255, options={"default" = null})
     */
    protected $fullName;

    /**
     * phone.
     *
     * @var string
     *
     * @ORM\Column(type="string", name="phone", nullable=true, length=100, options={"default" = null})
     * @Assert\Regex("/^\d+$/")
     */
    protected $phone;

    /**
     * create Date time.
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="create_datetime")
     */
    private $createDatetime;

    /**
     * update Date time.
     *
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
     * override original setter, with set username with email.
     *
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
     * Set lastLoginClientIp.
     *
     * @param string $lastLoginClientIp
     *
     * @return User
     */
    public function setLastloginClientIp($lastLoginClientIp)
    {
        $this->lastLoginClientIp = $lastLoginClientIp;

        return $this;
    }

    /**
     * Get lastLoginClientIp.
     *
     * @return string
     */
    public function getLastloginClientIp()
    {
        return $this->lastLoginClientIp;
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
