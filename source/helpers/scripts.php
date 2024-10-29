<?php
// Js script

class WP_AiPress_Scripts{
    public function __construct()
    {
        if ($this->startsWith($_GET['page'], 'aipress')){
            add_action( 'admin_footer', [$this,'admin']);
        }
        add_action('enqueue_block_editor_assets', [$this, 'block_editor']);

    }

    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    public static function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

    public function block_editor(){
        wp_register_script( 'aipress-block-js', AIPRESS_URL . 'source/gutenberg/sidebar/build/index.js' , [ 'wp-blocks','wp-element','wp-editor' ], AIPRESS_VS, true );
        wp_enqueue_script( 'aipress-block-js');
        wp_localize_script('aipress-block-js','appLocalizer',[
            'apiUrl' => $this->permaLinks(),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'openai_api_key' => get_option('aipress_openai_api_key') ? get_option('aipress_openai_api_key') : ""
        ]);
    }

    function permaLinks(){
        return [
          'addpost'  => get_rest_url(null,'aipress/v1/addpost'),
          'addtags'  => get_rest_url(null,'aipress/v2/addtags'),
          'addimage'  => get_rest_url(null,'aipress/v2/addimage'),
          'getcats'  => get_rest_url(null,'aipress/v1/getcats'),
          'saveopenaiapikey'  => get_rest_url(null,'aipress/v1/saveopenaiapikey'),
          'savesettings'  => get_rest_url(null,'aipress/v1/savesettings'),
          'getsettings'  => get_rest_url(null,'aipress/v1/getsettings'),
            'variations'  => get_rest_url(null,'aipress/v1/variations'),
            'bulkimageupload' =>get_rest_url(null,'aipress/v1/bulkimageupload'),

        ];
    }

    public function admin() {
        wp_register_script( 'aipress-admin-js', AIPRESS_URL . 'source/cpanel/cpanel.js' , [ 'jquery', 'wp-element' ], AIPRESS_VS, true );
        wp_enqueue_script( 'aipress-admin-js');
        wp_localize_script( 'aipress-admin-js', 'appLocalizer',[
            'apiUrl' => $this->permaLinks(),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'openai_api_key' => get_option('aipress_openai_api_key') ? get_option('aipress_openai_api_key') : "",
            'pages' => array_map(function($page){
                if ($page->path === 'aipress') {
                    return (object) [
                        'path' => '/',
                        'title' => $page->title,
                        'slug' => $page->slug
                    ];
                }
                if ($this->startsWith($page->path, 'aipress#/')) {
                    return (object) [
                        'path' => $this->after($page->path, 'aipress#'),
                        'title' => $page->title,
                        'slug' => $page->slug
                    ];
                }
                return null;
            },[
                    (object) [
                        'title' => __('AIPress'),
                        'path' => 'aipress',
                        'slug' => 'aipress',
                    ],
                    (object) [
                        'title' => __('Dashboard','aipress'),
                        'path' => 'aipress#/dashboard',
                        'slug' => 'dashboard',
                    ],
                    (object) [
                        'title' => __('Post Creator','aipress'),
                        'path' => 'aipress#/postcreator',
                        'slug' => 'postcreator',
                    ],
                    (object) [
                        'title' => __('Image Generator','aipress'),
                        'path' => 'aipress#/imagegenerator',
                        'slug' => 'imagegenerator',
                    ],
                    (object) [
                        'title' => __('Pro Version','aipress'),
                        'path' => 'aipress#/pro',
                        'slug' => 'pro',
                    ],
                ]
            )
        ]);
    }
}

new WP_AiPress_Scripts();