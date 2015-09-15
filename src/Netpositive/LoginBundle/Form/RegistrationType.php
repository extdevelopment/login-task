<?php

namespace Netpositive\LoginBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * RegistrationType.
 *
 * @author zsolt.k
 */
class RegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', null, array(
                'label' => 'form.full_name',
                'translation_domain' => 'NetpositiveLoginBundle',
            ))
            ->add('phone', null, array(
                'label' => 'form.phone',
                'translation_domain' => 'NetpositiveLoginBundle',
            ));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'netpositive_user_registration';
    }
}
