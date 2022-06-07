<h3><?php echo (!empty($content))?esc_html($content):esc_html(MV_Slider_Settings::$options['mv_slider_title']);?></h3>
<?php  ?>

<div class="mv-slider flexslider <?php echo (isset(MV_Slider_Settings::$options['mv_slider_style']))?esc_attr(
                            MV_Slider_Settings::$options['mv_slider_style']): 'style-1'; ?>">
    <ul class="slides">
        <?php 
            $args = array(
                'post_type' => 'mv-slider',
                'post_status' => 'publish',
                'post__in' => $id,
                'orderby' => $orderby
            );

            $my_query = new WP_Query($args);//c'est la boucle qui prend tout ce qu'il y'a dans le tableau $args

            //On fait le pont avec la base de donnée. On recherche avec if et on affiche avec while
            if($my_query->have_posts()): //if commence ici
                while ($my_query->have_posts()): $my_query-> the_post();//while commence ici

                //On personalise le lien et le text du lien par rapport à l'article
                $button_text = get_post_meta(get_the_ID(),'mv_slider_link_text', true);
                $button_url = get_post_meta(get_the_ID(),'mv_slider_link_url', true);
        ?>
        <li>
            <?php
                if(has_post_thumbnail()){//si on a mi une image par defaut
                    the_post_thumbnail('full', array('class' => 'img-fluid'));// full, medium,large 
                } else{//si on n'a pas mi une image, on va récupérer une image qu'on va remplacer en attendant 
                    echo mv_slider_get_placeholder_image();
                }
             ?>
            <div class="mvs-container">
                <div class="slider-details-container">
                    <div class="wrapper">
                        <div class="slider-title">
                            <h2><?php the_title();  ?></h2>
                        </div>
                        <div class="slider-description">
                            <div class="subtitle"><?php the_content(); ?></div>
                            <a class="link" href="<?php echo esc_attr($button_url); ?>"><?php echo esc_html($button_text); ?></a>
                        </div>
                    </div>
                </div>              
            </div>
        </li>
       <?php endwhile; //while termine ici
            wp_reset_postdata();
         endif; //if termine icic?>
    </ul>
</div>