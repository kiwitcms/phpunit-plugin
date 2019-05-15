<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use DateTime;

class User extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     */
    private $lastLogin;

    /**
     * @var bool
     */
    private $isSuperuser;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $isStaff;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var DateTime
     */
    private $dateJoined;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getLastLogin(): ? DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $value)
    {
        $this->lastLogin = $value;
    }

    public function getIsSuperuser() : ? bool
    {
        return $this->isSuperuser;
    }

    public function setIsSuperuser(bool $value)
    {
        $this->isSuperuser = $value;
    }

    public function getUsername() : ? string
    {
        return $this->username;
    }

    public function setUsername(string $value)
    {
        $this->username = $value;
    }

    public function getFirstName() : ? string
    {
        return $this->firstName;
    }

    public function setFirstName(string $value)
    {
        $this->firstName = $value;
    }

    public function getLastName() : ? string
    {
        return $this->lastName;
    }

    public function setLastName(string $value)
    {
        $this->lastName = $value;
    }

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $value)
    {
        $this->email = $value;
    }

    public function getIsStaff() : ? bool
    {
        return $this->isStaff;
    }

    public function setIsStaff(bool $value)
    {
        $this->isStaff = $value;
    }

    public function getIsActive() : ? bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $value)
    {
        $this->isActive = $value;
    }

    public function getDateJoined() : ? DateTime
    {
        return $this->dateJoined;
    }

    public function setDateJoined(DateTime $value)
    {
        $this->dateJoined = $value;
    }
}
