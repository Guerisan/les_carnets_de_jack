<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Tool\ControllerTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("crew/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $illustration = $form['profile_pic']->getData();

            if ($illustration) {
                $this->handleImage($user, $illustration);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_cabin', [
                'slug' => $user->getSlug()
            ]);
        }

        return $this->render('crew/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    function handleImage($user, $illustration)
    {
        $illustrationName = ControllerTool::generateUniqueFileName() . '.' . $illustration->guessExtension();
        try {
            $illustration->move(
                $this->getParameter('uploads_directory').'/img/user/', $illustrationName
            );
        } catch (FileException $e) {
            $this->addFlash("exception", $e);
        }
        $user->setProfilePic($illustrationName);
    }

}