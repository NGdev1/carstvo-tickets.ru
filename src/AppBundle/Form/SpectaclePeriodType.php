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

use AppBundle\Controller\Admin\SpectaclePeriodController;
use AppBundle\Entity\SpectaclePeriod;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
class SpectaclePeriodType extends AbstractType
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
            ->add('name', null, ['label' => 'Название представления', 'attr' => ['class' => 'input_green']])
            ->add('description', TextareaType::class, ['label' => 'Описание представления (для афиши)', 'attr' => ['class' => 'input_green']])
            ->add('startDate', DateType::class, ['label' => 'Дата начала', 'widget' => 'single_text', 'html5' => false, 'format' => 'dd.MM.yyyy', 'attr' => ['class' => 'js-datepicker input_green']])
            ->add('endDate', DateType::class, ['label' => 'Дата окончания', 'widget' => 'single_text', 'html5' => false, 'format' => 'dd.MM.yyyy', 'attr' => ['class' => 'js-datepicker input_green']])
            ->add('costOfFood', NumberType::class, ['label' => 'Стоимость питания (в рублях)', 'attr' => ['class' => 'input_green']])
            ->add('numberOfTickets', NumberType::class, ['label' => 'Количество билетов на каждое представление', 'attr' => ['class' => 'input_green']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SpectaclePeriod::class
        ]);
    }
}
