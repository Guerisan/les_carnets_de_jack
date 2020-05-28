<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\CommentFormType;
use App\Form\SourceFormType;
use App\Entity\Commentary;
use App\Form\PublishTaleType;
use App\Entity\Source;
use App\Entity\Recit;

/**
 * @Route("/Recits")
 */
class TaleController extends AbstractController
{

    /**
     * @Route("/", name="tales_list")
     */

    public function taleslist(Request $request)
    {
        $depot = $this->getDoctrine()->getRepository(Recit::class);
        $recits = $depot->findAll();

        $depot = $this->getDoctrine()->getRepository(Source::class);
        $sources = $depot->findAll();

        $tale = new Recit();

        $form = $this->createForm(PublishTaleType::class, $tale);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($tale);

            foreach ($tale->getSources() as $source ){

                $source->addRelation($tale);

            }

            $gestionnaire->flush();

            $this->addFlash("tale", "Le récit a bien été inséré.");

            return $this->redirect($request->getUri());

        }

        return $this->render('/recits/index_recits.html.twig', [
            'recits' => $recits,
            'sources' => $sources,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Source", name="source_register")
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function setSource(Request $request){

        $source = new Source();

        $sourceForm = $this->createForm(SourceFormType::class, $source);

        $sourceForm->handleRequest($request);

        if ($sourceForm->isSubmitted() && $sourceForm->isValid()) {

            $source = $sourceForm->getData();

            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($source);

            $gestionnaire->flush();

            $this->addFlash("source", "La source a bien été postée");

            return $this->redirect($request->getUri());

        }
        return $this->render('/recits/source_register.html.twig', [
            'sourceForm' => $sourceForm->createView()]);
    }

    /**
     * @Route("/{vue}", name="recit")
     */

    public function tale(Recit $recit, Request $request)
    {

        $depot = $this->getDoctrine()->getRepository(Commentary::class);
        $comments = $depot->findBy(['relation' => $recit->getId()]);

        $sources = $recit->getSources();

        $comment = new Commentary();
        $comment->setRelation($recit);
        $comment->setDate();

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $author = $user->getUsername();

            $comment->setAuthor($author);

        }else{

            $comment->setAuthor('Anonyme');

        }

        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($comment);

            $gestionnaire->flush();

            $this->addFlash("recit", "Votre commentaire a bien été posté");

            return $this->redirect($request->getUri());
        }

        return $this->render('/recits/base_recit.html.twig', [
            'recit' => $recit,
            'sources' => $sources,
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
            ]);
    }
}