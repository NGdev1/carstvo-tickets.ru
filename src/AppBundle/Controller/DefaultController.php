<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Entity\UserApplication;
use AppBundle\Form\UserApplicationType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    private function siteRenderFullView($parameters)
    {
        return $this->render('site/index.html.twig', $parameters);
    }

    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function showPosterAction(Request $request)
    {
        $spectaclePeriods = $this->getDoctrine()->getRepository(SpectaclePeriod::class)->findAll();

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'site/poster.html.twig',
                [
                    'spectaclePeriods' => $spectaclePeriods
                ]
            );
        } else {
            return $this->siteRenderFullView([
                'spectaclePeriods' => $spectaclePeriods,
                'content' => 'poster'
            ]);
        }
    }

    /**
     * @Route("/{id}", name="show_spectacle")
     * @Method("GET")
     */
    public function showSpectacleAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $form = $this->createForm(UserApplicationType::class, null, array(
                'action' => $this->generateUrl('add_user_application_action'))
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'site/spectacle.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'form' => $form->createView()
                ]
            );
        } else {
            return $this->siteRenderFullView([
                'spectaclePeriod' => $spectaclePeriod,
                'content' => 'spectacle',
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("user-applications/add", name="add_user_application_action")
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

        $userApplication->setCheckedByAdmin(false);

        $spectacle = $entityManager->getRepository(SpectaclePeriod::class)->find($request->get('spectacle_id'));
        $userApplication->setSpectaclePeriod($spectacle);

        $userApplication->setComment($request->get('comment'));
        $userApplication->setPaid(0);

        $userApplication->setCheckedByAdmin(false);

        if (!$request->get('tickets')) {
            throw new Exception("Нет билетов");
        }

        $ticketsArray = $request->get('tickets');
        $priceCategoryRepository = $entityManager->getRepository(PriceCategory::class);

        $this->get('user_application_service')->addTicketsCount($ticketsArray,
            $priceCategoryRepository,
            $entityManager,
            $userApplication);

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
}
