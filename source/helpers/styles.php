<?php
// register styles

class WP_AiPress_Styles{
    public function __construct(){
        // Add action of admin css
        add_action('admin_enqueue_scripts',[$this,'admin']);
        // Add action of frontend css
        add_action('wp_enqueue_scripts',[$this,'frontpage'],100);
    }

    public function admin(){
        wp_enqueue_style(
            'aipress-admin-css',
            AIPRESS_URL.'source/cpanel/style.css?v='.AIPRESS_VS,
            array(),
            time()

        );

        wp_enqueue_style(
            'toastify-admin-css',
            AIPRESS_URL.'source/assets/ReactToastify.min.css?v='.AIPRESS_VS,
            time()
        );
        wp_enqueue_style(
            'react-confirm-alert',
            AIPRESS_URL.'source/assets/react-confirm-alert.css?v='.AIPRESS_VS,
            time()
        );
        wp_enqueue_style(
            'aipress-react-modal-video',
            AIPRESS_URL.'source/assets/modal-video.min.css?v='.AIPRESS_VS,
            time()
        );

        wp_enqueue_style(
            'aipress-rc-slider',
            AIPRESS_URL.'source/assets/rc-slider.css?v='.AIPRESS_VS,
            time()
        );
        wp_enqueue_style(
            'aipress-toggle',
            AIPRESS_URL.'source/assets/toggle.css?v='.AIPRESS_VS,
            time()
        );
        wp_enqueue_style(
            'aipress-tooltip',
            AIPRESS_URL.'source/assets/tooltip.css?v='.AIPRESS_VS,
            time()
        );
        wp_enqueue_style(
            'aipress-gutenberg-sidebar',
            AIPRESS_URL.'source/gutenberg/sidebar/build/style-index.css?v='.AIPRESS_VS,
            time()
        );
        wp_enqueue_style(
            'aipress-react-tabs',
            AIPRESS_URL.'source/assets/react-tabs.css?v='.AIPRESS_VS,
            time()
        );


    }

    public function frontpage(){
        wp_enqueue_style(
            'aipress-cke-css',
            AIPRESS_URL.'source/cpanel/ckstyles.css?v='.AIPRESS_VS,
            time()
        );

    }
    
}

new WP_AiPress_Styles();