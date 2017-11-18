<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Form\SpectaclePeriodType;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SpectaclePeriodController extends Controller
{
    /**
     * @Route("spectacle-periods/add", name="add_spectacle_period_action")
     * @Method("POST")
     */
    public function addSpectaclePeriodAction(Request $request)
    {
        $request->setRequestFormat('json');

        $spectaclesPeriod = new SpectaclePeriod();

        $form = $this->createForm(SpectaclePeriodType::class, $spectaclesPeriod);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new Exception("Введены неверные данные");
        }

        if ($spectaclesPeriod->getStartDate()->getTimestamp() > $spectaclesPeriod->getEndDate()->getTimestamp()) {
            throw new Exception("Дата начала должна быть раньше даты окончания");
        }

        $entityManager = $this->getDoctrine()->getManager();

        $priceCategories = json_decode($request->get('priceCategories'));

        $priceCategoriesRepository = $entityManager->getRepository(PriceCategory::class);
        for ($i = 0; $i < count($priceCategories); $i++) {
            $spectaclesPeriod->addTicketPrice($priceCategoriesRepository->find($priceCategories[$i]));
        }

        $entityManager->persist($spectaclesPeriod);
        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("spectacle-periods/", name="spectacle_periods_edit_action")
     * @Method("DELETE")
     */
    public function spectaclePeriodDeleteAction(Request $request)
    {
        $request->setRequestFormat('json');

        $id = $request->get('id');

        $entityManager = $this->getDoctrine()->getManager();
        $spectaclePeriod = $entityManager->getRepository(SpectaclePeriod::class)->find($id);
        $entityManager->remove($spectaclePeriod);

        $entityManager->flush();
        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("spectacle-periods/")
     * @Method("POST")
     */
    public function spectaclePeriodEditFromTableAction(Request $request)
    {
        $request->setRequestFormat('json');

        $entityManager = $this->getDoctrine()->getManager();

        $id = $request->get('id');

        $spectaclesPeriod = $entityManager->getRepository(SpectaclePeriod::class)->find($id);

        $name = $request->get('name');
        $startDate = new DateTime($request->get('startDate'));
        $endDate = new DateTime($request->get('endDate'));

        if ($name == null || $startDate == null || $endDate == null) {
            throw new Exception("Введены неверные данные");
        }

        if ($startDate->getTimestamp() > $endDate->getTimestamp()) {
            throw new Exception("Дата начала должна быть раньше даты окончания");
        }

        $spectaclesPeriod->setName($name);
        $spectaclesPeriod->setStartDate($startDate);
        $spectaclesPeriod->setEndDate($endDate);

        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("spectacle-periods/{id}/edit", name="spectacle_periods_edit_form_action")
     * @Method("POST")
     */
    public function spectaclePeriodEditAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $request->setRequestFormat('json');

        $form = $this->createForm(SpectaclePeriodType::class, $spectaclePeriod);

        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new Exception("Введены неверные данные");
        }

        if ($spectaclePeriod->getStartDate()->getTimestamp() > $spectaclePeriod->getEndDate()->getTimestamp()) {
            throw new Exception("Дата начала должна быть раньше даты окончания");
        }

        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }
}
