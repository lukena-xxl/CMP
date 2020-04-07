<?php

namespace App\Form\Admin\ProductImage;

use App\Entity\ProductImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('isVisible', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'img-visible',
                ],
            ])
            ->add('position', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input-position',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductImage::class,
        ]);
    }
}
