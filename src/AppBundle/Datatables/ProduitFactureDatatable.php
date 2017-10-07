<?php

namespace AppBundle\Datatables;

use AppBundle\Entity\ProduitFacture;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class ProduitFactureDatatable
 *
 * @package AppBundle\Datatables
 */
class ProduitFactureDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set($this->getDataTableLanguage());

        $this->ajax->set(array());

        $this->options->set($this->getDataTableOptions());

        $this->features->set($this->getDataTableFeatures());
        
        $this->columnBuilder
            ->add('quantite', Column::class, array(
                'title' => 'Quantité vendue',
            ))
            ->add('produit.numero', Column::class, array(
                'title' => 'Code du produit',
            ))
            ->add('produit.designation', Column::class, array(
                'title' => 'Désignation du produit',
            ))
            ->add('prix', Column::class, array(
                'title' => 'Prix unitaire de vente',
                ))
            ->add('prixTotal', Column::class, array(
                'title' => 'Prix total',
                ))
            ->add('produit.prixUnitaire', Column::class, array(
                'title' => 'Prix unitaire réel',
                ))
            ->add('facture.numero', Column::class, array(
                'title' => 'Numéro facture',
                ))
            ->add('facture.dateFacture', DateTimeColumn::class, array(
                'title' => 'Date facturation',
                'date_format' => 'lll',
            ))
//            ->add(null, ActionColumn::class, array(
//                'title' => $this->translator->trans('sg.datatables.actions.title'),
//                'actions' => array(
//                    array(
//                        'route' => 'produit_delete',
//                        'route_parameters' => array(
//                            'id' => 'id'
//                        ),
//                        'label' => $this->translator->trans('sg.datatables.actions.show'),
//                        'icon' => 'glyphicon glyphicon-eye-open',
//                        'attributes' => array(
//                            'rel' => 'tooltip',
//                            'title' => $this->translator->trans('sg.datatables.actions.show'),
//                            'class' => 'btn btn-primary btn-xs',
//                            'role' => 'button'
//                        ),
//                    ),
//                    array(
//                        'route' => 'produit_delete',
//                        'route_parameters' => array(
//                            'id' => 'id'
//                        ),
//                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
//                        'icon' => 'glyphicon glyphicon-edit',
//                        'attributes' => array(
//                            'rel' => 'tooltip',
//                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
//                            'class' => 'btn btn-primary btn-xs',
//                            'role' => 'button'
//                        ),
//                    )
//                )
//            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return ProduitFacture::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'produitfacture_datatable';
    }
}
