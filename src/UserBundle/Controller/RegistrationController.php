<?php

namespace UserBundle\Controller;

use AppBundle\Services\Mailer;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use FOS\UserBundle\Controller\RegistrationController as BaseController;


class RegistrationController extends BaseController
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @var Mailer
     */
    private $mailerService;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    public function __construct(UserPasswordEncoder $encoder, Mailer $mailerService, TokenGeneratorInterface $tokenGenerator)
    {
        $this->encoder = $encoder;
        $this->mailerService = $mailerService;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();

        $event = new GetResponseUserEvent($user, $request);
//        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
//                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                list($plainPwd, $encodedPwd, $confirmationUrl) = $this->generatePassWord($user);
                $user->setPassword($encodedPwd);
                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_register');
                    $response = new RedirectResponse($url);
                    // confirmation email
                    $params  = array(
                        'subject' => '[Chimaf] : Confirmation de création de compte utilisateur',
                        'user' => $user, 
                        'confirmationUrl' => $confirmationUrl, 
                        'pwd' => $plainPwd
                    );
                    $template = '@User/Registration/email.txt.twig';
                    $this->mailerService->sendMail($user, $params, $template);

                    $this->addFlash('success', 'L\'utilisateur '.$user->getFullName().' a été créé avec succès. Un mail de confirmation lui a été envoyé.');
                }

//                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@User/Registration/register.html.twig', array(
            'form' => $form->createView(),
            'submitUrl' => $this->generateUrl('fos_user_registration_register')
        ));
    }

    /**
     * Tell the user to check their email provider.
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->getSession()->get('fos_user_send_confirmation_email/email');

        if (empty($email)) {
            return new RedirectResponse($this->get('router')->generate('fos_user_registration_register'));
        }

        $request->getSession()->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('@FOSUser/Registration/check_email.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * @return mixed
     */
    private function getTargetUrlFromSession()
    {
        $key = sprintf('_security.%s.target_path', $this->get('security.token_storage')->getToken()->getProviderKey());

        if ($this->get('session')->has($key)) {
            return $this->get('session')->get($key);
        }
    }

    /**
     * Génère l'url de confirmation de mail, le mot de passe utilisateur et retourne la version claire et encodée
     * @param UserInterface $user
     * @return array
     */
    private function generatePassWord(UserInterface $user)
    {
        $plainPwd = '123'; // à générer automatiquement
        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }
        $confirmationUrl = $this->generateUrl('user_account_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        return array($plainPwd, $this->encoder->encodePassword($user, $plainPwd), $confirmationUrl);
    }
}
