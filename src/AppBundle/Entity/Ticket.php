<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var PriceCategory
     *
     * @ORM\ManyToOne(targetEntity="PriceCategory", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $priceCategory;

    /**
     * @var UserApplication
     *
     * @ORM\ManyToOne(targetEntity="UserApplication", inversedBy="tickets", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

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
     * Set count
     *
     * @param integer $count
     *
     * @return Ticket
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return PriceCategory
     */
    public function getPriceCategory()
    {
        return $this->priceCategory;
    }

    /**
     * @param PriceCategory $priceCategory
     */
    public function setPriceCategory($priceCategory)
    {
        $this->priceCategory = $priceCategory;
    }

    /**
     * @return UserApplication
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param UserApplication $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }
}

