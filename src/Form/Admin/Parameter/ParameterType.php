<?php

namespace App\Form\Admin\Parameter;

use App\Entity\Category;
use App\Entity\Parameter;
use App\Repository\CategoryRepository;
use App\Services\Common\TranslationRecipient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterType extends AbstractType
{
    protected $translationRecipient;
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, TranslationRecipient $translationRecipient)
    {
        $this->translationRecipient = $translationRecipient;
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parameter_categories', EntityType::class, [
                'required' => false,
                'label' => 'Категории',
                'class' => Category::class,
                'choice_label' => 'name',
                //'choices' => $this->categoryRepository->getCategoryTree(),
                'multiple' => true,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }
}
