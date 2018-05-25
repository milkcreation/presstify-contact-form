<?php

namespace
{
    use tiFy\Plugins\ContactForm\ContactForm;

    /**
     * Affichage du formulaire de contact.
     *
     * @return string
     */
    function tify_plugin_contact_form_display($echo = true)
    {
        return ContactForm::appInstance()->display($echo);
    }
}