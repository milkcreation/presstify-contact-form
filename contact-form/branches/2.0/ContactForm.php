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

use tiFy\Apps\AppController;
use tiFy\Form\Form;
//use tiFy\Core\Router\Router;
//use tiFy\Core\Router\Taboox\Helpers\ContentHook;

final class ContactForm extends AppController
{
    /**
     * Liste des attributs de configuration
     * @var array
     */
    protected $attributes = [];


    /**
     * Initialisation du controleur.
     *
     * @return void
     */
    public function appBoot()
    {
        $this->appAddAction('tify_form_register');
        //$this->appAddAction('tify_router_register');
        add_filter('the_content', [$this, 'the_content']);
    }

    /**
     * Déclaration de formulaire
     *
     * @param Form $formController Classe de rappel du controleur de formulaires.
     *
     * @return void
     */
    public function tify_form_register($formController)
    {
        $formController->register(
            'tiFyPluginContactForm',
            $this->appConfig('form', [])
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
    public function the_content($content)
    {
        // Bypass
        if (! in_the_loop()) :
            return $content;
        elseif (! is_singular()) :
            return $content;
        elseif (Router::get()->getContentHook('tiFyPluginContactForm') !== get_the_ID()) :
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
        return Form::display($this->appConfig('form.name', 'tiFyPluginContactForm'), $echo);
    }
}
