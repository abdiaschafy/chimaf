<?php
namespace AppBundle\Controller;

use AppBundle\Datatables\ProduitDatatable;
use AppBundle\Entity\CategorieProduit;
use AppBundle\Entity\Produit;
use AppBundle\Form\CategorieType;
use AppBundle\Form\ProduitType;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 * @Route("produit")
 */
class ProduitController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="produit_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(ProduitDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('@App/Produit/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/categorie/{id}", name="produit_categorie")
     * @param Request $request
     * @param CategorieProduit $categorieProduit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function produitsCategorieAction(Request $request, CategorieProduit $categorieProduit)
    {
        $form = $this->createForm(CategorieType::class, $categorieProduit);
        $form->handleRequest($request);

        return $this->render('@App/Produit/produits_par_categorie.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    /**
     * Creates a new produit entity.
     *
     * @Route("/new", name="produit_new", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', 'Le produit '.$produit->getDesignation().' a été créé avec succès !');
            return $this->redirectToRoute('produit_show', array('id' => $produit->getId()));
        }

        return $this->render('@App/Produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
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
        $editForm = $this->createForm('AppBundle\Form\ProduitType', $produit);
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
        return $this->redirectToRoute('produit_list');
    }
}
