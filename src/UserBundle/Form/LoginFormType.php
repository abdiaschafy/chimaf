<?php
namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, array(
                'label' => 'security.login.username',
                'label_attr' => array('class' => 'text-color-white'),
                'attr' => array(
                    'class' => 'form-control',
                    'autofocus' => 'autofocus'
                )
            ))
            ->add('_password', PasswordType::class, array(
                'label' => 'security.login.password',
                'label_attr' => array('class' => 'text-color-white'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('_submit', SubmitType::class, array(
                'label' => 'security.login.submit',
                'attr' => array('class' => 'btn btn-success')
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'fos_user_bundle'
        ));
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
