<?php

namespace App\Form\Admin;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('Название'),
                'attr' => [
                    'placeholder' => $this->translator->trans('Введите название'),
                ],
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'label' => 'Slug',
                'help' => $this->translator->trans('Разрешенные символы') . ': [a-z] _ -',
                'attr' => [
                    'placeholder' => $this->translator->trans('Введите slug'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => $this->translator->trans('Описание'),
                'attr' => [
                    'placeholder' => $this->translator->trans('Введите описание'),
                    'rows' => 10,
                    'class' => 'editor',
                ],
            ])
            ->add('image')
            ->add('is_visible', CheckboxType::class, [
                'required' => false,
                'label' => $this->translator->trans('Включена / Отключена'),
                'label_attr' => [
                    'class' => 'font-weight-normal text-dark',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('Сохранить'),
                'attr' => [
                    'class' => 'btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
