<?php


namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\JournalEntry;
use App\Form\CommentFormType;
use App\Form\JournalEntryType;
use App\Tool\ControllerTool;
use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/journal")
 */
class JournalController extends AbstractController
{
    /**
     * @Route("/", name="journal_index")
     */

    public function journalIndex()
    {

        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $entries = $depot->findAll();

        return $this->render('/journal/journal_index.html.twig', [
            'entries' => $entries,
        ]);
    }

    function handleImage($entry, $illustration)
    {
        $illustrationName = ControllerTool::generateUniqueFileName() . '.' . $illustration->guessExtension();
        try {
            $illustration->move(
                $this->getParameter('images_journal_directory'), $illustrationName
            );
        } catch (FileException $e) {
            $this->addFlash("exception", $e);
        }
        $entry->setIllustration($illustrationName);
    }

    /**
     * @Route("/tag/{tag}", name="entry_category")
     */

    public function category($tag)
    {
        $depot = $this->getDoctrine()->getRepository(JournalEntry::class)->findAll();
        $entries = [];

        foreach ($depot as $entry) {
            if (in_array($tag, $entry->getTags())) {
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
     * @Route("/nouveau", name="new_entry")
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function newEntry(Request $request)
    {

        $entry = new JournalEntry();

        $entry->setDate(time());

        $form = $this->createForm(JournalEntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $illustration = $entry->getIllustration();
            $slug = ControllerTool::slugify($entry->getTitle());
            $entry->setSlug($slug);
            //TODO :: Il n'est pas possible de remplir la date voulue dans le formulaire, Symfony rentre systématiquement le 01/01/1970
            //On rentre donc ici par dépit la date du jour, sans prendre en compte celle entrée dans le formulaire
            $entry->setDate(time());

            if ($illustration !== null) {
                $this->handleImage($entry, $illustration);
            }

            $tagsList = $form->getData();
            $tagsList = $tagsList->getTags();
            $tags = explode(";", $tagsList[0]);
            $entry->setTags($tags);

            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($entry);
            $gestionnaire->flush();

            $this->addFlash("journal", "Article posté !");

            return $this->redirectToRoute("journal_index");

        }

        return $this->render('/journal/new_entry.html.twig', [
            'form' => $form->createView(),
            'entry' => $entry,
        ]);
    }

    /**
     * @Route("/edition/{id}", name="edit_entry"))
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function edit_entry($id, Request $request)
    {

        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $entry = $depot->find($id);

        $form = $this->createForm(JournalEntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $slug = ControllerTool::slugify($entry->getTitle());
            $entry->setSlug($slug);

            $illustration = $form['illustration']->getData();

            if ($illustration) {
                $this->handleImage($entry, $illustration);
            }

            $tagsList = $form->getData();
            $tagsList = $tagsList->getTags();
            $tags = explode(";", $tagsList[0]);
            $entry->setTags($tags);

            $gestionnaire = $this->getDoctrine()->getManager();

            $gestionnaire->persist($entry);

            $gestionnaire->flush();


            $this->addFlash("journal", "L'article a bien été modifié");
            return $this->redirectToRoute('journal_index');
        }

        return $this->render('/journal/new_entry.html.twig', [
            'form' => $form->createView()]);
    }


    /**
     * @Route("/{slug}", name="single_entry")
     */

    public function singleEntry(JournalEntry $entry, string $slug, Request $request)
    {

        $entry = $this->getDoctrine()->getRepository(JournalEntry::class)->findOneBy(['slug' => $slug]);

        //Préparation des images pour le lazy loading
        $dom = new DOMDocument;
        $dom->loadHTML($entry->getContent());
        $imgs = $dom->getElementsByTagName('img');
        $imagesSrc = [];
        foreach ($imgs as $img) {
            $originalSrc = $img->getAttribute('src');
            $newNode = $img;
            $newNode->setAttribute('src', '');
            $newNode->setAttribute('data-src', $originalSrc);
            $newNode->setAttribute('class', 'trigger expandable_picture lazy');
            $newNode->parentNode->replaceChild($newNode, $img);
        }

        $newContent = $dom->getElementsByTagName('body')->item(0);

        $innerHTML = "";
        $children = $newContent->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $newContent->ownerDocument->saveHTML($child);
        }
        $entry->setContent($innerHTML);

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

            return $this->redirect($request->getUri() . "#commentaires");
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

    public function journalEntrySupression($id)
    {
        $depot = $this->getDoctrine()->getRepository(JournalEntry::class);
        $entry = $depot->find($id);

        $illustration = $entry->getIllustration();

        if ($illustration !== null) {
            try {
                unlink($this->getParameter('images_journal_directory') . $illustration);
            } catch (\Exception $e) {
                $this->addFlash("journal", "Suppression du fichier $illustration impossible. Le fichier n'existe peut-être plus");
            }
        }

        $gestionnaire = $this->getDoctrine()->getManager();
        $gestionnaire->remove($entry);
        $gestionnaire->flush();

        $this->addFlash("journal", "Article supprimé");

        return $this->redirectToRoute("journal_index");
    }

}