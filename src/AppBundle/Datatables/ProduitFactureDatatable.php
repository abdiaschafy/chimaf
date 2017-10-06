<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

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
        $this->language->set(array(
            'cdn_language_by_locale' => true
            //'language' => 'de'
        ));

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'global_search_type' => 'like',
            'page_length' => $this->getPageLength(),
            'length_menu' => $this->getLengthMenu(),
            'search_in_non_visible_columns' => true,
            'dom'     =>    '<"row"
                            <"col-md-12"
                                <"col-md-4 col-sm-4 text-left"l>
                                <"col-md-4 col-sm-4 text-center">
                                <"col-md-4 col-sm-4 text-center padding-5"f>
                            >
                         >
                         tr
                         <"col-md-12"
                            <"row"
                                <"pull-left"i>
                                <"pull-right"p>
                            >
                         >',
        ));

        $this->features->set(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => false
        ));

        $this->columnBuilder
            ->add('quantite', Column::class, array(
                'title' => 'Quantite',
            ))
            ->add('produit.numero', Column::class, array(
                'title' => 'Produit Numero',
            ))
            ->add('produit.designation', Column::class, array(
                'title' => 'Produit Designation',
            ))
            ->add('prix', Column::class, array(
                'title' => 'Prix unitaire vendu',
                ))
            ->add('prixTotal', Column::class, array(
                'title' => 'Prix total',
                ))
            ->add('produit.quantiteStock', Column::class, array(
                'title' => 'Produit QuantiteStock',
                ))
            ->add('produit.prixUnitaire', Column::class, array(
                'title' => 'Prix unitaire réel',
                ))
            ->add('facture.numero', Column::class, array(
                'title' => 'Facture Numero',
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
        return 'AppBundle\Entity\ProduitFacture';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'produitfacture_datatable';
    }
}
