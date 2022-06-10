<?php

class MV_Testimonials_Widget extends WP_Widget{
    public function __construct(){
        $widget_options = array(
            'description'   => __( 'Your most beloved testimonials', 'mv-testimonials' )
        );

        parent::__construct(
            'mv-testimonials',
            'MV Testimonials',
            $widget_options
        );

        add_action(
            'widgets_init', function(){
                register_widget(
                    'MV_Testimonials_Widget'
                );
            }
        );
        if( is_active_widget(false,false, $this->id_base)){
            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        }
    }

    //
    public function enqueue(){
        wp_enqueue_style('mv-testimonials-style-css', MV_TESTIMONIALS_URL . 'assets/css/front_end.css', array(),
                                MV_TESTIMONIALS_VERSION, 'all');
    }

    //On travail sur le coté option qu'on peut avoir avec ce widget
    public function form( $instance ){
        $title  = isset($instance['title']) ? $instance['title']: '';
        $number = isset($instance['number']) ? (int) $instance['number']: 5;//nombre de témoignage par défaut
        $image  = isset($instance['image']) ? (bool) $instance['image']: false;
        $occupation = isset($instance['occupation']) ? (bool) $instance['occupation']: false;
        $company    = isset($instance['company'])    ? (bool) $instance['company']: false;

        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title') ;?>" class="">
                <?php esc_html_e('Title','mv-testimonials') ;?>: </label>

                <input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ;?>"
                name="<?php echo $this->get_field_name('title') ;?>" 
                value="<?php echo esc_attr($title) ;?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('number') ;?>" class="">
                <?php esc_html_e('Number of testimonials to show','mv-testimonials') ;?></label>
                
                <input type="number" class="tiny-text" id="<?php echo $this->get_field_id('number') ;?>"
                step="1" min="1" size="3"  name="<?php echo $this->get_field_name('number') ;?>" 
                value="<?php echo esc_attr($number) ;?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('image') ;?>" class="">
                <?php esc_html_e('Display user image?','mv-testimonials') ;?></label>

                <input type="checkbox" class="checkbox" 
                id="<?php echo $this->get_field_id('image') ;?>"
                name="<?php echo $this->get_field_name('image') ;?>" 
                <?php checked($image) ;?> />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('occupation') ;?>" class="">
                <?php esc_html_e('Display occupation?','mv-testimonials') ;?></label>

                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('occupation') ;?>"
                name="<?php echo $this->get_field_name('occupation') ;?>" 
                <?php checked($occupation) ;?> />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('company') ;?>" class="">
                <?php esc_html_e('Display company?','mv-testimonials') ;?></label>

                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('company') ;?>"
                name="<?php echo $this->get_field_name('company') ;?>" 
                <?php checked($company) ;?> />
            </p>
        <?php

    }

    //On va travailler sur les messages comme affichage pour le client
    public function widget( $args, $instance ){
        $default_title  = 'MV Testimonials';//initialise la valeur par défaut
        $title  = ! empty($instance['title']) ? $instance['title']: $default_title;
        $number = ! empty($instance['number'])? $instance['number']: 5;
        $image  = isset($instance['image']) ?   $instance['image']: false;
        $occupation = isset($instance['occupation']) ?  $instance['occupation']: false;
        $company    = isset($instance['company'])    ?  $instance['company']: false;

        echo $args['before_widget'];
        echo $args['before_title'].$title.$args['after_title'];
        //On fait appel au fichier qui va contenir les messages par défauts
        require( MV_TESTIMONIALS_PATH . 'views/mv-testimonials_widget.php' );
        echo $args['after_widget'];
    }

    //les informations enregistrées dans la database
    public function update( $new_instance, $old_instance ){
        $instance   = $old_instance;//on initialise la variable par la dernière valeur reçue
        $instance['title']  = sanitize_text_field($new_instance['title']);
        $instance['number'] = (int)$new_instance['number'];
        //avec le type checkbox on récupère 1 ou 0
        $instance['image']  = ! empty($new_instance['image']) ? 1 : 0;//si c'est 1=true, et si c'est 0=false
        $instance['occupation']     = ! empty($new_instance['occupation']) ? 1 : 0;
        $instance['company']        = ! empty($new_instance['company']) ? 1 : 0;

        return $instance;
    }
}