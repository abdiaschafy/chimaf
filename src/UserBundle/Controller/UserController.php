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
use UserBundle\Form\UserRegistrationType;


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
        return $this->render('@User/User/show.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @param User $user
     *
     * @Route("/edit/{id}", name = "user_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm(UserRegistrationType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'L\utilisateur  '.$user->getFirstName().' '.$user->getLastName().' a été modifié avec succès !');
            return $this->redirectToRoute('user_list');
        }
        return $this->render('@User/Registration/register.html.twig', array(
            'form' => $editForm->createView(),
            'submitUrl' => $this->generateUrl('user_edit', array('id' => $user->getId()))
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

    /**
     * Deletes a produit entity.
     *
     * @Route("/delete/{id}", name="user_delete", options = {"expose" = true})
     * @Method("GET")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(User $user)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur '.$user->getFullName().' a été supprimé avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        
        return $this->redirectToRoute('produit_list');
    }
}
