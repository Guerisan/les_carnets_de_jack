<?php


namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Contribution;
use App\Form\CommentFormType;
use App\Form\ContributionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @Route("/Contributions")
 */
class ContributionController extends AbstractController
{
    /**
     * @Route("/", name="contribution_index")
     */

    public function contributionIndex()
    {
        $depot = $this->getDoctrine()->getRepository(Contribution::class);
        $contributions = $depot->findAll();


        return $this->render('/contributions/index_contribution.html.twig', [
            'contributions' => $contributions,]);
    }

    /**
     * @Route("/Recit/{id}", name="contribution_single")
     */

    public function contribution(Contribution $contribution, Request $request)
    {
        $depot = $this->getDoctrine()->getRepository(Commentary::class);
        $comments = $depot->findBy(['contributionRelation' => $contribution->getId()]);

        $comment = new Commentary();
        $comment->setContributionRelation($contribution);
        $comment->setDate();

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $author = $user->getUsername();
            $comment->setAuthor($author);
        } else {
            $comment->setAuthor('Anonyme');
        }

        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $comment = $commentForm->getData();
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($comment);
            $gestionnaire->flush();

            $this->addFlash("blog", "Le commentaire a bien été posté");

            return $this->redirect($request->getUri());
        }

        return $this->render("/contributions/contribution.html.twig", [
            "contribution" => $contribution,
            'comments' => $comments,
            "commentForm" => $commentForm->createView()]);
    }

    /**
     * @Route("/Recit/{id}/Validation", name="contribution_validation")
     * @Security("is_granted('ROLE_ADMIN')")
     */


    public function contributionValidation($id)
    {
        $depot = $this->getDoctrine()->getRepository(Contribution::class);
        $contribution = $depot->find($id);

        $contribution->setStatus('published');

        if ($contribution->getStatus() === 'published') {
            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($contribution);

            $gestionnaire->flush();

            $this->addFlash("contribution", "Contribution validée");
        } else {
            $this->addFlash("contribution", "Hmmm, y'a un souci");
        }

        return $this->redirect('/Contributions');
    }

    /**
     * @Route("/Recit/{id}/Suppression", name="contribution_suppression")
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function contributionSuppression($id)
    {
        $depot = $this->getDoctrine()->getRepository(Contribution::class);
        $contribution = $depot->find($id);

        $deletedImages = [];

        $imageMain = $contribution->getMainImage();
        $gallery1 = $contribution->getImageGallery1();
        $gallery2 = $contribution->getImageGallery2();
        $gallery3 = $contribution->getImageGallery3();

        $deletedImages[] = $imageMain;
        $deletedImages[] = $gallery1;
        $deletedImages[] = $gallery2;
        $deletedImages[] = $gallery3;

        foreach ($deletedImages as $image){
            if ($image !== null){
                unlink($this->getParameter('images_contribution_directory').$image);
            }
    }

        $gestionnaire = $this->getDoctrine()->getManager();
        $gestionnaire->remove($contribution);
        $gestionnaire->flush();

        $this->addFlash("contribution", "Contribution supprimée");

        return $this->redirect('/Contributions');
    }

    /**
     * @Route("/Creation", name="contribution_creation")
     */

    public
    function makeContribution(Request $request)
    {
        $cont = new Contribution();
        $cont->setDate();

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $author = $user->getUsername();

            $cont->setAuthor($author);
        } else {
            $cont->setAuthor('Anonyme');
        }

        $form = $this->createForm(ContributionType::class, $cont);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageMain = $cont->getMainImage();
            $gallery1 = $cont->getImageGallery1();
            $gallery2 = $cont->getImageGallery2();
            $gallery3 = $cont->getImageGallery3();

            if ($imageMain !== null) {

                $imageMainName = $this->generateUniqueFileName() . '.' . $imageMain->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $imageMain->move(
                        $this->getParameter('images_contribution_directory'), $imageMainName
                    );
                } catch (FileException $e) {
                    $this->addFlash("contribution", "Il y a eu un problème lors de l'envoi de l'image principale.");
                }
            $cont->setMainImage($imageMainName);
            }

            if ($gallery1 !== null) {
                $gallery1Name = $this->generateUniqueFileName() . '.' . $gallery1->guessExtension();
                try {
                    $gallery1->move(
                        $this->getParameter('images_contribution_directory'), $gallery1Name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            $cont->setImageGallery1($gallery1Name);
            }

            if ($gallery2 !== null) {
                $gallery2Name = $this->generateUniqueFileName() . '.' . $gallery2->guessExtension();
                try {
                    $gallery2->move(
                        $this->getParameter('images_contribution_directory'), $gallery2Name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            $cont->setImageGallery2($gallery2Name);
            }

            if ($gallery3 !== null) {
                $gallery3Name = $this->generateUniqueFileName() . '.' . $gallery3->guessExtension();
                try {
                    $gallery3->move(
                        $this->getParameter('images_contribution_directory'), $gallery3Name
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            $cont->setImageGallery3($gallery3Name);
            }

            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($cont);

            $gestionnaire->flush();

            $this->addFlash("contribution", "Merci d'avoir partagé votre récit ! Je vous recontacterai pour la suite");

            return $this->redirect($request->getUri());
        }

        return $this->render('/contributions/contribution_creation.html.twig', [
            'contribution' => $cont,
            'contForm' => $form->createView()
        ]);
    }

    /**
     * @return string
     */
    private
    function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}