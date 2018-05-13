<?php

/**
 * @name ContactForm
 * @desc Extension PresstiFy de gestion de formulaire de contact.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstiFy
 * @namespace \tiFy\Plugins\ContactForm
 * @version 2.0.0
 */

namespace tiFy\Plugins\ContactForm;

use tiFy\App\Plugin;
use tiFy\Core\Forms\Forms;
use tiFy\Core\Router\Router;
use tiFy\Core\Router\Taboox\Helpers\ContentHook;

class ContactForm extends Plugin
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Déclaration des événements
        $this->appAddAction('tify_form_register');
        $this->appAddAction('tify_router_register');
        add_filter('the_content', [$this, 'the_content']);
    }

    /**
     * Déclaration de formulaire
     *
     * @return \tiFy\Core\Forms\Factory
     */
    public function tify_form_register()
    {
        $attrs = self::tFyAppConfig('form');

        return Forms::register(
            (isset($attrs['ID']) ? $attrs['ID'] : 'tiFySetContactForm'),
            $attrs
        );
    }

    /**
     * Déclaration de route
     *
     * @return null|\tiFy\Core\Router\Factory
     */
    public function tify_router_register()
    {
        // Bypass
        if(! $router = self::tFyAppConfig('router')) :
            return;
        endif;

        $defaults = [
            'title'       => __('Formulaire de contact', 'tify'),
            'option_name' => 'tiFySetContactForm-hook_id',
        ];
        $args = is_bool($router) ? $defaults : \wp_parse_args($router, $defaults);

        return Router::register(
            'tiFySetContactForm',
            $args
        );
    }

    /**
     *
     */
    final public function the_content($content)
    {
        // Bypass
        if (! in_the_loop()) :
            return $content;
        elseif (! is_singular()) :
            return $content;
        elseif (Router::get()->getContentHook('tiFySetContactForm') !== get_the_ID()) :
            return $content;
        endif;

        // Masque le contenu et le formulaire sur la page d'accroche
        if (! $content_display = $this->appConfig('content_display')) :
            return '';

        // Affiche uniquement le contenu de la page
        elseif ($content_display === 'only') :
            return $content;
        endif;

        $output  = "";
        if (($content_display === 'before') || ($content_display === true)) :
            $output .= $content;
        endif;

        $output .= $this->display(false);
        if ($content_display === 'after' ) :
            $output .= $content;
        endif;

        remove_filter(current_filter(), __METHOD__, 10);

        return $output;
    }

    /**
     *
     */
    public function display($echo = true)
    {
        $form_attrs = $this->appConfig('form', []);
        $form_id = isset($form_attrs['ID']) ? $form_attrs['ID'] : 'tiFySetContactForm';

        return Forms::display($form_id, $echo);
    }
}
