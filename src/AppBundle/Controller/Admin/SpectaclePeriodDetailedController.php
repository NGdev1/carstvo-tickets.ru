<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\UserApplication;
use AppBundle\Form\UserApplicationType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Form\SpectaclePeriodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SpectaclePeriodDetailedController extends Controller
{
    private function spectaclePeriodRenderFullView($parameters)
    {
        return $this->render('admin/spectaclePeriod/index.html.twig', $parameters);
    }

    /**
     * @Route("/spectacle-period/{id}", name="show_spectacle_period")
     * @Method("GET")
     */
    public function spectaclesPeriodAction(SpectaclePeriod $spectaclePeriod)
    {
        return $this->spectaclePeriodRenderFullView(['content' => 'no', 'spectaclePeriod' => $spectaclePeriod]);
    }

    /**
     * @Route("/spectacle-period/{id}/edit", name="spectacle_period_edit_view")
     * @Method("GET")
     */
    public function spectaclesPeriodEditAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $form = $this->createForm(
            SpectaclePeriodType::class,
            $spectaclePeriod,
            array(
                'action' => $this->generateUrl('spectacle_periods_edit_action'),
                'method' => 'POST',
            )
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/edit.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'form' => $form->createView(),
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'content' => 'edit',
                    'form' => $form->createView(),
                ]
            );
        }
    }

    /**
     * @Route("/spectacle-period/{id}/all-applications", name="spectacle_period_all_applications")
     * @Method("GET")
     */
    public function allApplicationsAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $userApplications = $this->getDoctrine()->getRepository(UserApplication::class)->findAllFor($spectaclePeriod);

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/userApplications.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplications' => $userApplications,
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplications' => $userApplications,
                    'content' => 'userApplications',
                ]
            );
        }
    }

    /**
     * @Route("/spectacle-period/{id}/accepted-applications", name="spectacle_period_accepted_applications")
     * @Method("GET")
     */
    public function acceptedApplicationsAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $userApplications = $this->getDoctrine()->getRepository(UserApplication::class)->findCheckedFor(
            $spectaclePeriod
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/userApplications.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplications' => $userApplications,
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplications' => $userApplications,
                    'content' => 'acceptedApplications',
                ]
            );
        }
    }

    /**
     * @Route("/spectacle-period/{id}/not-accepted", name="spectacle_period_not_accepted_applications")
     * @Method("GET")
     */
    public function notAcceptedApplicationsAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $userApplications = $this->getDoctrine()->getRepository(UserApplication::class)->findNotCheckedFor(
            $spectaclePeriod
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/userApplications.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplications' => $userApplications,
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplications' => $userApplications,
                    'content' => 'notAcceptedApplications',
                ]
            );
        }
    }

    /**
     * @Route("/spectacle-period/{id}/add-application", name="spectacle_period_add_user_application")
     * @Method("GET")
     */
    public function addUserApplicationsAction(Request $request, SpectaclePeriod $spectaclePeriod)
    {
        $form = $this->createForm(
            UserApplicationType::class,
            null,
            array(
                'action' => $this->generateUrl('add_user_application_by_admin_action'),
            )
        );


        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/addUserApplication.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'form' => $form->createView(),
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'form' => $form->createView(),
                    'content' => 'addUserApplication',
                ]
            );
        }
    }

    /**
     * @Route("/spectacle-period/{spectacleId}/user-application/{userApplicationId}/edit", name="spectacle_period_edit_user_application")
     * @ParamConverter("spectaclePeriod", options={"mapping": {"spectacleId": "id"}})
     * @ParamConverter("userApplication",  options={"mapping": {"userApplicationId":  "id"}})
     * @Method("GET")
     */
    public function editUserApplicationsAction(
        Request $request,
        SpectaclePeriod $spectaclePeriod,
        UserApplication $userApplication
    ) {
        $form = $this->createForm(
            UserApplicationType::class,
            $userApplication,
            array(
                'action' => $this->generateUrl('user_application_edit_form_action', ['id' => $userApplication->getId()]),
            )
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/editUserApplication.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplication' => $userApplication,
                    'form' => $form->createView(),
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplication' => $userApplication,
                    'form' => $form->createView(),
                    'content' => 'editUserApplication',
                ]
            );
        }
    }

    /**
     * @Route("/spectacle-period/{spectacleId}/user-application/{userApplicationId}", name="spectacle_period_show_user_application")
     * @ParamConverter("spectaclePeriod", options={"mapping": {"spectacleId": "id"}})
     * @ParamConverter("userApplication",  options={"mapping": {"userApplicationId":  "id"}})
     * @Method("GET")
     */
    public function showUserApplicationsAction(
        Request $request,
        SpectaclePeriod $spectaclePeriod,
        UserApplication $userApplication
    ) {
        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/spectaclePeriod/showUserApplication.html.twig',
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplication' => $userApplication,
                ]
            );
        } else {
            return $this->spectaclePeriodRenderFullView(
                [
                    'spectaclePeriod' => $spectaclePeriod,
                    'userApplication' => $userApplication,
                    'content' => 'showUserApplication',
                ]
            );
        }
    }
}
