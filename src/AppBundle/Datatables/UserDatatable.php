<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class UserDatatable
 *
 * @package AppBundle\Datatables
 */
class UserDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        /** ==========================================================================================
        NB: Il es possible d'enlever chaque élément présent dans le datatables en allant dans
        la section $this->features->set, sous [extensions], dans l'array, ajouter les éléments du dom
        et rétirer la lettre qui correspond à l'option à retiter.
        - f = formulaire de recherche globale
        - l = liste de sélection du nombre d'éléments par page
        - i = informations: enregistrements xxxx dans xxx
        - p = pagination
        - r = le loader lors du chargement des données
        - t = la table
        =========================================================================================== **/

        $this->language->set(array(
            'cdn_language_by_locale' => true
            //'language' => 'de'
        ));

        $this->ajax->set(array(
            'pipeline' => 10
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'global_search_type' => 'like',
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
            ->add('first_name', Column::class, array(
                'title' => 'Prénom',
                ))
            ->add('last_name', Column::class, array(
                'title' => 'Nom',
                ))
            ->add('email', Column::class, array(
                'title' => 'Email',
                ))
            ->add('lastLogin', DateTimeColumn::class, array(
                'title' => 'Dernière connexion',
                'date_format' => 'L',
                ))
            ->add('language', Column::class, array(
                'title' => 'Language',
                ))
            ->add('phone', Column::class, array(
                'title' => 'Phone',
                ))
            ->add('groups.name', Column::class, array(
                'title' => 'Role',
                'orderable' => false,
                'data' => 'groups[, ].name'
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'user_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-info btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'user_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
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
        return 'AppBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}
