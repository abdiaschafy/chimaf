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
 * Class CategorieProduitDatatable
 *
 * @package AppBundle\Datatables
 */
class CategorieProduitDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set($this->getDataTableLanguage());

        $this->ajax->set(array(
        ));

        $this->options->set($this->getDataTableOptions());

        $this->features->set($this->getDataTableFeatures());

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                ))
            ->add('nom', Column::class, array(
                'title' => 'Catégorie',
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'categorie_produit_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Modifier',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'class' => 'btn btn-info btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'categorie_produit_delete',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Supprimer',
                        'icon' => 'fa fa-trash-o',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'class' => 'btn btn-danger btn-xs',
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
        return 'AppBundle\Entity\CategorieProduit';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'categorieproduit_datatable';
    }
}
