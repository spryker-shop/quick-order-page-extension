<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CartNotesWidget\Plugin\Provider\CartNotesWidgetControllerProvider;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class QuoteCartNoteForm extends AbstractType
{
    const FORM_NAME = 'quoteCartNote';
    const FIELD_CART_NOTE = 'cartNote';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction(CartNotesWidgetControllerProvider::ROUTE_CART_NOTES_QUOTE);

        $this->addCartNoteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCartNoteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CART_NOTE, TextareaType::class, [
            'label' => 'cart_notes.quote_form.enter_note',
            'empty_data' => 'cart_notes.quote_form.placeholder',
            'required' => false,
        ]);

        return $this;
    }
}
