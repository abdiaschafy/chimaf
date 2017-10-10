<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Group;
use AppBundle\Entity\Produit;
use AppBundle\Form\ClientType;
use AppBundle\Form\ProduitType;
use AppBundle\Tools\StringGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * ClientController
 *
 * @Route("client")
 */
class ClientController extends Controller
{
//    /**
//     * Lists all Post entities.
//     *
//     * @param Request $request
//     *
//     * @Route("/list", name="client_list")
//     * @Method("GET")
//     *
//     * @return Response
//     */
//    public function indexAction(Request $request)
//    {
//        $isAjax = $request->isXmlHttpRequest();
//
//        // or use the DatatableFactory
//        /** @var DatatableInterface $datatable */
//        $datatable = $this->get('sg_datatables.factory')->create(ProduitDatatable::class);
//        $datatable->buildDatatable();
//
//        if ($isAjax) {
//            $responseService = $this->get('sg_datatables.response');
//            $responseService->setDatatable($datatable);
//            $responseService->getDatatableQueryBuilder();
//
//            return $responseService->getResponse();
//        }
//
//        return $this->render('@App/Produit/list.html.twig', array(
//            'datatable' => $datatable,
//        ));
//    }

    /**
     * @Route("/new", name="client_new", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        if (null !== $request->get('cart_form_produit')['produits']) {
            $this->saveUserCartTemporary($request);
            $this->addFlash('info', 'Pour terminer le processus de commande, veuillez renseigner vos données de livraison.');
        }
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $client
                ->getUser()
                ->addGroup($em->getRepository('AppBundle:Group')->findOneBy(array('code' => Group::ROLE_CLIENT)));
            $em->persist($client->setNumero('C'.StringGenerator::generate(8)));
            $em->flush();
            
            return $this->redirectToRoute('facture_create', array('client' => $client->getId()));
        }

        return $this->render('@App/Client/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     */
    private function saveUserCartTemporary(Request $request)
    {
        $session = $request->getSession();
        $session->set('produits', $request->get('cart_form_produit')['produits']);
        $session->set('tva', $request->get('cart_form_produit')['tva']);
        $session->set('totalTTC', $request->get('cart_form_produit')['totalTTC']);
        $session->set('totalHT', $request->get('cart_form_produit')['totalHT']);
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="produit_show", options = {"expose" = true})
     * @Method("GET")
     * @param Produit $produit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Produit $produit)
    {
        return $this->render('@App/Produit/show.html.twig', array(
            'produit' => $produit
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/edit/{id}", name="produit_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Produit $produit
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Produit $produit)
    {
        $editForm = $this->createForm(ProduitType::class, $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le produit '.$produit->getDesignation().' a été modifié avec succès !');
            return $this->redirectToRoute('produit_list');
        }

        return $this->render('@App/Produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/delete/{id}", name="produit_delete", options = {"expose" = true})
     * @Method("GET")
     * @param Produit $produit
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Produit $produit)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();

        $this->addFlash('success', 'Le produit '.$produit->getDesignation().' a été supprimé avec succès !');
        return $this->redirectToRoute('user_list');
    }
}
