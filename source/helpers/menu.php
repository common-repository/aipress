<?php
// Menu settings
class Wp_AiPress_Admin_Menu{
    public function __construct(){
        add_action('admin_menu',[$this,"menus"]);
    }
    public function menus(){
        global $submenu;
        $capability = 'manage_options';
        $slug       = 'aipress';
        add_menu_page(
            __( 'AIPress' ),
            __( 'AIPress' ),
            'manage_options',
            'aipress',
            [$this,'content'],
            AIPRESS_URL.'source/assets/favicon.png',
            999
        );
        if( current_user_can( $capability )  ) {
            $submenu[ $slug ][] = [ __( 'Dashboard', 'aipress' ), $capability, 'admin.php?page=' . $slug .'#/dashboard'];
            $submenu[ $slug ][] = [ __( 'Post Creator', 'aipress' ), $capability, 'admin.php?page=' . $slug .'#/postcreator'];
            $submenu[ $slug ][] = [ __( 'Image Generator', 'aipress' ), $capability, 'admin.php?page=' . $slug .'#/imagegenerator'];
        }

    }

    public function content(){
        $html="";
        if(is_admin()) {
            $html = "<div id='aipress-admin-root'></div>";
            echo $html;
        }
    }



}

new Wp_AiPress_Admin_Menu();