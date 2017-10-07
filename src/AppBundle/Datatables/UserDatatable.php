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
class UserDatatable extends BaseDatatable
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
