<?php
namespace AppBundle\Controller;

use AppBundle\Datatables\FactureDatatable;
use AppBundle\Datatables\ProduitDatatable;
use AppBundle\Datatables\ProduitFactureDatatable;
use AppBundle\Entity\Client;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Produit;
use AppBundle\Entity\ProduitFacture;
use AppBundle\Form\CartType;
use AppBundle\Model\UserCart;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * FactureController
 *
 * @Route("invoice")
 */
class FactureController extends Controller
{

    /**
     * @Security("has_role('ROLE_ACCOUNTANT')")
     * @param Request $request
     * @Route("/list", name="invoice_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function invoiceAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(FactureDatatable::class);
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
     * @Security("has_role('ROLE_ACCOUNTANT')")
     * @param Request $request
     * @Route("/details", name="invoice_details")
     * @Method("GET")
     *
     * @return Response
     */
    public function soldProductsAction(Request $request)
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

        return $this->render('@App/Facture/sold_products_list.html.twig', array(
            'datatable' => $datatable,
        ));
    }
    
    /**
     * @Route("/create/{client}", name="facture_create")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function createBillAction(Request $request, Client $client)
    {
        $session = $request->getSession();
        $produits = $session->get('produits');
        $tva = $session->get('tva');
        $totalTTC = $session->get('totalTTC');
        $totalHT = $session->get('totalHT');
        $objetProduits = (object)json_decode(json_encode($produits));

        try {
            $em = $this->getDoctrine()->getManager();
            $facture = new Facture($client, $totalTTC, $totalHT, $tva);
            $em->persist($facture);
            $em->flush();

            foreach ($objetProduits as $produit) {
                $prod = $em->getRepository('AppBundle:Produit')->findOneBy(array('id' => $produit->id));
                $produitFacture = new ProduitFacture($prod, $facture, $produit->prixUnitaire , $produit->quantiteAchetee);
                // Mise à jour du stock du produit acheté
                $prod->setQuantiteStock($prod->getQuantiteStock() - $produit->quantiteAchetee);
                $em->persist($prod);
                $em->persist($produitFacture);
            }
            $em->flush();
            $this->addFlash('success', "Votre commande a été prise en compte, vous recevrez prochainement votre facture proformat à valider");
            $this->sendMailToAccountant($facture);

            $request->getSession()->clear();
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('chimaf_home');
    }

    /**
     * @Security("has_role('ROLE_ACCOUNTANT')")
     * @Route("/generate/proforma/{id}", name="proforma_invoice_generate")
     * @param Facture $facture
     * @return Response
     */
    public function generateProformaBillAction(Facture $facture)
    {
        $produitsDeLaFacture = $facture->getProduitsDeLaFacture();

        $options = new Options();
        // Pour simplifier l'affichage des images, on autorise dompdf à utiliser
        // des  url pour les nom de  fichier
        $options->set('isRemoteEnabled', TRUE);
        // On crée une instance de dompdf avec  les options définies
        $dompdf = new Dompdf($options);
        // On demande à Symfony de générer  le code html  correspondant à
        // notre template, et on stocke ce code dans une variable
        $html = $this->renderView(
            '@App/Facture/invoice_template.html.twig',
            array('produitsDeLaFacture' => $produitsDeLaFacture, 'facture' => $facture)
        );
        // On envoie le code html  à notre instance de dompdf
        $dompdf->loadHtml($html);
        // On demande à dompdf de générer le  pdf
        $dompdf->render();
        // On renvoie  le flux du fichier pdf dans une  Response pour l'utilisateur
        return new Response ($dompdf->stream());
    }

    private function sendMailToAccountant(Facture $facture)
    {
        $comptable = $this->getDoctrine()->getRepository('AppBundle:User')->getAccountant();
        $facturation_url = $this->generateUrl('invoice_list', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        $params  = array(
            'subject' => '[Chimaf] : Réception de commande',
            'accountant_name' => $comptable->getFullName(),
            'numero_facture' => $facture->getNumero(),
            'date_facture' => $facture->getDateFacture()->format('d/m/Y'),
            'facturation_url' => $facturation_url
        );
        $template = '@App/Mail/invoice.txt.twig';
        $this->get('app.mailer')->sendMail($comptable, $params, $template);
        
    }
}
