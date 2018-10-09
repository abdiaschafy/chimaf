<?php
namespace AppBundle\Controller;

use AppBundle\Datatables\CategorieProduitDatatable;
use AppBundle\Entity\CategorieProduit;
use AppBundle\Form\CategorieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * CategorieController
 *
 * @Route("categorie")
 */
class CategorieController extends Controller
{
    /**
     * Lists all category products.
     * @Security("has_role('ROLE_STORE_KEEPER') or has_role('ROLE_ADMIN')")
     * @Route("/list", name="categorie_produit_list")
     * @Method("GET")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(CategorieProduitDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('@App/Categorie/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/all", name="categorie_produit_client_list", options = {"expose" = true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction()
    {
        $categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:CategorieProduit')->findAll();
        
        return $this->render('@App/Categorie/categories_produits.html.twig', array(
            'categories' => $categories
        ));

    }

    /**
     * @Security("has_role('ROLE_STORE_KEEPER') or has_role('ROLE_ADMIN')")
     * @Route("/new", name="categorie_produit_new", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $categorie = new CategorieProduit();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('success', 'La '.$categorie->getNom().' a été créé avec succès !');
            return $this->redirectToRoute('categorie_produit_list');
        }

        return $this->render('@App/Categorie/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_STORE_KEEPER') or has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="categorie_produit_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param CategorieProduit $categorieProduit
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CategorieProduit $categorieProduit)
    {
        $editForm = $this->createForm(CategorieType::class, $categorieProduit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La catégorie '.$categorieProduit->getNom().' a été modifié avec succès !');
            return $this->redirectToRoute('categorie_produit_list');
        }

        return $this->render('@App/Categorie/new.html.twig', array(
            'produit' => $categorieProduit,
            'form' => $editForm->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_STORE_KEEPER') or has_role('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="categorie_produit_delete", options = {"expose" = true})
     * @Method("GET")
     * @param CategorieProduit $categorieProduit
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(CategorieProduit $categorieProduit)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorieProduit);
            $em->flush();

            $this->addFlash('success', 'La catégorie '.$categorieProduit->getNom().' a été supprimée avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('categorie_produit_list');
    }
}
