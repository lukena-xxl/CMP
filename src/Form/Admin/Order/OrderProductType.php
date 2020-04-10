<?php

namespace App\Form\Admin\Order;

use App\Entity\OrderProduct;
use App\Entity\ProductItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class, [
                'label' => false,
            ])
            ->add('quantity', NumberType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => '1',
                    'class' => 'max-width-100 product-quantity',
                    'min' => 1,
                ],
                'html5' => true,
                'empty_data' => '1',
            ])
            ->add('price', HiddenType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'product-price',
                ]
            ])
            ->add('image', HiddenType::class, [
                'label' => false,
            ])
            ->add('product', EntityType::class, [
                'label' => false,
                'class' => ProductItem::class,
                'choice_label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderProduct::class,
        ]);
    }
}
