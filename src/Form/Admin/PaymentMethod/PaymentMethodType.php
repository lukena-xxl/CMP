<?php

namespace App\Form\Admin\PaymentMethod;

use App\Entity\PaymentMethod;
use App\Services\Common\TranslationRecipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends AbstractType
{
    protected $translationRecipient;

    public function __construct(TranslationRecipient $translationRecipient)
    {
        $this->translationRecipient = $translationRecipient;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('short_description', TextareaType::class, [
                'required' => false,
                'label' => 'Краткое описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
            ])
            ->add('translation_short_description', TextareaType::class, [
                'required' => false,
                'label' => 'Краткое описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
                'mapped' => false,
                'data' => $this->translationRecipient->getTranslation(isset($options['data']) ? $options['data'] : false, 'uk', 'short_description'),
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
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Изображение',
                'data' => '',
            ])
            ->add('key_1', TextType::class, [
                'required' => false,
                'label' => 'API ключ %num%',
                'label_translation_parameters' => [
                    '%num%' => '1',
                ],
                'attr' => [
                    'placeholder' => 'Введите ключ',
                ],
            ])
            ->add('key_2', TextType::class, [
                'required' => false,
                'label' => 'API ключ %num%',
                'label_translation_parameters' => [
                    '%num%' => '2',
                ],
                'attr' => [
                    'placeholder' => 'Введите ключ',
                ],
            ])
            ->add('key_3', TextType::class, [
                'required' => false,
                'label' => 'API ключ %num%',
                'label_translation_parameters' => [
                    '%num%' => '3',
                ],
                'attr' => [
                    'placeholder' => 'Введите ключ',
                ],
            ])
            ->add('note_key_1', TextareaType::class, [
                'required' => false,
                'label' => 'Описание к ключу %num%',
                'label_translation_parameters' => [
                    '%num%' => '1',
                ],
                'attr' => [
                    'placeholder' => 'Введите описание',
                ],
            ])
            ->add('note_key_2', TextareaType::class, [
                'required' => false,
                'label' => 'Описание к ключу %num%',
                'label_translation_parameters' => [
                    '%num%' => '2',
                ],
                'attr' => [
                    'placeholder' => 'Введите описание',
                ],
            ])
            ->add('note_key_3', TextareaType::class, [
                'required' => false,
                'label' => 'Описание к ключу %num%',
                'label_translation_parameters' => [
                    '%num%' => '3',
                ],
                'attr' => [
                    'placeholder' => 'Введите описание',
                ],
            ])
            ->add('is_visible', CheckboxType::class, [
                'required' => false,
                'label' => 'Включено / Отключено',
                'label_attr' => [
                    'class' => 'font-weight-normal text-dark',
                ],
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
            'data_class' => PaymentMethod::class,
        ]);
    }
}
