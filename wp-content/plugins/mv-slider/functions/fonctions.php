<?php 
//cette fonction traite la partie du slider image par défaut
if(! function_exists('mv_slider_get_placeholder_image')){
    function mv_slider_get_placeholder_image(){
        return "<img src='".MV_SLIDER_URL."assets/images/default.jpg' class='img-fluid wp-post-image'/>";
    }
}

//cette partie traite sur le côté options, si elle a été activer, cochée ou pas
if(! function_exists('mv_slider_options')){
   function mv_slider_options(){
        $show_bullets = isset(MV_Slider_Settings::$options['mv_slider_bullets']) && esc_attr(
            MV_Slider_Settings::$options['mv_slider_bullets'])== 1 ? true:false;
    
        wp_enqueue_script('mv-slider-options-js', MV_SLIDER_URL.'vendor/flexslider/flexslider.js',
            array('jquery'), MV_SLIDER_VERSION, true);//on fait appel au fichier concerné
        
        wp_localize_script('mv-slider-options-js', 'SLIDER_OPTIONS', array('controlNav' =>$show_bullets ));
    }
}
 ?>