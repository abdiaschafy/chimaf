<?php

namespace AppBundle\Datatables;

use AppBundle\Entity\Facture;
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
 * Class FactureDatatable
 *
 * @package AppBundle\Datatables
 */
class FactureDatatable extends BaseDatatable
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
            ->add('numero', Column::class, array(
                'title' => 'Numero',
                ))
            ->add('dateFacture', DateTimeColumn::class, array(
                'title' => 'DateFacture',
                'date_format' => 'lll',
                ))
            ->add('totalHt', Column::class, array(
                'title' => 'TotalHt',
            ))
            ->add('tva', Column::class, array(
                'title' => 'Tva',
            ))
            ->add('totalTtc', Column::class, array(
                'title' => 'TotalTtc',
                ))
            ->add('client.user.last_name', Column::class, array(
                'title' => 'Client',
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'start_html' => '<div class="text-center">',
                        'end_html' => '</div>',
                        'route' => 'proforma_invoice_generate',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-2x fa-file-pdf-o',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => "Générer la facture proforma",
                            'class' => 'text-danger',
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
        return Facture::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'facture_datatable';
    }
}
