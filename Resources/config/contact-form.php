<?php

/**
 * Exemple de configuration.
 */

return [
    'form' => [
        'title'  => __('Formulaire de contact', 'theme'),
        'attrs'  => [
            'class' => 'ContactForm'
        ],
        'fields' => [
            'lastname'  => [
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
            'email'     => [
                'title'       => __('E-mail', 'theme'),
                'attrs'       => [
                    'autocomplete' => 'email'
                ],
                'type'        => 'text',
                'validations' => 'is-email',
                'required'    => true
            ],
            'subject'   => [
                'title'    => __('Sujet du message', 'theme'),
                'type'     => 'text',
                'required' => true
            ],
            'message'   => [
                'title'    => __('Message', 'theme'),
                'type'     => 'textarea',
                'required' => true
            ],
            'captcha'   => [
                'title'    => __('code de sécurité', 'theme'),
                'type'     => 'simple-captcha-image',
                'required' => true
            ]
        ],
        'addons' => [
            'mailer'
        ]
    ]
];
