<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Entity\UserApplication;
use AppBundle\Form\SpectaclePeriodType;
use AppBundle\Form\UserApplicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/boards/spectacle-periods")
     * @Method("GET")
     */
    public function showSpectaclePeriodsAction(Request $request)
    {
        $spectaclePeriods = $this->getDoctrine()->getRepository(SpectaclePeriod::class)->findAll();

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/boards/spectacle_periods.html.twig',
                [
                    'spectaclePeriods' => $spectaclePeriods,
                ]
            );
        } else {
            return $this->indexAction();
        }
    }

    /**
     * @Route("/boards/spectacle-periods/add")
     * @Method("GET")
     */
    public function spectaclePeriodsAddBoardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(SpectaclePeriodType::class, null, array(
                'action' => $this->generateUrl('add_spectacle_period_action'))
            );

            return $this->render('admin/boards/add_spectacle_period.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->indexAction();
        }
    }

    /**
     * @Route("/boards/price-categories")
     * @Method("GET")
     */
    public function showPriceCategoriesAction(Request $request)
    {
        $priceCategories = $this->getDoctrine()->getRepository(PriceCategory::class)->findAll();

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/boards/price_categories.html.twig',
                [
                    'priceCategories' => $priceCategories,
                ]
            );
        } else {
            return $this->indexAction();
        }
    }

    /**
     * @Route("/boards/price-categories/add")
     * @Method("GET")
     */
    public function priceCategoryAddBoardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/boards/add_price_category.html.twig');
        } else {
            return $this->indexAction();
        }
    }

    /**
     * @Route("/boards/user-applications")
     * @Method("GET")
     */
    public function showUserApplicationsAction(Request $request)
    {
        $userApplications = $this->getDoctrine()->getRepository(UserApplication::class)->findNotChecked();

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/boards/user_applications.html.twig',
                [
                    'userApplications' => $userApplications,
                ]
            );
        } else {
            return $this->indexAction();
        }
    }

    /**
     * @Route("/boards/user-applications/add")
     * @Method("GET")
     */
    public function userApplicationAddBoardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(UserApplicationType::class, null, array(
                'action' => $this->generateUrl('add_user_application_action'))
            );

            return $this->render('admin/boards/add_user_application.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->indexAction();
        }
    }
}
