<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produits', CollectionType::class, array(
                'label' => false,
                'entry_type' => ProduitPanierType::class,
                'data' => $options['produits']
            ))
            ->add('tva', IntegerType::class, array(
                'label' => false,
            ))
            ->add('totalTTC', IntegerType::class, array(
                'label' => false
            ))
            ->add('totalHT', IntegerType::class, array(
                'label' => false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Model\UserCart',
            'produits' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cart_form_produit';
    }


}
