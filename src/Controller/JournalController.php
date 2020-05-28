<?php


namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\JournalEntry;
use App\Form\CommentFormType;
use App\Form\ContributionType;
use App\Form\JournalEntryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/Journal")
 */

class JournalController extends AbstractController
{
    /**
     * @Route("/", name="journal_index")
     */

    public function journalIndex(){

        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $entries = $depot->findAll();

        return $this->render('/journal/journal_index.html.twig', [
            'entries' => $entries,
        ]);
    }

    /**
     * @Route("/Tag/{tag}", name="entry_category")
     */

    public function category($tag){
        $depot = $this->getDoctrine()->getRepository(JournalEntry::class)->findAll();
        $entries = [];

        foreach ($depot as $entry){
            if (in_array($tag, $entry->getTags())){
                $entries[] = $entry;
            };
        }
        $category = $tag;

        return $this->render('/journal/journal_index.html.twig', [
            'entries' => $entries,
            'category' => $category
        ]);
    }

    /**
     * @Route("/Nouveau", name="new_entry")
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function newEntry(Request $request){

        $entry = new JournalEntry();

        $entry->setDate(time());

        $form = $this->createForm(JournalEntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO :: Il n'est pas possible de remplir la date voulue dans le formulaire, Symfony rentre systématiquement le 01/01/1970
            $illustration = $entry->getIllustration();
            $slug = $entry->slugify($entry->getTitle());
            $entry->setSlug($slug);
            $entry->setDate(time());

            if ($illustration !== null){
                $illustrationName = $this->generateUniqueFileName(). '.' . $illustration->guessExtension();
                try {
                    $illustration->move(
                        $this->getParameter('images_journal_directory'), $illustrationName
                    );
                } catch (FileException $e) {
                    $this->addFlash("exception", $e);
                }
                $entry->setIllustration($illustrationName);
            }

            $tagsList = $form->getData();
            $tagsList = $tagsList->getTags();
            $tags = explode(";", $tagsList[0]);
            $entry->setTags($tags);

            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($entry);
            $gestionnaire->flush();

            $this->addFlash("journal", "Article posté !");

            return $this->redirect($request->getUri());

        }

        return $this->render('/journal/new_entry.html.twig', [
            'Form' => $form->createView()
        ]);
    }

/**
* @Route("/Edition/{id}", name="edit_entry"))
*
* @Security("is_granted('ROLE_ADMIN')")
*/

    public function endit_entry($id, Request $request)
    {

        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $entry = $depot->find($id);

        $form = $this->createForm(JournalEntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $illustration = $entry->getIllustration();

            $slug = $entry->slugify($entry->getTitle());
            $entry->setSlug($slug);

            if ($illustration !== null){
                $illustrationName = $this->generateUniqueFileName(). '.' . $illustration->guessExtension();
                try {
                    $illustration->move(
                        $this->getParameter('images_journal_directory'), $illustrationName
                    );
                } catch (FileException $e) {
                    $this->addFlash("exception", $e);
                }
                $entry->setIllustration($illustrationName);
            }

            $tagsList = $form->getData();
            $tagsList = $tagsList->getTags();
            $tags = explode(";", $tagsList[0]);
            $entry->setTags($tags);

            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($entry);

            $gestionnaire->flush();


            $this->addFlash("journal", "L'article a bien été modifié");
        }

        return $this->render('/journal/new_entry.html.twig', [
            'Form' => $form->createView()]);
    }


    /**
     * @Route("/{slug}", name="single_entry")
     */

    public function singleEntry(JournalEntry $entry, string $slug, Request $request){

        $entry = $this->getDoctrine()->getRepository(JournalEntry::class)->findOneBy(['slug' => $slug]);

        $depot = $this->getDoctrine()->getRepository(Commentary::class);
        $comments = $depot->findBy(['journalEntry' => $entry->getId()]);

        $comment = new Commentary();
        $comment->setJournalEntry($entry);
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

            $this->addFlash("blog", "Le commentaire a bien été posté !");

            return $this->redirect($request->getUri()."#commentaires");
        }

        return $this->render('/journal/single_entry.html.twig', [
            'entry' => $entry,
            'comments' => $comments,
            "commentForm" => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="del_entry")
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function journalEntrySupression($id){
        $depot  = $this->getDoctrine()->getRepository(JournalEntry::class);
        $entry = $depot->find($id);

        $illustration = $entry->getIllustration();

        if ($illustration !== null){
            unlink($this->getParameter('images_journal_directory').$illustration);
        }

        $gestionnaire = $this->getDoctrine()->getManager();
        $gestionnaire->remove($entry);
        $gestionnaire->flush();

        $this->addFlash("journal", "Article supprimé");

        return $this->redirectToRoute("journal_index");
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