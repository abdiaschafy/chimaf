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
 * Class BaseDatatable
 *
 * @package AppBundle\Datatables
 */
class BaseDatatable extends AbstractDatatable
{
    /**
     * @return array
     */
    public function getDataTableOptions()
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

        return array(
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
        );
    }

    /**
     * @return array
     */
    public function getDataTableFeatures()
    {
        return array(
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
        );
    }

    /**
     * @return array
     */
    public function getDataTableLanguage()
    {
        return array(
            'cdn_language_by_locale' => true
            //'language' => 'de'
        );
    }
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    }
    
    protected function getLengthMenu()
    {
        return array(5,10,15,20,50);
    }
    
    protected function getPageLength()
    {
        return $this->getLengthMenu()[0];
    }
}
