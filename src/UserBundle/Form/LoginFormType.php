<?php
namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username',  TextType::class, array(
                'label'         =>  'connexion.form.field_label.nom_utilisateur',
                'label_attr'    =>  array('class'   =>  'text-default'),
                'attr'          =>  array('class'   =>  'form-control',
                    'autofocus'     =>  'autofocus'
                )
            ))
            ->add('_password', PasswordType::class, array(
                'label'         =>  'connexion.form.field_label.mot_de_passe',
                'label_attr'    =>  array('class'   =>  'text-default'),
                'attr'          =>  array('class'   =>  'form-control')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'translation_domain' => 'connexion_form'
        ));
    }

    public function getName()
    {
        return 'login_formtype';
    }
}
