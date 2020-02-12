<?php

namespace App\Form\Admin\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $roles;

    public function __construct(array $user_roles)
    {
        $this->roles= $user_roles;
    }

    private function roleChoices()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $parseRole = explode('ROLE_', $role);
            $roles[$parseRole[1]] = $role;
        }

        return $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'required' => false,
                'label' => 'Доступность',
                'choices' => $this->roleChoices(),
                'multiple' => true,
                'help' => 'По умолчанию %role%',
                'help_translation_parameters' => [
                    '%role%' => ' USER',
                ],
            ])
            ->add('login', TextType::class, [
                'label' => 'Логин',
                'attr' => [
                    'placeholder' => 'Введите логин',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Поля пароля должны совпадать',
                'options' => [
                    'attr' => [
                        'class' => 'password-field'
                    ]
                ],
                'required' => true,
                'first_options'  => [
                    'label' => 'Пароль',
                    'attr' => [
                        'placeholder' => 'Введите пароль',
                    ],
                ],
                'second_options' => [
                    'label' => 'Пароль повторно',
                    'attr' => [
                        'placeholder' => 'Введите пароль повторно',
                    ],
                ],
                'empty_data' => function (User $entity = null) {
                    if ($entity) {
                        return $entity->getPassword();
                    }
                    return '';
                },
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Введите Email',
                ],
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'label' => 'Телефон',
                'attr' => [
                    'placeholder' => '0501234567',
                ],
            ])
            ->add('first_name', TextType::class, [
                'required' => false,
                'label' => 'Имя',
                'attr' => [
                    'placeholder' => 'Введите имя',
                ],
            ])
            ->add('middle_name', TextType::class, [
                'required' => false,
                'label' => 'Отчество',
                'attr' => [
                    'placeholder' => 'Введите отчество',
                ],
            ])
            ->add('second_name', TextType::class, [
                'required' => false,
                'label' => 'Фамилия',
                'attr' => [
                    'placeholder' => 'Введите фамилию',
                ],
            ])
            ->add('region', ChoiceType::class, [
                'required' => false,
                'label' => 'Область',
                'placeholder' => 'выберите область',
            ])
            ->add('birth_date', BirthdayType::class, [
                'required' => false,
                'label' => 'Дата рождения',
            ])
            ->add('is_block', CheckboxType::class, [
                'required' => false,
                'label' => 'Заблокировать / Разблокировать',
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
            'data_class' => User::class,
        ]);
    }
}
