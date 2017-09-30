<?php
namespace AppBundle\Services;


use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Security\Core\User\UserInterface;

class Mailer
{

    private $template;
    private $mailer;
    
    public function __construct(\Swift_Mailer $mailer, TwigEngine $template)
    {
        $this->template = $template;
        $this->mailer = $mailer;
    }
    public function sendConfirmationMail(UserInterface $user, $confirmationUrl, $plainPwd)
    {
        $message = (new \Swift_Message('[Chimaf] : Confirmation de création de compte utilisateur'))
            ->setFrom('no-reply@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->template->render(
                    '@User/Registration/email.txt.twig',
                    array('user' => $user, 'confirmationUrl' => $confirmationUrl, 'pwd' => $plainPwd)
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}