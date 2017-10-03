<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
/**
 * Class ProduitDatatable
 *
 * @package AppBundle\Datatables
 */
class ProduitDatatable extends BaseDatatable
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
            ->add('designation', Column::class, array(
                'title' => 'Designation',
                ))
            ->add('numero', Column::class, array(
                'title' => 'Numero',
                ))
            ->add('quantiteAlerte', Column::class, array(
                'title' => 'QauntiteAlerte',
                ))
            ->add('quantiteStock', Column::class, array(
                'title' => 'QauntiteStock',
                ))
            ->add('categorie.nom', Column::class, array(
                'title' => 'Category Nom',
                ))
            ->add('facturesDuProduit.id', Column::class, array(
                'title' => 'FacturesDuProduit Id',
                'data' => 'facturesDuProduit[, ].id'
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'produit_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'produit_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-success btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Produit';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'produit_datatable';
    }
}
