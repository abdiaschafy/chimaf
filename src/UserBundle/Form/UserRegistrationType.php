<?php
namespace UserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            'label' => 'form.last_name',
            'label_attr' => array('class' => 'text-color-white required'),
            'required' => false,
            'attr' => array(
                'class' => 'form-control input-sm',
                'maxlength' => 50
            )
        ))->
        add('first_name', TextType::class, array(
            'label' => 'form.first_name',
            'label_attr' => array('class' => 'text-color-white required'),
            'required' => false,
            'attr' => array(
                'class' => 'form-control input-sm',
                'maxlength' => 50
            )
        ))->
        add('email', EmailType::class, array(
            'label' => 'form.email',
            'label_attr' => array('class' => 'text-color-white required'),
            'required' => false,
            'attr' => array(
                'class' => 'form-control input-sm',
            )
        ))->
        add('phone', TextType::class, array(
            'label' => 'form.telephone',
            'required' => false,
            'label_attr' => array('class' => 'text-color-white required'),
            'attr' => array(
                'class' => 'form-control input-sm height-30',
                'maxlength' => 15
            )
        ))->
        add('fax', TextType::class, array(
            'label' => 'form.fax',
            'required' => false,
            'label_attr' => array('class' => 'text-color-white'),
            'attr' => array(
                'class' => 'form-control input-sm height-30',
                'maxlength' => 30
            )
        ))
        ->add('language', ChoiceType::class, array(
            'required' => true,
            'label' => 'form.laguage',
            'label_attr' => array('class' => 'text-color-white'),
            'choices' => array(
                'Français' => 'fr',
                'English' => 'en'
            )
        ))
        ->add('save', SubmitType::class, array(
            'label' => 'registration.submit',
            'attr' => array('class' => 'btn btn-success')
        ));
        $builder->remove('plainPassword');
        $builder->remove('username');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'fos_user_bundle'
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
