<?php

namespace AppBundle\Utils;


use AppBundle\Entity\UserApplication;

class PriceCalculator
{
    /**
     * @param UserApplication $userApplication
     */
    public function calculateAndSetCost(UserApplication $userApplication){
        $price = 0;
        $tickets = $userApplication->getTickets();

        //стоимость билетов
        for($i = 0; $i < count($tickets); $i++) {
            $price += $tickets[$i]->getPriceCategory()->getPrice() * $tickets[$i]->getCount();
        }

        //стоимость питания, если нужно включить
        //$price += $userApplication->getNumberOfEaters() * $userApplication->getSpectaclePeriod()->getCostOfFood();

        $userApplication->setCost($price);
    }
}
