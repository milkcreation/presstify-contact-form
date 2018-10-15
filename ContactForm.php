<?php

/**
 * @name ContactForm
 * @desc Extension PresstiFy de gestion de formulaire de contact.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstify-plugins/contact-form
 * @namespace \tiFy\Plugins\ContactForm
 * @version 2.0.1
 */

namespace tiFy\Plugins\ContactForm;

use tiFy\Kernel\Parameters\AbstractParametersBag;
use tiFy\Form\Form;

class ContactForm extends AbstractParametersBag
{
    /**
     * Liste des attributs de configuration.
     * @var array {
     * @var bool|string $content_display Affichage du contenu de la page lorsque le formulaire est associé à une page.
     *                                      'before'|true : Affiche le contenu du post avant le formulaire.
     *                                      'after' : Affiche le contenu du post après le formulaire.
     *                                      'hide' : Masque le contenu du post.
     *                                       'only' : Affiche seulement le contenu du post, le formulaire est masqué et
     *     doit être appelé manuellement. false : Masque à la fois le contenu du post et le formulaire.
     * @var array $form {
     *      Attributs de configuration du formulaire
     * }
     * @var $router @todo {
     *      Attributs de configuration de la page d'affichage du formulaire
     *
     * @var string $title Intitulé de qualification de la route
     * @var string $desc Texte de description de la route
     * @var string object_type Type d'objet (post|taxonomy) en relation avec la route
     * @var string object_name Nom de qualification de l'objet en relation (ex: post, page, category, tag ...)
     * @var string option_name Clé d'index d'enregistrement en base de données
     * @var int selected Id de l'objet en relation
     * @var string list_order Ordre d'affichage de la liste de selection de l'interface d'administration
     * @var string show_option_none Intitulé de la liste de selection de l'interface d'administration lorsqu'aucune
     *     relation n'a été établie
     * }
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
        add_action(
            'wp_loaded',
            function () {
                parent::__construct(config('contact-form', []));

                form()->add('contact-form', $this->get('form'));
            }
        );
    }

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->display();
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
                    [
                        'slug'     => 'lastname',
                        'title'    => __('Nom', 'theme'),
                        'attrs'    => [
                            'autocomplete' => 'family-name',
                        ],
                        'type'     => 'text',
                        'required' => true
                    ],
                    [
                        'slug'     => 'firstname',
                        'title'    => __('Prénom', 'theme'),
                        'attrs'    => [
                            'autocomplete' => 'given-name'
                        ],
                        'type'     => 'text',
                        'required' => true
                    ],
                    [
                        'slug'         => 'email',
                        'title'        => __('E-mail', 'theme'),
                        'attrs'        => [
                            'autocomplete' => 'email'
                        ],
                        'type'         => 'text',
                        'integrity_cb' => 'is_email',
                        'required'     => true
                    ],
                    [
                        'slug'     => 'subject',
                        'title'    => __('Sujet du message', 'theme'),
                        'type'     => 'text',
                        'attrs'    => [
                            'class' => '%s tiFyForm-FieldInput--tify_dropdown--gray'
                        ],
                        'required' => true
                    ],
                    [
                        'slug'     => 'message',
                        'title'    => __('Message', 'theme'),
                        'type'     => 'textarea',
                        'required' => true
                    ],
                    [
                        'slug'     => 'captcha',
                        'title'    => __('code de sécurité', 'theme'),
                        'type'     => 'simple-captcha-image',
                        'required' => true
                    ]
                ],
                'addons' => [
                    'mailer' => true
                ]
            ]
        ];
    }

    /**
     * Affichage du formulaire.
     *
     * @return string
     */
    public function display()
    {
        return form()->display('contact-form');
    }

    /**
     * @todo
     */
    public function the_content($content)
    {
        if (!in_the_loop()) :
            return $content;
        elseif (!is_singular()) :
            return $content;
        elseif (Router::get()->getContentHook('tiFyPluginContactForm') !== get_the_ID()) :
            return $content;
        endif;

        // Masque le contenu et le formulaire sur la page d'accroche
        if (!$content_display = config('contact-form.content_display')) :
            return '';

        // Affiche uniquement le contenu de la page
        elseif ($content_display === 'only') :
            return $content;
        endif;

        $output = "";
        if (($content_display === 'before') || ($content_display === true)) :
            $output .= $content;
        endif;

        $output .= $this->display(false);
        if ($content_display === 'after') :
            $output .= $content;
        endif;

        remove_filter(current_filter(), __METHOD__, 10);

        return $output;
    }
}
