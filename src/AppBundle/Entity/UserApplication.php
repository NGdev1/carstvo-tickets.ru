<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserApplication
 *
 * @ORM\Table(name="user_applications")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserApplicationRepository")
 */
class UserApplication
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="spectacleDate", type="date")
     */
    private $spectacleDate;

    /**
     * @var string
     *
     * @ORM\Column(name="spectacleTime", type="string", length=255)
     */
    private $spectacleTime;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfEaters", type="integer")
     */
    private $numberOfEaters;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="checkedByAdmin", type="boolean")
     */
    private $checkedByAdmin;

    /**
     * @var Ticket[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="application", cascade="remove")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tickets;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var int
     *
     * @ORM\Column(name="paid", type="integer")
     */
    private $paid;

    /**
     * @var int
     *
     * @ORM\Column(name="debt", type="integer")
     */
    private $debt;

    /**
     * @var SpectaclePeriod
     *
     * @ORM\ManyToOne(targetEntity="SpectaclePeriod", inversedBy="applications", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spectaclePeriod;


    /**
     * UserApplication constructor.
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set applicationDate
     *
     * @param \DateTime $spectacleDate
     *
     * @return UserApplication
     */
    public function setSpectacleDate($spectacleDate)
    {
        $this->spectacleDate = $spectacleDate;

        return $this;
    }

    /**
     * Get applicationDate
     *
     * @return \DateTime
     */
    public function getSpectacleDate()
    {
        return $this->spectacleDate;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return UserApplication
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return UserApplication
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set numberOfEaters
     *
     * @param integer $numberOfEaters
     *
     * @return UserApplication
     */
    public function setNumberOfEaters($numberOfEaters)
    {
        $this->numberOfEaters = $numberOfEaters;

        return $this;
    }

    /**
     * Get numberOfEaters
     *
     * @return int
     */
    public function getNumberOfEaters()
    {
        return $this->numberOfEaters;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return UserApplication
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set numberOfPaid
     *
     * @param integer $paid
     *
     * @return UserApplication
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get numberOfPaid
     *
     * @return int
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set checkedByAdmin
     *
     * @param boolean $checkedByAdmin
     *
     * @return UserApplication
     */
    public function setCheckedByAdmin($checkedByAdmin)
    {
        $this->checkedByAdmin = $checkedByAdmin;

        return $this;
    }

    /**
     * Get checkedByAdmin
     *
     * @return bool
     */
    public function getCheckedByAdmin()
    {
        return $this->checkedByAdmin;
    }

    /**
     * @return Ticket[]
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param Ticket[] $tickets
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
    }

    public function addTicket(Ticket $ticket)
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
        }
    }

    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * @return SpectaclePeriod
     */
    public function getSpectaclePeriod()
    {
        return $this->spectaclePeriod;
    }

    /**
     * @param SpectaclePeriod $spectaclePeriod
     */
    public function setSpectaclePeriod($spectaclePeriod)
    {
        $this->spectaclePeriod = $spectaclePeriod;
    }

    /**
     * @return string
     */
    public function getSpectacleTime()
    {
        return $this->spectacleTime;
    }

    /**
     * @param string $spectacleTime
     */
    public function setSpectacleTime($spectacleTime)
    {
        $this->spectacleTime = $spectacleTime;
    }

    /**
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getDebt()
    {
        return $this->debt;
    }

    /**
     * @param int $debt
     */
    public function setDebt($debt)
    {
        $this->debt = $debt;
    }
}

