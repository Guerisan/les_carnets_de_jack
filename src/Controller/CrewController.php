<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CrewController extends AbstractController
{
    /**
     * @Route("/cabin/{slug}", name="user_cabin")
     */

    function user_cabin(User $user = null)
    {

        if ($user === null) {
            $user = $this->getUser();
        }

        return $this->render('crew/user.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/crew", name="crew_list")
     * @Security("is_granted('ROLE_ADMIN')")
     */

    function users_list()
    {
        $depot = $this->getDoctrine()->getRepository(User::class);
        $crew = $depot->findAll();
        return $this->render('crew/crew_list.html.twig', [
            'crew' => $crew,
        ]);
    }

}