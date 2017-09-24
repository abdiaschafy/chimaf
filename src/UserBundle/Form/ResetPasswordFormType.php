<?php

/**
 * Created by PhpStorm.
 * User: gabriel.notong
 * Date: 28/03/2017
 */

namespace Cil\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'text', array(
                'required'  =>  true,
                'label'         =>  'password_oublie.form.field_label.nom_utilisateur',
                'label_attr'    =>  array('class'   =>  'text-default'),
                'attr' => array('class'         => 'form-control',
                    'autofocus'     =>  'autofocus'
                )
            ))
            ->add('captcha', 'Gregwar\CaptchaBundle\Type\CaptchaType', array(
                'label_attr'    =>  array('class'   =>  'text-default'),
                'label' => 'password_oublie.form.field_label.captcha',
                'width' => 200,
                'height' => 50,
                'length' => 6,
                'quality' => 90,
                'distortion' => true,
                'background_color' => [115, 194, 251],
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cil\AppBundle\Entity\User',
            'translation_domain' => 'password_oublie_form'
        ));
    }

    public function getName()
    {
        return 'cil_reset_password_formtype';
    }
}
