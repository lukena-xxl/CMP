<?php

namespace App\Form\Admin\Article;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\ArticleTag;
use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleTagRepository;
use App\Services\Common\TranslationRecipient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    protected $articleCategoryRepository;
    protected $articleTagRepository;
    protected $translationRecipient;

    public function __construct(ArticleCategoryRepository $articleCategoryRepository, ArticleTagRepository $articleTagRepository, TranslationRecipient $translationRecipient)
    {
        $this->articleCategoryRepository = $articleCategoryRepository;
        $this->articleTagRepository = $articleTagRepository;
        $this->translationRecipient = $translationRecipient;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article_category', EntityType::class, [
                'label' => 'Категория',
                'class' => ArticleCategory::class,
                'choice_label' => 'name',
            ])
            ->add('article_tags', EntityType::class, [
                'required' => false,
                'label' => 'Теги публикаци',
                'class' => ArticleTag::class,
                'choice_label' => 'name',
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
            ->add('is_visible', CheckboxType::class, [
                'required' => false,
                'label' => 'Включено / Отключено',
                'label_attr' => [
                    'class' => 'font-weight-normal text-dark',
                ],
            ])
            ->add('publish', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Запланировать показ',
                'help' => 'Выберите дату начала показа публикации или оставьте поле пустым для немедленного показа',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
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
            'data_class' => Article::class,
        ]);
    }
}
