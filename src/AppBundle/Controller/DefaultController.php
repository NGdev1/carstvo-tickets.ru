<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SpectaclePeriod;
use AppBundle\Entity\UserApplication;
use AppBundle\Form\UserApplicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}
