<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\JournalEntry;
use App\Entity\User;
use App\Form\ContactType;
use App\Notification\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */

    public function index()
    {


        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $lastEntry = $depot->findBy(array(),array('id'=>'DESC'),1,0);
        if ($lastEntry !== []){
            $lastEntry = $lastEntry[0];
            $chapo = substr(strip_tags($lastEntry->getContent()), 0, 300);
        } else{
            $chapo = "";
        }

        return $this->render('/pages/home.html.twig', [
            "last_entry" =>$lastEntry,
            "chapo" => $chapo
        ]);
    }

    /**
     * @Route("/Contact", name="contact")
     */

    public function contact(Request $request, Notification $notification)
    {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($_POST["email"] === ""){

                $notification->notify($contact);
                $this->addFlash('email', "Votre message a bien été envoyé !");

            }else{
                $this->addFlash('email', "Suck my bot =)");
            }
            $this->redirectToRoute('contact');

        }

        return $this->render('/pages/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Mentions-Legales", name="legals")
     */

    public function legals()
    {
        return $this->render('/pages/legals.html.twig');
    }


}