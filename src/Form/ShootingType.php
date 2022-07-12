<?php

namespace App\Form;

use App\Entity\Shooting;
use App\Form\ShootingImageType;
use Symfony\Component\Form\AbstractType;
use Twig\Node\Expression\Binary\SubBinary;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ShootingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class, [
                'attr' => [ 'rows' => 15],
                'required' => false,
            ])
            ->add('price')
            ->add('shootingImages', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('isActive', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Yes' => 1,
                    'No' => 0
                ]
            ])
            // ->add('shootingImages', CollectionType::class, [
            //     'entry_type' => ShootingImageType::class,
            //     'entry_options' => [
            //         'label' => false,
            //     ],
            //     'by_reference' => false,
            //     'allow_add' => true,
            //     'allow_delete' => true
            // ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shooting::class,
        ]);
    }
}
