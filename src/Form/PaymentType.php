<?php

namespace App\Form;

use App\Entity\Payment;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['mapped'=>false])
            ->add('amount', NumberType::class)
            ->add('hidden', CheckboxType::class, ['required' => false])
            ->add('hidden_participant', CheckboxType::class, ['required' => false, 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            'attr' => ['id' => 'payment-form'],
        ]);
    }


}
