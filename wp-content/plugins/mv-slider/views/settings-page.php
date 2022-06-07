<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <h2 class="nav-tab-wrapper">
        <!--on travail sur le menu de navigation sur la page  MV Slider qui est la page admin du plugin slider-->
        <?php
            //$active_tab = (iseet( $_GET['tab']) ) ? $_GET['tab'] : 'main_options';//envoie une erreur
            //J'ai du truver une solution qui est compatible par rapport au resultat attendu
            $active_tab = $_GET['tab'];//quand on clique sur le lien, cette classe s'active
        ?>
        <a href="?page=mv_slider_admin&tab=main_options" class="nav-tab <?php 
            echo $active_tab == 'main_options' ? 'nav-tab-active' : ''; ?>" >Main Options</a>
        <a href="?page=mv_slider_admin&tab=additional_options" class="nav-tab <?php 
            echo $active_tab == 'additional_options' ? 'nav-tab-active' : ''; ?>">Additional Options</a>
    </h2>
    <form action="options.php" method="post">
        <?php 
        //On a deux section qui sont gérées dans chaqu'une des pages sont rassemblés  
        /*settings_fields('mv_slider_group');
        do_settings_sections('mv_slider_page1');
        do_settings_sections('mv_slider_page2');*/

        //chaque page aura sa section
        if ($active_tab == 'main_options'){
            settings_fields('mv_slider_group');
            do_settings_sections('mv_slider_page1');
        } else{
            settings_fields('mv_slider_group');
            do_settings_sections('mv_slider_page2');
        }
        submit_button('Save Settings');
        ?>
    </form>
</div>