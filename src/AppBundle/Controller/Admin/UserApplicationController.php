<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\UserApplication;
use AppBundle\Form\UserApplicationType;
use AppBundle\Repository\TicketRepository;
use Exception;
use Psr\Log\LoggerInterface;
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
class UserApplicationController extends Controller
{
    /**
     * @Route("user-applications/add", name="add_user_application_by_admin_action")
     * @Method("POST")
     */
    public function addUserApplicationAction(Request $request)
    {
        $request->setRequestFormat('json');

        $userApplication = new UserApplication();

        $form = $this->createForm(UserApplicationType::class, $userApplication);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new Exception("Введены неверные данные формы");
        }

        $entityManager = $this->getDoctrine()->getManager();

        if (!$request->get('spectacle_id')) {
            throw new Exception("Спектакль не определен");
        }

        $userApplication->setCheckedByAdmin($request->get('checkedByAdmin'));

        $spectacle = $entityManager->getRepository(SpectaclePeriod::class)->find($request->get('spectacle_id'));
        $userApplication->setSpectaclePeriod($spectacle);

        $userApplication->setComment($request->get('comment'));
        $userApplication->setPaid(0);

        $userApplication->setCheckedByAdmin(true);

        if (!$request->get('tickets')) {
            throw new Exception("Нет билетов");
        }

        $ticketsArray = $request->get('tickets');
        $priceCategoryRepository = $entityManager->getRepository(PriceCategory::class);

        $this->get('user_application_service')->addTicketsCount(
            $ticketsArray,
            $priceCategoryRepository,
            $entityManager,
            $userApplication
        );

        $this->get('user_application_service')->calculateAndSetCost($userApplication);

        $userApplication->setDebt($userApplication->getCost());
        $userApplication->setPaid(0);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userApplication);
        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("user-applications/", name="user_application_edit_action")
     * @Method("DELETE")
     */
    public function userApplicationDeleteAction(Request $request)
    {
        $request->setRequestFormat('json');

        $id = $request->get('id');

        $entityManager = $this->getDoctrine()->getManager();
        $userApplication = $entityManager->getRepository(UserApplication::class)->find($id);
        $entityManager->remove($userApplication);

        $entityManager->flush();
        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("user-applications/")
     * @Method("POST")
     */
    public function userApplicationEditFromTableAction(Request $request)
    {
        $request->setRequestFormat('json');

        $entityManager = $this->getDoctrine()->getManager();

        $id = $request->get('id');

        $userApplication = $entityManager->getRepository(UserApplication::class)->find($id);

        $spectacleDate = new \DateTime($request->get('spectacleDate'));
        $spectacleTime = $request->get('spectacleTime');
        $fullName = $request->get('fullName');
        $phone = $request->get('phone');
        $checkedByAdmin = $request->get('checkedByAdmin');

        if ($spectacleDate == null ||
            $spectacleTime == null ||
            $fullName == null ||
            $phone == null ||
            $checkedByAdmin == null
        ) {
            throw new Exception("Введены неверные данные");
        }

        $userApplication->setSpectacleDate($spectacleDate);
        $userApplication->setSpectacleTime($spectacleTime);
        $userApplication->setFullName($fullName);
        $userApplication->setPhone($phone);
        $userApplication->setCheckedByAdmin($checkedByAdmin);

        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("user-applications/{id}/edit", name="user_application_edit_form_action")
     * @Method("POST")
     */
    public function userApplicationEditAction(Request $request, UserApplication $userApplication, LoggerInterface $logger)
    {
        $request->setRequestFormat('json');

        $form = $this->createForm(UserApplicationType::class, $userApplication);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new Exception("Введены неверные данные формы");
        }

        $entityManager = $this->getDoctrine()->getManager();

        if (!$request->get('spectacle_id')) {
            throw new Exception("Спектакль не определен");
        }

        $userApplication->setCheckedByAdmin($request->get('checkedByAdmin') == 'on');

        $spectacle = $entityManager->getRepository(SpectaclePeriod::class)->find($request->get('spectacle_id'));
        $userApplication->setSpectaclePeriod($spectacle);

        $userApplication->setComment($request->get('comment'));
        $userApplication->setPaid(0);

        if (!$request->get('tickets')) {
            throw new Exception("Нет билетов");
        }

        $ticketsArray = $request->get('tickets');

        $this->get('user_application_service')->setTicketsCount(
            $ticketsArray,
            $entityManager->getRepository(Ticket::class),
            $entityManager->getRepository(PriceCategory::class),
            $entityManager,
            $userApplication
        );

        $this->get('user_application_service')->calculateAndSetCost($userApplication);

        $userApplication->setDebt($userApplication->getCost());
        $userApplication->setPaid(0);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }
}
