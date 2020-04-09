<?php

namespace App\Form\Admin\Order;

use App\Entity\DeliveryMethod;
use App\Entity\Orders;
use App\Entity\PaymentMethod;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name', TextType::class, [
                'label' => 'ФИО',
                'attr' => [
                    'placeholder' => 'Введите ФИО',
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Телефон',
                'attr' => [
                    'placeholder' => '0501234567',
                ],
            ])
            ->add('payment_method', EntityType::class, [
                'required' => false,
                'label' => 'Способ оплаты',
                'class' => PaymentMethod::class,
                'choice_label' => 'name',
                'placeholder' => 'выбрать',
            ])
            ->add('delivery_method', EntityType::class, [
                'required' => false,
                'label' => 'Способ доставки',
                'class' => DeliveryMethod::class,
                'choice_label' => 'name',
                'placeholder' => 'выбрать',
            ])
            ->add('postcode', TextType::class, [
                'required' => false,
                'label' => 'Почтовый индекс',
                'attr' => [
                    'placeholder' => 'Введите индекс',
                ],
            ])
            ->add('region', TextType::class, [
                'label' => 'Область',
                'attr' => [
                    'placeholder' => 'Введите область',
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Город',
                'attr' => [
                    'placeholder' => 'Введите город',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Адрес',
                'attr' => [
                    'placeholder' => 'Введите адрес',
                ],
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'Комментарий',
                'attr' => [
                    'placeholder' => 'Введите комментарий',
                ],
            ])
            ->add('admin_note', TextareaType::class, [
                'required' => false,
                'label' => 'Заметка администратора',
            ])
            ->add('products', CollectionType::class, [
                'label' => false,
                'entry_type' => OrderProductType::class,
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
