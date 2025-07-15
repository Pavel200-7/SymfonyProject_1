<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use function Sodium\add;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'required' => true,
                'attr' => ['placeholder'=>'Ваше полное имя'],
                'constraints' => [
                    new NotBlank(),
                ],
                'trim' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['placeholder'=>'Введите ваш email'],
                'constraints' => [
                    new NotBlank(),
                ],
                'trim' => true,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Телефон',
                'required' => true,
                'attr' => ['placeholder'=>'+7 (___) ___-__-__)'],
                'constraints' => [
                    new NotBlank(),
                ],
                'trim' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Пароль',
                'required' => true,
                'attr' => ['autocomplete' => 'new-password', 'placeholder'=>'Не менее 6 символов'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите ваш пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен содержать как минимум {{ limit }} символов',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'trim' => true,
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Подтвердите пароль',
                'required' => true,
                'attr' => ['autocomplete' => 'new-password', 'placeholder'=>'Подтвердите пароль'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите ваш пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен содержать как минимум {{ limit }} символов',
                        'max' => 255,
                    ]),
                ],
                'trim' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Зарегистрироваться",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new Assert\Callback(function ($data, ExecutionContextInterface $context, $payload)
                {
                    if ($data->get('plainPassword') !== $data->get('confirmPassword')) {
                        $context->buildViolation('Пароли должны совпадать')
                        ->atPath('confirmPassword')
                        ->addViolation();
                    }
                })
            ]

        ]);
    }
}
