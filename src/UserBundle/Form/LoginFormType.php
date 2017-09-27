<?php
namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class LoginFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username',  TextType::class, array(
                'label'         =>  'connexion.form.field_label.nom_utilisateur',
                'label_attr'    =>  array('class'   =>  'text-color-white'),
                'attr'          =>  array(
                    'class'   =>  'form-control',
                    'autofocus'     =>  'autofocus'
                )
            ))
            ->add('_password', PasswordType::class, array(
                'label'         =>  'connexion.form.field_label.mot_de_passe',
                'label_attr'    =>  array('class'   =>  'text-color-white'),
                'attr'          =>  array('class'   =>  'form-control')
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'app_user_login';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
