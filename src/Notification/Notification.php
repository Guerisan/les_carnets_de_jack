<?php


namespace App\Notification;


use App\Entity\Contact;
use App\Entity\User;
use Twig\Environment;

class Notification
{
    private $mailer;

    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message('Carnets de Jack : message de ' . $contact->getName()))
            ->setFrom($contact->getEmail())
            ->setTo('contact@bastien-pinna.net')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render("emails/contact.html.twig", [
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($message);
    }

    public function notify_new_user(User $user){
        $message = (new \Swift_Message('Carnets de Jack : inscription d\'un nouveau membre ! ' . $user->getUsername()))
            ->setFrom($user->getEmail())
            ->setTo('contact@bastien-pinna.net')
            ->setReplyTo($user->getEmail())
            ->setBody($this->renderer->render("emails/new_member.html.twig", [
                'user' => $user
            ]), 'text/html');
        $this->mailer->send($message);
    }
}