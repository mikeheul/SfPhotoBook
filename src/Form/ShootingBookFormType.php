<?php

namespace App\Form;

use App\Entity\Package;
use App\Entity\Shooting;
use App\Entity\ShootingBook;
use Doctrine\ORM\EntityRepository;
use App\Repository\PackageRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Annotations\Annotation\Enum;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ShootingBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('packages', EntityType::class, [
                'attr' => ['class' => 'select'],
                'mapped' => false,
                'class' => Package::class,
                'query_builder' => function (PackageRepository $pr) use ($options) {
                    return $pr->findPackagesByShooting($options['id']);
                }
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => 10
                ],
                'required' => false
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShootingBook::class,
            'id' => 1
        ]);
    }
}
