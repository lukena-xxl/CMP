<?php

namespace App\Form\Admin\Availability;

use App\Entity\Availability;
use App\Services\Common\TranslationRecipient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailabilityType extends AbstractType
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
            ->add('color', ColorType::class, [
                'required' => false,
                'label' => 'Цвет',
                'attr' => [
                    'class' => 'max-width-50',
                ]
            ])
            ->add('short_description', TextType::class, [
                'required' => false,
                'label' => 'Краткое описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
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
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            /** @var Availability $data */
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
                'label' => 'Краткое описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                ],
                'mapped' => false,
            ];

            if ($data) {
                $opt['data'] = $this->translationRecipient->getTranslation($data, 'uk', 'short_description');
            }

            $form->add('translation_short_description', TextType::class, $opt);

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
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Availability::class,
        ]);
    }
}
