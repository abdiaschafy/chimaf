<?php
namespace AppBundle\Controller;

use AppBundle\Datatables\ProduitDatatable;
use AppBundle\Entity\CategorieProduit;
use AppBundle\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * PanierController
 *
 * @Route("cart")
 */
class PanierController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="cart_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
       return $this->render('@App/Panier/panier.html.twig');
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/add/{id}", name="cart_add", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param CategorieProduit $categorieProduit
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, CategorieProduit $categorieProduit)
    {
        $form = $this->createForm('AppBundle\Form\CategorieType', $categorieProduit);
        $form->handleRequest($request);
        $categorieData = $form->getData();

        $cart = new ArrayCollection();

        if ($request->getSession()->has('cart')) {
            foreach ($request->getSession()->get('cart') as $cartProd) {
                $cart->add($cartProd);
            }
        }

        foreach ($categorieData->getProduits() as $produit) {
            foreach ($cart as $cartProd) {
                if ($cartProd->getId() === $produit->getId()) {
                    $produit->setQuantiteAchetee($cartProd->getQuantiteAchetee() + $produit->getQuantiteAchetee());
                    $cart->removeElement($cartProd);
                }
            }
            if ($produit->getQuantiteAchetee() !== null && $produit->getQuantiteAchetee() !== 0)
                $cart->add($produit);
        }
        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('produit_categorie', array('id' => $categorieProduit->getId()));
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
