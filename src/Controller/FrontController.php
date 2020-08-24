<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\JournalEntry;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */

    public function index(){

        /*
        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $lastEntry = $depot->findOneBy(array('id' => 'DESC'));
        */
        return $this->render('/pages/home.html.twig');
    }

    /**
     * @Route("/Contact", name="contact")
     */

    public function contact(Request $request, ContactNotification $notification){

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $notification->notify($contact);
            $this->addFlash('email', "Votre message a bien été envoyé !");

            $this->redirectToRoute('contact');
        }

        return $this->render('/pages/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Mentions-Legales", name="legals")
     */

    public function legals(){
        return $this->render('/pages/legals.html.twig');
    }

    /**
     * @Route("/cabin/{user}", name="user_cabin")
     */

    function user_cabin(){
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->render('pages/user.html.twig', [
            ]);
        } else{
            return $this->render('/security/login.html.twig');
        }
    }
}