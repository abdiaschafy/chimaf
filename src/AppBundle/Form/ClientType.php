<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserClientType::class, array(
                'required'  =>  true,
                'label'     =>  false
            ))
            ->add('codePostal', IntegerType::class, array(
                'required'  =>  true,
                'label'     =>  'Code postal',
                'label_attr'=>   array('class'   =>  'text-color-white'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                )
            ))
            ->add('rue', TextType::class, array(
                'required'  =>  true,
                'label'     =>  'Rue',
                'label_attr'=>   array('class'   =>  'text-color-white'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                )
            ))
            ->add('ville', TextType::class, array(
                'required'  =>  true,
                'label'     =>  'Ville',
                'label_attr'=>   array('class'   =>  'text-color-white'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                )
            ))
            ->add('pays', CountryType::class, array(
                'required'  =>  true,
                'label'     =>  'Pays',
                'label_attr'=>   array('class'   =>  'text-color-white'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client',
            'translation_domain' => 'fos_user_bundle'
        ));
    }

    public function getName()
    {
        return 'client_form_type';
    }
}
