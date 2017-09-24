<?php
namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;


class ResettingController
{
    const SESSION_EMAIL = 'fos_user_send_resetting_email/email';
    const RESETTING_EMAIL_TEMP = 'UserBundle:Resetting:email.html.twig';

    /**
     * Request reset user password: show form
     */
    public function requestAction()
    {
        return $this->renderResetPasswordForm();
    }

    /**
     * Request reset user password: submit form and send email
     *
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \InvalidArgumentException
     */
    public function sendEmailAction(Request $request)
    {
        $username = $this->container->get('request')->request->get('_username');
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        $sessionCaptcha = $request->getSession()->get('gcb_captcha')['phrase'];
        $userCaptcha = $request->request->get('captcha');

        $flashMessageService = $this->get('app.flash_message');

        if (null === $user)
        {
            $flashMessageService->generateFlashMessages(
                'error',
                'resetting.request.invalid_username',
                $username
            );
            return $this->renderResetPasswordForm($username);
        }

        if ($userCaptcha !== $sessionCaptcha && !$request->isXmlHttpRequest())
        {
            $flashMessageService->generateFlashMessages(
                'error',
                'resetting.request.invalid_captcha'
            );
            return $this->renderResetPasswordForm($username);
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl')))
        {
            $flashMessageService->generateFlashMessages(
                'error',
                'resetting.request.password_already_requested'
            );
            return $this->renderResetPasswordForm($username);
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
        $this->sendResettingEmail($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_check_email'));
    }

    /**
     * Demande à l'utilisateur de vérifier sa boite mail afin, de réinitialiser son mot de passe
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \InvalidArgumentException
     */
    public function checkEmailAction()
    {
        $session = $this->container->get('session');
        $email = $session->get(static::SESSION_EMAIL);
        $session->remove(static::SESSION_EMAIL);

        if (empty($email)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $this->get('app.flash_message')->generateFlashMessages(
            'success',
            'resetting.check_email',
            $email
        );
        return $this->forward("AppBundle:Default:home", array('email' => $email));
    }

    /**
     * Reset user password
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function resetAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        // Permet d'afficher le formulaire de réinitialisation du mdp en fonction de la locale de l'utilisateur
        // En utilisant user.language dans le twig, les libellés du formulaire n'est pas pris en compte car généré
        // par FOSUserBundle
        $this->setUserLocaleAndPreferredLanguageInSession($user);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $form = $this->container->get('fos_user.resetting.form');
        $formHandler = $this->container->get('fos_user.resetting.form.handler');
        $process = $formHandler->process($user);

        if ($process)
        {
            $this->get('app.flash_message')->generateFlashMessages(
                'success',
                'resetting.password'
            );
            $response = new RedirectResponse($this->getRedirectionUrl($user));
            $this->authenticateUser($user, $response);

            return $response;
        }

        return $this->container->get('templating')->renderResponse('UserBundle:Resetting:reset.html.' . $this->getEngine(), array(
            'token' => $token,
            'form' => $form->createView(),
        ));
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the userb cn bnbvcx
            // checker (not enabled, expired, etc.).
        }
    }

    /**
     * Generate the redirection url when the resetting is completed.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('cil_homepage');
    }

    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
    }

    /**
     * @param string $action
     * @param string $value
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }


    /**
     * @param User $user
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    private function sendResettingEmail(User $user)
    {
        $template = self::RESETTING_EMAIL_TEMP;
        $url = $this->generateUrl('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );
        $mailObject = new MailObject($template, $context, $this->getEmailSender(), $user->getEmail());
        $this->container->get('app.mailer.twig_swift')->sendMail($mailObject);
    }

    /**
     * Retourne l'email du sender récupéré du fichier de configuration app_paramteters.yml
     *
     * @return string
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    private function getEmailSender()
    {
        return $this->container->getParameter('mailer.user_sender');
    }

    /**
     * Construit et affiche le formulaire de changement de mot de passe avec le captcha
     * @param null $username
     * @return Response
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    private function renderResetPasswordForm($username=null)
    {
        $form = $this->createForm(new ResetPasswordFormType());
        $template = sprintf('UserBundle:Resetting:request.html.%s', $this->getEngine());
        $data = array(
            'resetPwdForm'      =>  $form->createView(),
            'last_username'     =>  $username
        );
        return $this->container->get('templating')->renderResponse($template, $data);
    }

}
