<?php
namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', 'text', array(
                'required'  =>  true,
                'label'     =>  'infos_personnelles.form.field_label.prenom',
                'label_attr'=>   array('class'   =>  'text-default'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                    'autofocus'     => 'autofocus',
                )
            ))
            ->add('last_name', 'text', array(
                'required'  =>  true,
                'label'     =>  'infos_personnelles.form.field_label.nom',
                'label_attr'=>   array('class'   =>  'text-default'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                )
            ))
            ->add('email', 'email', array(
                'required'  =>  true,
                'label'     =>  'infos_personnelles.form.field_label.mail',
                'label_attr'=>   array('class'   =>  'text-default'),
                'attr'      =>  array(
                    'class'         => 'form-control input-sm',
                )
            ));
//
//            $builder
//                ->add('groupe', 'entity', array(
//                    'class'         =>  'AppBundle:Groupe',
//                    'query_builder' => function (EntityRepository $er) {
//                        return $er->createQueryBuilder('g')
//                            ->where('g.code = :code')
//                            ->setParameter('code', Groupe::CLIENT_CODE);
//                    },
//                    'property'      =>  'name',
//                    'required'      =>  true,
//                    'label'         =>  'infos_personnelles.form.field_label.role',
//                    'label_attr'    =>   array('class'   =>  'text-default'),
//                    'attr'          =>  array(
//                        'class' => 'form-control input-sm cil-group',
//                    )
//                )) ;


        $builder
            ->add('language', 'cil_locale', array(
                'required' => false,
                'label' =>  'infos_personnelles.form.field_label.langue_preferee',
                'label_attr' => array('class' => 'text-default'),
                'placeholder' => 'infos_personnelles.form.field_label.choix_langue',
            ))
            ->add('client', 'entity', array(
                'class'         =>  'AppBundle:Client',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.raisonSociale', 'ASC');
                },
                'property'      =>  'raisonSociale',
                'required'      =>  false,
                'label'         =>  'infos_personnelles.form.field_label.client',
                'label_attr'    =>   array('class'   =>  'text-default'),
                'placeholder'   =>  'infos_personnelles.form.field_label.choix_client',
                'attr'          =>  array(
                    'class'         => 'form-control input-sm',
                )
            ))
            ->add('phone', 'text', array(
                'required'      =>  true,
                'label'         =>  'infos_personnelles.form.field_label.telephone',
                'label_attr'    =>   array('class'   =>  'text-default'),
                'attr' => array(
                    'class'         => 'form-control input-sm ',
                )
            ))
            ->add('fax', 'text', array(
                'required'      =>  false,
                'label'         =>  'infos_personnelles.form.field_label.fax',
                'label_attr'    =>   array('class'   =>  'text-default'),
                'attr' => array(
                    'class'         => 'form-control input-sm',
                )
            ))
            ->add('enabled', 'checkbox', array(
                'label'     =>  'infos_personnelles.form.field_label.actif',
                'label_attr'    =>   array('class'   =>  'text-default'),
                'attr' => array(
                    'class'         => 'margin-left-10',
                ),
                'required'  => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cil\AppBundle\Entity\User',
            'translation_domain' => 'infos_personnelles_form'
        ));
    }

    public function getName()
    {
        return 'appbundle_usertype';
    }
}
