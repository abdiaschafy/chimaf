<?php

namespace AppBundle\Form;

use AppBundle\Repository\CategorieProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', TextType::class, array(
                'label' => 'Désignation',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control', 'autofocus' => 'autofocus')
            ))
            ->add('numero', TextType::class, array(
                'label' => 'Numéro',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('quantiteAlerte', IntegerType::class, array(
                'label' => 'Quantité produit alerte',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('quantiteStock', IntegerType::class, array(
                'label' => 'Quantité produit en stock',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('prixUnitaire', IntegerType::class, array(
                'label' => 'Prix unitaire',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('categorie', EntityType::class, array(
                'required' => true,
                'class' => 'AppBundle:CategorieProduit',
                'query_builder' => function(CategorieProduitRepository $cpRepository) {
                    return $cpRepository->createQueryBuilder('cp');
                },
                'choice_label' => 'nom',
                'label' => 'Type de produit',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control')
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_form_produit';
    }


}
