<?php

namespace App\Form\Admin\ProductItem;

use App\Entity\Coefficient;
use App\Entity\ProductItem;
use App\Services\Common\TranslationRecipient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductItemType extends AbstractType
{
    private $translationRecipient;

    public function __construct(TranslationRecipient $translationRecipient)
    {
        $this->translationRecipient = $translationRecipient;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Название',
                'attr' => [
                    'placeholder' => 'Введите название',
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Цена',
                'attr' => [
                    'placeholder' => '0',
                    'class' => 'max-width-100 calc-price product_price',
                ],
                'row_attr' => [
                    'class' => 'mb-1',
                ],
            ])
            ->add('coefficient', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Coefficient::class,
                'choice_label' => function (Coefficient $coefficient = null) {
                    if ($coefficient) {
                        return $coefficient->getName() . " (" .$coefficient->getRatio() . ")";
                    }
                    return '';
                },
                'placeholder' => 'выберите коэффициент',
                'attr' => [
                    'class' => 'calc-price product_coefficient',
                ],
                'choice_attr' => function (Coefficient $coefficient = null) {
                    $k = false;
                    if ($coefficient) {
                        $k = $coefficient->getRatio();
                    }

                    return $k ? ['data-coefficient' => $k] : [];
                },
                'row_attr' => [
                    'class' => 'mb-1',
                ],
            ])
            ->add('discountPercentage', TextType::class, [
                'required' => false,
                'label' => 'Скидка',
                'attr' => [
                    'placeholder' => '0',
                    'class' => 'max-width-100 calc-price product_discount_percentage',
                ],
            ])
            ->add('discountStartDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Дата начала показа скидки',
                'help' => 'Выберите дату начала показа скидки или оставьте поле пустым для немедленного показа',
            ])
            ->add('discountEndDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Дата окончания показа скидки',
                'help' => 'Выберите дату окончания показа скидки или оставьте поле пустым для постоянного показа',
            ])
            ->add('displayedQuantity', NumberType::class, [
                'required' => false,
                'label' => 'Количество по умолчанию',
                'attr' => [
                    'placeholder' => '1',
                    'class' => 'max-width-100',
                    'min' => 1,
                ],
                'html5' => true,
                'empty_data' => '1',
            ])
            ->add('minOrderQuantity', NumberType::class, [
                'required' => false,
                'label' => 'Минимальное количество для заказа',
                'attr' => [
                    'placeholder' => '1',
                    'class' => 'max-width-100',
                    'min' => 1,
                ],
                'html5' => true,
                'empty_data' => '1',
            ])
            ->add('maxOrderQuantity', NumberType::class, [
                'required' => false,
                'label' => 'Максимальное количество для заказа',
                'attr' => [
                    'placeholder' => '0',
                    'class' => 'max-width-100',
                    'min' => 0,
                ],
                'html5' => true,
            ])
            ->add('isChecked', CheckboxType::class, [
                'required' => false,
                'label' => 'Выбрано / Не выбрано',
                'label_attr' => [
                    'class' => 'font-weight-normal text-dark',
                ],
                'row_attr' => [
                    'class' => 'mb-0',
                ],
            ])
            ->add('isVisible', CheckboxType::class, [
                'required' => false,
                'label' => 'Включено / Отключено',
                'label_attr' => [
                    'class' => 'font-weight-normal text-info',
                ],
            ])
            ->add('img', HiddenType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input-img-item',
                ],
            ])
            ->add('position', HiddenType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input-position',
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            /** @var ProductItem $data */
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
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductItem::class,
        ]);
    }
}
