<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use AppBundle\Entity\UserApplication;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Route;

/**
 * Defines the form used to create and manipulate blog comments. Although in this
 * case the form is trivial and we could build it inside the controller, a good
 * practice is to always define your forms as classes.
 *
 * See https://symfony.com/doc/current/book/forms.html#creating-form-classes
 */
class UserApplicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('content', null, ['required' => false]);

        $builder
            ->add('spectacleDate', DateType::class, ['label' => 'Дата', 'widget' => 'single_text', 'html5' => false, 'format' => 'dd.MM.yyyy', 'attr' => ['class' => 'js-datepicker input_green']])

            ->add('spectacleTime', ChoiceType::class, ['choices' =>
                [
                    '10:00' => '10:00',
                    '12:00' => '12:00',
                    '14:00' => '14:00',
                    '16:00' => '16:00'
                ]
                , 'label' => 'Время', 'attr' => ['class' => 'input_green']])

            ->add('fullName', null, ['label' => 'ФИО', 'attr' => ['class' => 'input_green']])
            ->add('phone', null, ['label' => 'Телефон', 'attr' => ['class' => 'input_green']])
            ->add('numberOfEaters', NumberType::class, ['label' => 'Количество питающихся', 'attr' => ['class' => 'input_green']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserApplication::class
        ]);
    }
}
