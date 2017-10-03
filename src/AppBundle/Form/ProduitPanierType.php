<?php

namespace AppBundle\Form;

use AppBundle\Repository\CategorieProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitPanierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', TextType::class, array(
                'required' => false,
                'label' => false,
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control', 'autofocus' => 'autofocus')
            ))
            ->add('quantiteAchetee', IntegerType::class, array(
                'required' => false,
                'label' => 'Quantité',
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('prixUnitaire', IntegerType::class, array(
                'required' => false,
                'label' => false,
                'label_attr' => array('class' => 'text-default'),
                'attr' => array('class' => 'form-control', 'readonly' => 'readonly')
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
