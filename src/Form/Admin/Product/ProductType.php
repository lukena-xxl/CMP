<?php

namespace App\Form\Admin\Product;

use App\Entity\Availability;
use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\DeliveryMethod;
use App\Entity\FilterElement;
use App\Entity\PaymentMethod;
use App\Entity\Product;
use App\Entity\ProductCaption;
use App\Form\Admin\ProductImage\ProductImageType;
use App\Form\Admin\ProductItem\ProductItemType;
use App\Repository\AvailabilityRepository;
use App\Repository\FilterElementRepository;
use App\Repository\ProductCaptionRepository;
use App\Services\Common\TranslationRecipient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProductType extends AbstractType
{
    private $translationRecipient;
    private $security;
    private $user;

    public function __construct(TranslationRecipient $translationRecipient, Security $security)
    {
        $this->translationRecipient = $translationRecipient;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', TextType::class, [
                'required' => false,
                'label' => 'Slug',
                'help' => 'Разрешенные символы %symbols%',
                'help_translation_parameters' => [
                    '%symbols%' => ' [a-z, 0-9] _ -',
                ],
                'attr' => [
                    'placeholder' => 'Введите slug',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Название',
                'attr' => [
                    'placeholder' => 'Введите название',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
            ])
            ->add('isVisible', CheckboxType::class, [
                'required' => false,
                'label' => 'Включено / Отключено',
                'label_attr' => [
                    'class' => 'font-weight-normal text-dark',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Категория',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('currency', EntityType::class, [
                'label' => 'Валюта показа',
                'class' => Currency::class,
                'choice_label' => 'abbr',
            ])
            ->add('delivery', EntityType::class, [
                'required' => false,
                'label' => 'Способы доставки',
                'class' => DeliveryMethod::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('payment', EntityType::class, [
                'required' => false,
                'label' => 'Способы оплаты',
                'class' => PaymentMethod::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => ProductItemType::class,
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('images', CollectionType::class, [
                'label' => false,
                'entry_type' => ProductImageType::class,
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('filterElements', EntityType::class, [
                'required' => false,
                'label' => 'Фильтры',
                'class' => FilterElement::class,
                'query_builder' => function (FilterElementRepository $er) {
                    return $er->createQueryBuilder('fe')
                        ->orderBy('fe.position', 'ASC');
                },
                'choice_label' => 'name',
                'group_by' => function (FilterElement $element) {
                    return $element->getFilter()->getName();
                },
                'attr' => [
                    'size' => '15',
                ],
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn-primary',
                ],
            ])
            ->add('submitAndAdd', SubmitType::class, [
                'label' => 'Сохранить и добавить',
                'attr' => [
                    'class' => 'btn-secondary',
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            /** @var Product $data */
            $data = $event->getData();
            $form = $event->getForm();

            $opt = [
                'required' => false,
                'label' => 'Название',
                'attr' => [
                    'placeholder' => 'Введите название',
                ],
                'mapped' => false,
            ];

            if ($data) {
                $opt['data'] = $this->translationRecipient->getTranslation($data, 'uk', 'name');
            }

            $form->add('translation_name', TextType::class, $opt);

            $opt = [
                'required' => false,
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
                'mapped' => false,
            ];

            if ($data) {
                $opt['data'] = $this->translationRecipient->getTranslation($data, 'uk', 'description');
            }

            $form->add('translation_description', TextareaType::class, $opt);

            if ($data) {
                $this->user = $data->getUser();
            } else {
                $this->user = $this->security->getUser();
            }

            $form->add('availability', EntityType::class, [
                'label' => 'Доступность',
                'class' => Availability::class,
                'query_builder' => function (AvailabilityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.user = :user')
                        ->setParameter('user', $this->user)
                        ->orderBy('a.id', 'DESC');
                },
                'choice_label' => 'name',
            ])
            ->add('captions', EntityType::class, [
                'required' => false,
                'label' => 'Подписи',
                'class' => ProductCaption::class,
                'query_builder' => function (ProductCaptionRepository $er) {
                    return $er->createQueryBuilder('pc')
                        ->andWhere('pc.user = :user')
                        ->setParameter('user', $this->user)
                        ->orderBy('pc.position', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
