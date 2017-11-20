<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\BirthdayBoy;
use AppBundle\Entity\PriceCategory;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
class BirthdayBoysController extends Controller
{
    /**
     * @Route("/birthday-boys/add", name="add_birthday_boy_action")
     * @Method("POST")
     */
    public function addBirthdayBoyAction(Request $request)
    {
        $request->setRequestFormat('json');

        $fullName = $request->get('fullName');
        $phone = $request->get('phone');
        $birthday = new DateTime($request->get('birthday'));
        $comment = $request->get('comment');

        if ($fullName == null ||
            $phone == null ||
            $birthday == null
        ) {
            throw new Exception("Введены неверные данные");
        }

        $birthdayBoy = new BirthdayBoy();

        $birthdayBoy->setFullName($fullName);
        $birthdayBoy->setPhone($phone);
        $birthdayBoy->setBirthday($birthday);
        $birthdayBoy->setComment($comment);

        $em = $this->getDoctrine()->getManager();
        $em->persist($birthdayBoy);
        $em->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("/birthday-boys", name="birthday_boys_edit_from_table_action")
     * @Method("POST")
     */
    public function birthdayBoyEditFromTableAction(Request $request)
    {
        $request->setRequestFormat('json');
        $id = $request->get('id');

        $fullName = $request->get('fullName');
        $phone = $request->get('phone');
        $birthday = new DateTime($request->get('birthday'));
        $comment = $request->get('comment');

        $entityManager = $this->getDoctrine()->getManager();
        $birthdayBoy = $entityManager->getRepository(BirthdayBoy::class)->find($id);

        $birthdayBoy->setFullName($fullName);
        $birthdayBoy->setPhone($phone);
        $birthdayBoy->setBirthday($birthday);
        $birthdayBoy->setComment($comment);

        $entityManager->flush();

        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }

    /**
     * @Route("/birthday-boys")
     * @Method("DELETE")
     */
    public function birthdayBoyDeleteAction(Request $request)
    {
        $request->setRequestFormat('json');
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $birthdayBoy = $entityManager->getRepository(BirthdayBoy::class)->find($id);
        $entityManager->remove($birthdayBoy);
        $entityManager->flush();
        $result = array(
            'status' => 'ok',//ok | error
        );

        return new Response(json_encode($result));
    }
}
