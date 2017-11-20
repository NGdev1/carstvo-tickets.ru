<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\BirthdayBoy;
use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Entity\UserApplication;
use AppBundle\Form\SpectaclePeriodType;
use AppBundle\Form\UserApplicationType;
use DateTime;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
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
     * @Route("/boards/birthday-boys")
     * @Method("GET")
     */
    public function showBirthdayBoysAction(Request $request)
    {
        $birthdayBoys = $this->getDoctrine()->getRepository(BirthdayBoy::class)->findAll();

        $ages = array();
        foreach ($birthdayBoys as $birthdayBoy){
            $ages[$birthdayBoy->getId()] = $birthdayBoy->getBirthday()->diff(new DateTime('now'))->y;
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'admin/boards/birthday_boys.html.twig',
                [
                    'birthdayBoys' => $birthdayBoys,
                    'ages' => $ages
                ]
            );
        } else {
            return $this->indexAction();
        }
    }

    /**
     * @Route("/boards/birthday-boys/add")
     * @Method("GET")
     */
    public function birthdayBoyAddBoardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/boards/add_birthday_boy.html.twig');
        } else {
            return $this->indexAction();
        }
    }
}
