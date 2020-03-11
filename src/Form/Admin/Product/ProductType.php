<?php

namespace App\Form\Admin\Product;

use App\Entity\Availability;
use App\Entity\Category;
use App\Entity\Coefficient;
use App\Entity\Currency;
use App\Entity\Product;
use App\Entity\ProductCaption;
use App\Services\Common\TranslationRecipient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    protected $translationRecipient;

    public function __construct(TranslationRecipient $translationRecipient)
    {
        $this->translationRecipient = $translationRecipient;
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
            ->add('translation_name', TextType::class, [
                'label' => 'Название',
                'attr' => [
                    'placeholder' => 'Введите название',
                ],
                'mapped' => false,
                'data' => $this->translationRecipient->getTranslation(isset($options['data']) ? $options['data'] : false, 'uk', 'name'),
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
            ])
            ->add('translation_description', TextareaType::class, [
                'required' => false,
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
                'mapped' => false,
                'data' => $this->translationRecipient->getTranslation(isset($options['data']) ? $options['data'] : false, 'uk', 'description'),
            ])
            ->add('price', TextType::class, [
                'label' => 'Цена',
                'attr' => [
                    'placeholder' => '0',
                    'class' => 'max-width-100 calc-price',
                ],
            ])
            ->add('discount_percentage', TextType::class, [
                'required' => false,
                'label' => 'Скидка',
                'attr' => [
                    'placeholder' => '0',
                    'class' => 'max-width-100 calc-price',
                ],
            ])
            ->add('discount_start_date', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Дата начала показа скидки',
                'help' => 'Выберите дату начала показа скидки или оставьте поле пустым для немедленного показа',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                ],
            ])
            ->add('discount_end_date', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Дата окончания показа скидки',
                'help' => 'Выберите дату окончания показа скидки или оставьте поле пустым для постоянного показа',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                ],
            ])
            ->add('is_visible', CheckboxType::class, [
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
            ->add('availability', EntityType::class, [
                'label' => 'Доступность',
                'class' => Availability::class,
                'choice_label' => 'name',
            ])
            ->add('captions', EntityType::class, [
                'required' => false,
                'label' => 'Подписи',
                'class' => ProductCaption::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('coefficient', EntityType::class, [
                'required' => false,
                'label' => 'Коэффициент',
                'class' => Coefficient::class,
                'choice_label' => function (Coefficient $coefficient = null) {
                    if ($coefficient) {
                        return $coefficient->getName() . " (" .$coefficient->getRatio() . ")";
                    }
                    return '';
                },
                'placeholder' => 'нет',
                'attr' => [
                    'class' => 'calc-price',
                ],
                'choice_attr' => function (Coefficient $coefficient = null) {
                    $k = false;
                    if ($coefficient) {
                        $k = $coefficient->getRatio();
                    }

                    return $k ? ['data-coefficient' => $k] : [];
                },
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
