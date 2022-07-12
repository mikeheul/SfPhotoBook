<?php

namespace App\Form;

use App\Entity\ShootingImages;
use Symfony\Component\Form\AbstractType;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ShootingImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShootingImages::class,
        ]);
    }
}
