<?php
namespace AppBundle\Controller;

use AppBundle\Datatables\ProduitDatatable;
use AppBundle\Entity\CategorieProduit;
use AppBundle\Entity\Produit;
use AppBundle\Form\CartType;
use AppBundle\Form\CategorieType;
use AppBundle\Model\UserCart;
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
     * @param Request $request
     *
     * @Route("/list", name="cart_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $panier = $request->getSession()->get('cart') ?: new UserCart();

        $form = $this->createForm(CartType::class, null, array('produits' => $panier->getProduits()));
        $form->handleRequest($request);

        return $this->render('@App/Panier/panier.html.twig', array(
            'cartForm' => $form->createView(),
        ));
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
    public function addToCartAction(Request $request, CategorieProduit $categorieProduit)
    {
        $form = $this->createForm(CategorieType::class, $categorieProduit);
        $form->handleRequest($request);
        $categorieData = $form->getData();

        $panier = $request->getSession()->get('cart') ?: new UserCart();
        foreach ($categorieData->getProduits() as $produit) {
            if ($request->getSession()->has('cart')) {
                foreach ($panier->getProduits() as $cartProd) {
                    if ($cartProd->getId() === $produit->getId()) {
                        $produit->setQuantiteAchetee($cartProd->getQuantiteAchetee() + $produit->getQuantiteAchetee());
                        $panier->removeProduit($cartProd);
                    }
                }
            }
            if ($produit->getQuantiteAchetee() !== null && $produit->getQuantiteAchetee() !== 0)
                $panier->addProduit($produit);
        }
        $request->getSession()->set('cart', $panier);
        $this->addFlash('success', "Vos produits ont été ajoutés au panier");
        return $this->redirectToRoute('produit_categorie', array('id' => $categorieProduit->getId()));
    }

    /**
     * Deletes a produit form cart.
     *
     * @Route("/delete/{id}", name="cart_produit_delete", options = {"expose" = true})
     * @Method("GET")
     * @param Produit $produit
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        try {
            $cart = $request->getSession()->get('cart');
            foreach ($cart->getProduits() as $cartProduit) {
                if ($produit->getId() === $cartProduit->getId()) {
                    $cart->removeProduit($cartProduit);
                }
            }
            $this->addFlash('success', "Le produit #".$produit->getDesignation()."# a été supprimé du panier");
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('cart_list');
    }
}
