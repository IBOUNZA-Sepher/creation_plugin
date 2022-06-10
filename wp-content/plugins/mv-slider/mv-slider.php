<?php

/**
 * Plugin Name: MV Slider
 * Plugin URI: https://www.wordpress.org/mv-slider
 * Description: My plugin's description
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Marcelo Vieira
 * Author URI: https://www.codigowp.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mv-slider
 * Domain Path: /languages
 */

 /*
MV Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
MV Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with MV Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if( ! defined( 'ABSPATH') ){
    //die('Bla bla bla');
    exit;
}

if ( !class_exists('MV_Slider')) {
    class MV_Slider {
        function __construct(){
            $this-> define_constants(); //on travailler sur les paquets

            $this-> load_textdomain();


            //On va faire appel au fichier
            require_once( MV_SLIDER_PATH . 'functions/fonctions.php');

            //On va ajouter un sous menu au nivieau du tableau de bord
            add_action('admin_menu', array($this, 'add_menu'));

            //fichier qui se charge de ma mise en forme des différentes fonctionalités
            require_once( MV_SLIDER_PATH . 'post-types/class.mv-slider-cpt.php');//on fait appel au fichier
            $MV_Slider_Post_Type = new MV_Slider_Post_Type();//on initialise le fichier

            //On va s'occuper de l'options dans setting
            require_once( MV_SLIDER_PATH . 'class.mv-slider-settings.php' );
            $MV_Slider_Settings = new MV_Slider_Settings();

            //On va travail sur la partie shortcode et API
            require_once( MV_SLIDER_PATH . 'shortcodes/class.mv-slider-shortcode.php' );
            $MV_Slider_shortcode = new MV_Slider_shortcode();

            //On va faire appel aux fichiers qu'on a téléchargé comme flexslider, jquery,...
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999);
            //On va manipuler le côté admin avec les fichiers qu'on va faire appel
            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        }
    
        public function define_constants(){
            define('MV_SLIDER_PATH', plugin_dir_path( __FILE__ ));
            define('MV_SLIDER_URL', plugin_dir_url( __FILE__ ));
            define('MV_SLIDER_VERSION', '1.0.0');
        }
        public static function activate(){
            update_option('rewrite_rules','');
        }
        public static function desactivate(){
            flush_rewrite_rules();
        }
        public static function uninstall(){
            delete_option('mv_slider_options');

            $posts = get_posts(
                array(
                    'post_type' => 'mv-slider',
                    'number_posts' => -1,
                    'post_status' => 'any'
                )
            );
            foreach($posts as $post){
                wp_delete_post($post-> ID, true);
            }

        }

        //on va travailler sur les messages qu'on va générer
        public function load_textdomain(){
            load_plugin_textdomain(
                'mv-slider',
                false,
                dirname(plugin_basename( __FILE__) . '/languages/')
            );
        }

        //On commence à créer un sous menu sur le tableau de bord pour Sliders
        public function add_menu(){//add_thème_page; add_options_page
            add_menu_page(//add_plugin_page; il faut enlever l'icon pour l'ajouter au sous menu de plugin
                esc_html__('MV Slider Options','mv-slider'),
                esc_html__('MV Slider','mv-slider'),
                'manage_options',
                'mv_slider_admin',
                array($this, 'mv_slider_setting_page'),
                'dashicons-images-alt2',
                //10  //changer la position de MV Slider
            );
            //on ajoute au sous menu un lien qui ramène à la page du plugin Sliders
            add_submenu_page(//il recevoir au moin 7paramètres sinon il envoie un message d'erreur
                'mv_slider_admin', //'edit-comments.php', pour l'afficher Sur la table commentaires 
                esc_html__('Manage Slides','mv-slider'),
                esc_html__('Manage Slides','mv-slider'),
                'manage_options',
                'edit.php?post_type=mv-slider',
                null,
                null
            );
            //on ajoute au sous menu un lien qui ramène à la page du plugin Sliders de New page
            add_submenu_page(//il recevoir au moin 7paramètres sinon il envoie un message d'erreur
                'mv_slider_admin',//pour l'afficher sous-menu MV Slider 
                esc_html__('Add New Slides','mv-slider'),
                esc_html__('Add New Slides','mv-slider'),
                'manage_options',
                'post-new.php?post_type=mv-slider',
                null,
                null
            );
        }
        public function mv_slider_setting_page(){
            if(!current_user_can('manage_options')){//si on a une erreur, que ça marche qu'en même
                return;
            }
            if( isset($_GET['settings-updated'])){
                //quand la personne enregistre ses information et que s'est dans la base de donnée.
                //on lui envoie un message de success
                add_settings_error('mv_slider_options', 'mv_slider_message',
                esc_html__('Settings saved','mv-slider'),'success');
            }
            settings_errors('mv_slider_options');
            require( MV_SLIDER_PATH . 'views/settings-page.php' );
        }

        //on va faire appel aux plugin qu'on aura besoin immédiatements ou pas
        public function register_scripts(){
            //ce script peut marcher sur toutes les pages automatiquements
            wp_register_script('mv-slider-main-jq', MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js',
             array('jquery'), MV_SLIDER_VERSION, true);
            /*wp_register_script('mv-slider-options-js', MV_SLIDER_URL.'vendor/flexslider/flexslider.js',
             array('jquery'), MV_SLIDER_VERSION, true);*/
            wp_register_style('mv-slider-main-css', MV_SLIDER_URL.'vendor/flexslider/flexslider.css',
             array(), MV_SLIDER_VERSION, 'all');
             //j'ai remarqué que le frontend.css existe et m'empèche de voir mes modifications
             wp_register_style('mv-slider-style-css', MV_SLIDER_URL.'assets/css/front_end.css',
               array(), MV_SLIDER_VERSION, 'all');
        }

        //il va controler plus les information sur la data admin
        public function register_admin_scripts(){
            /*global $pagenow;
            if('post.php' == $pagenow){//c'est pour une utilisation générale: sur les pages, blugs, ...
                wp_enqueue_style('mv-slider-admin', MV_SLIDER_URL.'assets/css/admin.css');//fichier associé
            }*/
            global $typenow;
            if($typenow == 'mv-slider'){//ça sera associé juste où on va faire appel au plugin MV_Slider
                wp_enqueue_style('mv-slider-admin', MV_SLIDER_URL.'assets/css/admin.css',array(),
                    MV_SLIDER_VERSION, 'all');//fichier associé
            }
            
        }
    }
}

if ( class_exists('MV_Slider')) {
    register_activation_hook( __FILE__, array('MV_Slider','activate'));
    register_deactivation_hook( __FILE__, array('MV_Slider','desactivate'));
    register_uninstall_hook( __FILE__, array('MV_Slider','uninstall'));

    $mv_slider = new MV_Slider();   
}