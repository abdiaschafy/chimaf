<?php

namespace UserBundle\Controller;

use AppBundle\Datatables\UserDatatable;
use AppBundle\Entity\User;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class UserController
 * @package UserBundle\Controller
 */
class UserController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="user_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(UserDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('@User/User/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Finds and displays a Post entity.
     *
     * @param User $user
     *
     * @Route("/show/{id}", name = "user_show", options = {"expose" = true})
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        return $this->render('@User/Group/show.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @param User $user
     *
     * @Route("/edit/{id}", name = "user_edit", options = {"expose" = true})
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function editAction(User $user)
    {
        return $this->render('@User/Group/show.html.twig', array(
            'user' => $user
        ));
    }


    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param Request $request
     * @param string  $token
     * @Route("/confirm/registration/{token}", name = "user_account_confirm", options = {"expose" = true})
     * @Method("GET")
     * @return Response
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher EventDispatcherInterface */
//        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
//        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('user_registration_confirmed', array('user' => $user->getId()));
            $response = new RedirectResponse($url);
        }

//        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed.
     * @Route("/{user}/registration/confirmed", name = "user_registration_confirmed", options = {"expose" = true})
     * @param User $user
     * @Method("GET")
     * @return RedirectResponse
     */
    public function confirmedAction(User $user)
    {
        $this->addFlash('success', $this->container->get('translator')->trans('registration.confirmed', array('username' => $user->getUsername()), 'fos_user_bundle'));
        return $this->redirectToRoute('fos_user_security_login');
    }
}
