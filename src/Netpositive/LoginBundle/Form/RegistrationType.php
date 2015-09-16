<?php

/**
 * This file is part of the LoginBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            ))
            ->remove('username');
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
