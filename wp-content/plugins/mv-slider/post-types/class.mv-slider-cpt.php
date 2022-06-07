<?php
if ( !class_exists('MV_Slider_Post_Type')) {
    class MV_Slider_Post_Type{
        function __construct() {
            add_action('init', array( $this, 'create_post_type'));//on fait appel aux fct fondamentales
            add_action('add_meta_boxes', array( $this, 'add_meta_boxes'));//on travail sur le lien Link Option
            add_action('save_post', array( $this, 'save_post'), 10, 2);
            //On ajoute 2 colonnes sur le tableau de bord pour les liens
            add_filter('manage_mv-slider_posts_columns', array($this, 'mv_slider_cpt_columns'));
            add_action('manage_mv-slider_posts_custom_column', array($this, 'mv_slider_custom_columns'),10,2);
            //On va ordonner l'ordre d'affichage des pages
            add_filter('manage_edit-mv-slider_sortable_columns', array( $this,'mv_slider_sortable_columns'));
        }
        public function create_post_type(){//on initialise tous
            register_post_type(
                'mv-slider',
                array(
                    'label'     => 'Slider',
                    'description' => 'Sliders',
                    'labels'    => array(
                        'name'          => 'Sliders',
                        'singular_name' => 'Slider'
                    ),
                    'public'        => true,
                    'supports'      => array( 'title', 'editor', 'thumbnail' ),/*'page-attributes' */
                    'hierarchical'  => false,//pour ne pas avoir des pages parents et enfants
                    'show_ui'       => true,
                    'show_in_menu'  => false,//true pour que Sliders se présente après posts ou Articles
                    'menu_position' => 5,//determine l'endroit où on va retrouver notre plugin
                    'show_in_admin_bar' => true, // pour pouvoir ajouter slider dans le menu de navigation 
                    'show_in_nav_menus' => true, //pour pouvoir ajouter ses posts dans le menu du site
                    'can_export'    => true,// on peut voir ça dans tool
                    'has_archive'   => false,
                    'exclude_from_search' => false,
                    'publicly_queryable'  => true,
                    //'show_in_rest'  => true,// il pourra supporter les API,
                     //ça donne une belle présentation pour la redaction; mais il a un problème*/
                    'menu_icon'     => 'dashicons-images-alt2',//on récuppère l'icon de notre slide sur wordpress

                    //'register_meta_box_cb' => array( $this, 'add_meta_boxes')

                )
            );
        }
        //On va sur la page admin MV-SLIDER on va ajouter 2 colonnes 
        public function mv_slider_cpt_columns( $columns){
            $columns['mv_slider_link_text'] = esc_html__( 'Link Text', 'mv-slider');
            $columns['mv_slider_link_url'] = esc_html__( 'Link URL', 'mv-slider');
            return $columns;
        }
        //on va s'occuper du contenu de la page pour l'affichage
        public function mv_slider_custom_columns($column, $post_id){
            switch($column){
                case 'mv_slider_link_text':
                    echo esc_html(get_post_meta($post_id, 'mv_slider_link_text',true));
                break;
                case 'mv_slider_link_url':
                    echo esc_url(get_post_meta($post_id, 'mv_slider_link_url',true));
                break;
            }

        }
        //On va ordonner l'ordre d'affichage des pages par rapport au text
        public function mv_slider_sortable_columns($columns){
            $columns['mv_slider_link_text'] = 'mv_slider_link_text';
            return $columns;
        }

        //on travail sur le lien Link Options; l'emplacement et l'apparence
        public function add_meta_boxes(){
            add_meta_box(
                'mv_slider_meta_box',
                'Link Options',
                array( $this, 'add_inner_meta_boxes'),
                'mv-slider',
                'normal',
                'high'  
            );
        }
        
        //On travail sur l'association de la page à la base de donné
        public function add_inner_meta_boxes($post){
            //il fait appel à la table qui possède la page du lien 'Link Options'
            require_once(MV_SLIDER_PATH.'views/mv-slider_metabox.php');
        }
        //on va gérer les mises à jours de notre Option de lien : Link Options
        public function save_post($post_id){
            /**on s'assure que les donnés entres bien dans la base de donnée */
            if( isset($_POST['mv_slider_nonce'])){
                if( !wp_verify_nonce($_POST['mv_slider_nonce'], 'mv_slider_nonce')){
                    return;
                }
            }
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
                return;
            }
            if(isset( $_POST['post_type']) && $_POST['post_type']==='mv-slider'){
                if(!current_user_can('edit_page', $post_id)){
                    return;
                } elseif( !current_user_can('edit_post', $post_id)){
                    return;
                }
            }
            /**on met les élément qui permettront à récupérer les informations du post */
            if ( isset( $_POST['action']) && $_POST['action']=='editpost'){
                $old_link_text = get_post_meta( $post_id, 'mv_slider_link_text', true);
                $new_link_text = $_POST['mv_slider_link_text'];
                $old_link_url  = get_post_meta( $post_id, 'mv_slider_link_url', true);
                $new_link_url  = $_POST['mv_slider_link_url'];

                if(empty( $new_link_text)){//si les informations sont vides
                    update_post_meta( $post_id, 'mv_slider_link_text', 'Add some text');
                } else{//si les informations sont bien remplies
                    update_post_meta( $post_id, 'mv_slider_link_text', sanitize_text_field($new_link_text), $old_link_text );
                }

                if(empty( $new_link_url)){//si c'est vide
                    update_post_meta( $post_id, 'mv_slider_link_url', '#');
                } else{//si les informations sont bien remplies
                    update_post_meta( $post_id, 'mv_slider_link_url', sanitize_text_field($new_link_url), $old_link_url );
                }
                

            }
        }

    }
}
