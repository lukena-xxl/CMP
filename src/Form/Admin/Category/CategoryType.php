<?php

namespace App\Form\Admin\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Services\Common\TranslationRecipient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    protected $repository;
    protected $translationRecipient;

    public function __construct(CategoryRepository $repository, TranslationRecipient $translationRecipient)
    {
        $this->repository = $repository;
        $this->translationRecipient = $translationRecipient;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentCategory', EntityType::class, [
                'required' => false,
                'label' => 'Родительская категория',
                'class' => Category::class,
                'choices' => $this->repository->getCategoryTree(false),
                'choice_label' => 'name',
                'placeholder' => 'нет',
                'choice_attr' => function ($val, $key, $index) use ($options) {
                    $disabled = false;
                    if (isset($options['data'])) {
                        if ($options['data']->getId() == $key) {
                            $disabled = true;
                        }
                    }

                    return $disabled ? ['disabled' => 'disabled'] : [];
                },
            ])
            ->add('name', TextType::class, [
                'label' => 'Название',
                'attr' => [
                    'placeholder' => 'Введите название',
                ],
            ])
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
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите описание',
                    'class' => 'editor',
                ],
            ])
            ->add('image', HiddenType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'input-img',
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
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            /** @var Category $data */
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
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
