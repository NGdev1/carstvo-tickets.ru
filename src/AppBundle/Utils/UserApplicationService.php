<?php

namespace AppBundle\Utils;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\UserApplication;
use AppBundle\Repository\PriceCategoryRepository;
use AppBundle\Repository\TicketRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use Exception;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;

class UserApplicationService
{
    /**
     * @param UserApplication $userApplication
     */
    public function calculateAndSetCost(UserApplication $userApplication)
    {
        $price = 0;
        $tickets = $userApplication->getTickets();

        //стоимость билетов
        for ($i = 0; $i < count($tickets); $i++) {
            $price += $tickets[$i]->getPriceCategory()->getPrice() * $tickets[$i]->getCount();
        }

        //стоимость питания, если нужно включить
        //$price += $userApplication->getNumberOfEaters() * $userApplication->getSpectaclePeriod()->getCostOfFood();

        $userApplication->setCost($price);
    }


    public function addTicketsCount(
        $ticketsArray,
        PriceCategoryRepository $priceCategoryRepository,
        ObjectManager $entityManager,
        UserApplication $userApplication
    ) {

        $keys = array_keys($ticketsArray);

        if (count($keys) == 0) {
            throw new Exception("Нет билетов");
        }

        //проходим по всем категориям
        for ($i = 0; $i < count($keys); $i++) {
            //и ставим кол-во билетов с ценовой категорией
            $ticketsCount = $ticketsArray[$keys[$i]];
            if ($ticketsCount == 0 || $ticketsCount == null) {
                break;
            }

            $ticket = new Ticket();

            $ticket->setPriceCategory($priceCategoryRepository->find($keys[$i]));
            $ticket->setCount($ticketsArray[$keys[$i]]);

            $entityManager->persist($ticket);
            $ticket->setApplication($userApplication);
            $userApplication->addTicket($ticket);
        }
    }

    public function setTicketsCount(
        $ticketsArray,
        TicketRepository $ticketRepository,
        PriceCategoryRepository $priceCategoryRepository,
        ObjectManager $entityManager,
        UserApplication $userApplication,
        LoggerInterface $logger
    ) {

        $keys = array_keys($ticketsArray);

        if (count($keys) == 0) {
            throw new Exception("Нет билетов");
        }

        //проходим по всем категориям
        for ($i = 0; $i < count($keys); $i++) {
            //и ставим кол-во билетов с ценовой категорией
            $ticketsCount = $ticketsArray[$keys[$i]];
            if ($ticketsCount == 0 || $ticketsCount == null) {
                break;
            }

            $priceCategory = $priceCategoryRepository->find($keys[$i]);
            try {
                $ticket = $ticketRepository->findInApplicationWithCategory($userApplication, $priceCategory);
            } catch (NoResultException $exception){
                $ticket = new Ticket();

                $ticket->setPriceCategory($priceCategory);
                $ticket->setApplication($userApplication);
                $userApplication->addTicket($ticket);

                $entityManager->persist($ticket);
            }

            $ticket->setCount($ticketsArray[$keys[$i]]);
        }
    }
}
