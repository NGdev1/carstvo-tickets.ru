<?php

namespace AppBundle\Entity;

use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpectaclePeriod
 *
 * @ORM\Table(name="spectacle_periods")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpectaclePeriodRepository")
 */
class SpectaclePeriod
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
     * @ORM\Column(name="startDate", type="date")
     * @ORM\JoinColumn(nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date")
     * @ORM\JoinColumn(nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @ORM\JoinColumn(nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="costOfFood", type="string", length=255)
     * @ORM\JoinColumn(nullable=true)
     */
    private $costOfFood;

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfTickets", type="integer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $numberOfTickets;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @ORM\JoinColumn(nullable=true)
     */
    private $description;

    /**
     * @var PriceCategory[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="PriceCategory", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketPrices;

    /**
     * @var UserApplication[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserApplication", mappedBy="spectaclePeriod", cascade="remove")
     * @ORM\JoinColumn(nullable=false)
     */
    private $applications;

    public function __construct()
    {
        $timezone = new DateTimeZone('Europe/Moscow');

        $this->startDate = new \DateTime();
        $this->startDate->setTimezone($timezone);
        $this->startDate->format('dd.MM.yyyy');

        $this->endDate = new \DateTime();
        $this->endDate->setTimezone($timezone);
        $this->endDate->format('dd.MM.yyyy');

        $this->ticketPrices = new ArrayCollection();
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return SpectaclePeriod
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return SpectaclePeriod
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SpectaclePeriod
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set costOfFood
     *
     * @param string $costOfFood
     *
     * @return SpectaclePeriod
     */
    public function setCostOfFood($costOfFood)
    {
        $this->costOfFood = $costOfFood;

        return $this;
    }

    /**
     * Get costOfFood
     *
     * @return string
     */
    public function getCostOfFood()
    {
        return $this->costOfFood;
    }

    /**
     * Set numberOfTickets
     *
     * @param integer $numberOfTickets
     *
     * @return SpectaclePeriod
     */
    public function setNumberOfTickets($numberOfTickets)
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    /**
     * Get numberOfTickets
     *
     * @return int
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * Get ticketPrices
     *
     * @return PriceCategory[]
     */
    public function getTicketPrices()
    {
        return $this->ticketPrices;
    }

    /**
     * @param PriceCategory[] $ticketPrices
     */
    public function setTicketPrices($ticketPrices)
    {
        $this->ticketPrices = $ticketPrices;
    }

    public function addTicketPrice(PriceCategory $priceCategory)
    {
        if (!$this->ticketPrices->contains($priceCategory)) {
            $this->ticketPrices->add($priceCategory);
        }
    }

    public function removeTicketPrice(PriceCategory $priceCategory)
    {
        $this->ticketPrices->removeElement($priceCategory);
    }

    /**
     * Get applications
     *
     * @return UserApplication[]
     */
    public function getUserApplications()
    {
        return $this->applications;
    }

    /**
     * @param UserApplication[] applications
     */
    public function setUserApplications($applications)
    {
        $this->applications = $applications;
    }

    public function addUserApplication(UserApplication $userApplication)
    {
        if (!$this->applications->contains($userApplication)) {
            $this->applications->add($userApplication);
        }
    }

    public function removeUserApplication(UserApplication $userApplication)
    {
        $this->applications->removeElement($userApplication);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}

