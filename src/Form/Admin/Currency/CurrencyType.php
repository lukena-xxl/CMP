<?php

namespace App\Form\Admin\Currency;

use App\Entity\Currency;
use App\Services\Common\TranslationRecipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractType
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
            ->add('short', TextType::class, [
                'label' => 'Сокращенно',
                'attr' => [
                    'placeholder' => 'Введите сокращенное название',
                ],
            ])
            ->add('translation_short', TextType::class, [
                'label' => 'Сокращенно',
                'attr' => [
                    'placeholder' => 'Введите сокращенное название',
                ],
                'mapped' => false,
                'data' => $this->translationRecipient->getTranslation(isset($options['data']) ? $options['data'] : false, 'uk', 'short'),
            ])
            ->add('abbr', \Symfony\Component\Form\Extension\Core\Type\CurrencyType::class, [
                'label' => 'Международное обозначение',
            ])
            ->add('symbol', TextType::class, [
                'label' => 'Знак',
                'attr' => [
                    'placeholder' => 'Введите знак',
                ],
            ])
            ->add('display', ChoiceType::class, [
                'label' => 'Показывать по умолчанию',
                'choices'  => [
                    'Сокращенно' => 'short',
                    'Международное обозначение' => 'abbr',
                    'Знак' => 'symbol',
                ],
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
            'data_class' => Currency::class,
        ]);
    }
}
