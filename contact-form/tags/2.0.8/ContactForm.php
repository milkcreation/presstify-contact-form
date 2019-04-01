<?php

namespace tiFy\Plugins\ContactForm;

use tiFy\Support\ParamsBag;

/**
 * Class ContactForm
 *
 * @desc Extension PresstiFy de gestion de formulaire de contact.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\ContactForm
 * @version 2.0.8
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\ContactForm\ContactForm à la liste des fournisseurs de services.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\ContactForm\ContactForm;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          ContactForm::class
 *          ...
 *      ]
 * ];
 *
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier contact-form.php
 * @see /vendor/presstify-plugins/contact-form/Resources/config/contact-form.php
 */
class ContactForm extends ParamsBag
{
    /**
     * Liste des attributs de configuration.
     * @var array
     */
    protected $attributes = [
        'content_display' => true,
        'form'            => []
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        add_action('after_setup_theme', function () {
            $this->set(config('contact-form', []))->parse();

            form()->register('contact-form', $this->get('form', []));
        });
    }

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->form();
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return [
            'form' => [
                'title'  => __('Formulaire de contact', 'theme'),
                'attrs'  => [
                    'class' => 'ContactForm'
                ],
                'fields' => [
                    'lastname' => [
                        'title'    => __('Nom', 'theme'),
                        'attrs'    => [
                            'autocomplete' => 'family-name',
                        ],
                        'type'     => 'text',
                        'required' => true
                    ],
                    'firstname' => [
                        'title'    => __('Prénom', 'theme'),
                        'attrs'    => [
                            'autocomplete' => 'given-name'
                        ],
                        'type'     => 'text',
                        'required' => true
                    ],
                    'email' => [
                        'title'        => __('E-mail', 'theme'),
                        'attrs'        => [
                            'autocomplete' => 'email'
                        ],
                        'type'         => 'text',
                        'validations'  => 'is-email',
                        'required'     => true
                    ],
                    'subject' => [
                        'title'    => __('Sujet du message', 'theme'),
                        'type'     => 'text',
                        'required' => true
                    ],
                    'message' => [
                        'title'    => __('Message', 'theme'),
                        'type'     => 'textarea',
                        'required' => true
                    ],
                    'captcha' => [
                        'title'    => __('code de sécurité', 'theme'),
                        'type'     => 'simple-captcha-image',
                        'required' => true
                    ]
                ],
                'addons' => ['mailer']
            ]
        ];
    }

    /**
     * Récupération du formulaire.
     *
     * @return string
     */
    public function form()
    {
        return form('contact-form');
    }
}
