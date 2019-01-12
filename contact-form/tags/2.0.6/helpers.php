<?php

use tiFy\Plugins\ContactForm\ContactForm;

/**
 * Récupération de l'instance du formulaire de contact ou Affichage.
 *
 * @param string $name Nom de qualification du formulaire.
 *
 * @return string|ContactForm
 */
function contact_form($name = 'contact-form')
{
    return app(ContactForm::class);
}