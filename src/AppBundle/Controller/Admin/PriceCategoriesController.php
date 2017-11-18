<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PriceCategory;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\SpectaclesPeriod;
use AppBundle\Form\SpectaclesPeriodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PriceCategoriesController extends Controller
{
    /**
     * @Route("/price-categories/find/", name="find_price_categories_action")
     * @Method("GET")
     */
    public function findPriceCategoriesAction(Request $request)
    {
        $request->setRequestFormat('json');

        $query = $request->get('query');

        $entityManager = $this->getDoctrine()->getManager();

        if($query == '*') {
            $priceCategories = $entityManager->getRepository(PriceCategory::class)->findAll();
        } else {
            $priceCategories = $entityManager->getRepository(PriceCategory::class)->createQueryBuilder('c')
                ->where('c.name LIKE :query')
                ->setParameter('query', '%'.$query.'%')
                ->getQuery()->getArrayResult();
        }

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $result = array(
            'status' => 'ok',//ok | error
            'list' => $priceCategories
        );

        $jsonContent = $serializer->serialize($result, 'json');

        return new Response($jsonContent);
    }

    /**
     * @Route("/price-categories/add", name="add_price_category_action")
     * @Method("POST")
     */
    public function addPriceCategoryAction(Request $request)
    {
        $request->setRequestFormat('json');

        $name = $request->get('name');
        $price = $request->get('price');

        if ($name == null || $price == null) {
            throw new Exception("Введены неверные данные");
        }

        $priceCategory = new PriceCategory();

        $priceCategory->setName($name);
        $priceCategory->setPrice($price);

        $em = $this->getDoctrine()->getManager();
        $em->persist($priceCategory);
        $em->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("/price-categories", name="price_categories_edit_action")
     * @Method("POST")
     */
    public function priceCategoryEditAction(Request $request)
    {
        $request->setRequestFormat('json');
        $id = $request->get('id');
        $name = $request->get('name');
        $price = $request->get('price');

        $entityManager = $this->getDoctrine()->getManager();
        $priceCategory = $entityManager->getRepository(PriceCategory::class)->find($id);
        $priceCategory->setName($name);
        $priceCategory->setPrice($price);
        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("/price-categories")
     * @Method("DELETE")
     */
    public function priceCategoryDeleteAction(Request $request)
    {
        $request->setRequestFormat('json');
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $priceCategory = $entityManager->getRepository(PriceCategory::class)->find($id);
        $entityManager->remove($priceCategory);
        $entityManager->flush();
        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }
}
