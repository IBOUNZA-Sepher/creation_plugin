<?php
if (! class_exists('MV_Slider_Settings')) {
    class MV_Slider_Settings{
        
       public static $options;

       public function __construct() {
           self::$options = get_option('mv_slider_options');
           add_action('admin_init', array( $this, 'admin_init'));
           //var_dump(self::$options);

       }
       //on va s'occuper des différentes sections qu'on aura à gérer pour la page option
       public function admin_init(){
           register_setting('mv_slider_group','mv_slider_options', array($this, 'mv_slider_validate'));

            //première partie de la section
            add_settings_section(//Trois paramettres
                'mv_slider_main_section',//nom de la fonction
                'How does it work?',
                null,
                'mv_slider_page1'

            );
            add_settings_field(
                'mv_slider_shortcode',//la reférence dans la base de donnée clé
                'Shortcode', //le titre de la section
                array($this, 'mv_slider_shortcode_callback'),
                'mv_slider_page1',
                'mv_slider_main_section'
            );

            //seconde parties de la section
            add_settings_section(//Trois paramettres
                'mv_slider_second_section',//nom de la fonction
                'Other Plugin Option?',
                null,
                'mv_slider_page2'

            );
            add_settings_field(
                'mv_slider_title',//la reférence dans la base de donnée comme clé
                'Slider Title', //le titre de la section
                array($this, 'mv_slider_title_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'label_for' => 'mv_slider_title'
                )
            );
            add_settings_field(
                'mv_slider_bullets',//la reférence dans la base de donnée comme clé
                'Display Bullets', //le titre de la section
                array($this, 'mv_slider_bullets_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'label_for' => 'mv_slider_bullets'
                )
            );
            add_settings_field(
                'mv_slider_style',//la reférence dans la base de donnée comme clé
                'Slider Style', //le titre de la section
                array($this, 'mv_slider_style_callback'),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                    ),
                    'label_for' => 'mv_slider_style'
                )
            );
       }
       //associé à la 1ère partie de la section
       public function mv_slider_shortcode_callback(){
        ?>
        <span>Use the shortcode [mv_slider] to display the slider in any page/post/widget </span>   
        <?php
       }

       //associé à la second parties de la section
       public function mv_slider_title_callback($args){
        ?>
        <input type="text"
        name="mv_slider_options[mv_slider_title]"
        value="<?php echo isset(self::$options['mv_slider_title'])? esc_attr(self::$options['mv_slider_title']): ''; ?>"
        id="mv_slider_title"
        /> 
        <?php
       }
       public function mv_slider_bullets_callback($args){
        ?>
        <input type="checkbox"
        name="mv_slider_options[mv_slider_bullets]"
        value="1"
        id="mv_slider_bullets"
        <?php 
        if (isset(self::$options['mv_slider_bullets'])){
            checked("1", self::$options['mv_slider_bullets'], true);
        }
         ?>
        /> 
        <label for="mv_slider_bullets">Whether to display bullets or not</label>
        <?php
       }
       public function mv_slider_style_callback($args){
        ?>
        <select name="mv_slider_options[mv_slider_style]" id="mv_slider_style">
            <option value="style-1" <?php 
              isset(self::$options['mv_slider_style'])? selected('style-1', self::$options['mv_slider_style'], true): ''; 
            ?>>Style-1</option>
            <option value="style-2" <?php 
              isset(self::$options['mv_slider_style'])? selected('style-2', self::$options['mv_slider_style'], true): ''; 
            ?>>Style-2</option>

        </select>
        
        <?php
       }
       /*public function mv_slider_style_callback($args){
        ?>
        <select name="mv_slider_options[mv_slider_style]" id="mv_slider_style">
            <?php foreach ($args['items'] as item): ?>
                <option value="<?php echo esc_attr($item); ?>"
                    <?php 
                    isset(self::$options['mv_slider_style'])? selected($item, self::$options['mv_slider_style'], true): '';
                    ?>>     <?php echo esc_html(ucfirst($item)); ?> </option>
            <?php endforeach; ?>

        </select>
        
        <?php
       }*/

       public function mv_slider_validate($input){//c'est une fonction qui sert de filtre
            $new_input = array();
            foreach($input as $key=>$value){// les sont mv_slider_style, mv_slider_bullets, mv_slider_title
                //$new_input[$key] = sanitize_text_field($value);
                switch ($key) {
                    case 'mv_slider_title':
                        if (empty($value)) {
                            //si la personne n'a pas rempli la partie text, on lui envoie un message d'erreur
                            add_settings_error('mv_slider_options', 'mv_slider_message','The title field can not be left empty','error');
                            $value = 'Please, type some text';
                        }
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                    /*case 'mv_slider_url':
                        $new_input[$key] = esc_url_raw($value);
                        break;
                    case 'mv_slider_int':
                        $new_input[$key] = absint($value);
                        break;*/
                    default:
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }
            }
            return $new_input;
       }

    }
}