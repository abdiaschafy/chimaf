<?php
namespace UserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->
        add('last_name', TextType::class, array(
            'label' => 'creation_acces.form.field_label.nom',
            'label_attr' => array('class' => 'text-default required'),
            'required' => false,
            'attr' => array(
                'class' => 'form-control input-sm',
                'maxlength' => 50
            )
        ))->
        add('first_name', TextType::class, array(
            'label' => 'creation_acces.form.field_label.prenom',
            'label_attr' => array('class' => 'text-default required'),
            'required' => false,
            'attr' => array(
                'class' => 'form-control input-sm',
                'maxlength' => 50
            )
        ))->
        add('email', EmailType::class, array(
            'label' => 'creation_acces.form.field_label.mail',
            'label_attr' => array('class' => 'text-default required'),
            'required' => false,
            'attr' => array(
                'class' => 'form-control input-sm',
            )
        ))->
        add('phone', TextType::class, array(
            'label' => 'creation_acces.form.field_label.telephone',
            'required' => false,
            'label_attr' => array('class' => 'text-default required'),
            'attr' => array(
                'class' => 'form-control input-sm',
                'maxlength' => 15
            )
        ))->
        add('fax', TextType::class, array(
            'label' => 'creation_acces.form.field_label.fax',
            'required' => false,
            'label_attr' => array('class' => 'text-default'),
            'attr' => array(
                'class' => 'form-control input-sm',
                'maxlength' => 30
            )
        ))->
        add('language', TextType::class, array(
            'required' => true,
            'label' => 'creation_acces.form.field_label.langue',
            'label_attr' => array('class' => 'text-default'),
        ))->
        add('save', SubmitType::class, array(
            'label' => 'creation_acces.form.button_label.demande_creation_compte',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'translation_domain' => 'fos_user_bundle',
            'cascade_validation' => true
        ));
    }
}
