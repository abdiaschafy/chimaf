<?php
namespace AppBundle\Form;

use AppBundle\Entity\Group;
use AppBundle\Repository\GroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Form\UserRegistrationType;

class UserType extends UserRegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name', TextType::class, array(
                'label' => 'form.last_name',
                'label_attr' => array('class' => 'text-color-white required'),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control input-sm',
                    'maxlength' => 50
                )
            ))
            ->add('first_name', TextType::class, array(
                'label' => 'form.first_name',
                'label_attr' => array('class' => 'text-color-white required'),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control input-sm',
                    'maxlength' => 50
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'label_attr' => array('class' => 'text-color-white required'),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control input-sm',
                )
            ))
            ->add('phone', TextType::class, array(
                'label' => 'form.telephone',
                'required' => false,
                'label_attr' => array('class' => 'text-color-white required'),
                'attr' => array(
                    'class' => 'form-control input-sm height-30',
                    'maxlength' => 15
                )
            ))
            ->add('fax', TextType::class, array(
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
                'label' => 'form.language',
                'label_attr' => array('class' => 'text-color-white'),
                'choices' => array(
                    'Français' => 'fr',
                    'English' => 'en'
                ),
                'attr' => array('class' => 'form-control')
            ))
            ->add('groups', EntityType::class, array(
                'required' => true,
                'multiple' => true,
                'query_builder' => function(GroupRepository $gr) {
                    return $gr->createQueryBuilder('g')
                        ->where('g.code = :code')
                        ->setParameter('code', Group::ROLE_CLIENT);
                },
                'class' => 'AppBundle\Entity\Group',
                'choice_label' => 'name',
                'label' => 'form.roles',
                'label_attr' => array('class' => 'text-color-white'),
                'attr' => array('class' => 'form-control')
            ))
            ;
        $builder->remove('plainPassword');
        $builder->remove('username');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'translation_domain' => 'fos_user_bundle'
        ));
    }

    public function getName()
    {
        return 'user_form_type';
    }
}
