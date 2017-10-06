<?php
namespace AppBundle\Controller;

use AppBundle\Datatables\ProduitDatatable;
use AppBundle\Datatables\ProduitFactureDatatable;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Produit;
use AppBundle\Entity\ProduitFacture;
use AppBundle\Form\CartType;
use AppBundle\Model\UserCart;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * FactureController
 *
 * @Route("bill")
 */
class FactureController extends Controller
{

    /**
     * Lists all Post entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="bill_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(ProduitFactureDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('@App/Facture/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }
    
    /**
     * Lists all Post entities.
     *
     * @param Request $request
     *
     * @Route("/sent", name="facture_create")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function billAction(Request $request)
    {
        $produits = $request->get('cart_form_produit')['produits'];
        $tva = $request->get('cart_form_produit')['tva'];
        $totalTTC = $request->get('cart_form_produit')['totalTTC'];
        $totalHT = $request->get('cart_form_produit')['totalHT'];
        $objetProduits = (object)json_decode(json_encode($produits));

        try {
            $em = $this->getDoctrine()->getManager();
            $facture = new Facture($totalTTC, $totalHT, $tva);
            $em->persist($facture);
            $em->flush();

            foreach ($objetProduits as $produit) {
                $prod = $em->getRepository('AppBundle:Produit')->findOneBy(array('id' => $produit->id));
                $produitFacture = new ProduitFacture($prod, $facture, $produit->prixUnitaire , $produit->quantiteAchetee);
                $em->persist($produitFacture);
            }
            $em->flush();
            $this->addFlash('success', "Votre commande a été prise en compte, vous recevrez prochainement votre facture proformat à valider");
            $request->getSession()->clear();
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('chimaf_home');
    }
}
