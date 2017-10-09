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
    public function sendMail(UserInterface $user, array $params, $template)
    {
        $message = (new \Swift_Message($params['subject']))
            ->setFrom('no-reply@chimaf.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->template->render(
                    $template,
                    $params
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
