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
        $this->language->set($this->getDataTableLanguage());

        $this->ajax->set(array());

        $this->options->set($this->getDataTableOptions());

        $this->features->set($this->getDataTableFeatures());

        $this->columnBuilder
            ->add('designation', Column::class, array(
                'title' => 'Désignation',
                ))
            ->add('numero', Column::class, array(
                'title' => 'Code du produit',
                ))
            ->add('quantiteAlerte', Column::class, array(
                'title' => 'Quantité critique',
                ))
            ->add('quantiteStock', Column::class, array(
                'title' => 'Quantité en stock',
                ))
            ->add('categorie.nom', Column::class, array(
                'title' => 'Catégorie du produit',
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
