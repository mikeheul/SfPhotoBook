<?php

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Country;

class SearchForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Search...']
            ])
            ->add('min', NumberType::class, [
                'html5' => true,
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Min Price...', 'min' => 0]
            ])
            ->add('max', NumberType::class, [
                'html5' => true,
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Max Price...', 'min' => 0]
            ])
            ->add('location', CountryType::class, [
                'label' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}