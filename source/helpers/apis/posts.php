<?php
class WP_AiPress_Posts_Rest_Route extends WP_AiPress_General_Functions {

    public function __construct()
    {
        add_action( 'rest_api_init', [ $this, 'create_rest_routes' ] );
    }

    public function create_rest_routes(){
        register_rest_route('aipress/v1','/addpost',[
            'methods' => 'POST',
            'callback' => [$this,'addPost'],
            'permission_callback' => [$this,'get_api_permission']
        ]);

        register_rest_route('aipress/v2','/addimage',[
            'methods' => 'POST',
            'callback' => [$this,'addImage'],
            'permission_callback' => [$this,'get_api_permission']
        ]);
        register_rest_route('aipress/v2','/addtags',[
            'methods' => 'POST',
            'callback' => [$this,'addTags'],
            'permission_callback' => [$this,'get_api_permission']
        ]);
        register_rest_route('aipress/v1','/getcats',[
            'methods' => 'GET',
            'callback' => [$this,'getCats'],
            'permission_callback' => [$this,'get_api_permission']
        ]);
    }


    public function getCats(){
        $categories = get_categories( array('hide_empty' => false));
        $data = array();
        foreach ($categories as $cat){
            $data[] = [
                'label' => $cat->name,
                'value' => $cat->term_id
            ];
        }
        return rest_ensure_response($data);
    }

    public function addImage($req){
        $image = sanitize_url($req['image']);
        $desc = sanitize_text_field($req['desc']);
        $data = $this->justImageUpload($image,$desc);
        return rest_ensure_response($data);
    }

    public function addTags($req){
        $tags = $req['tags'];
        $arr = [];
        if(count($tags)>0){
            foreach ($tags as $tag){
                $value =  wp_set_object_terms(0, sanitize_text_field($tag['value']), 'post_tag', true);
                $arr[] = $value;
            }
        }
        $data = [
          'tags' => $arr
        ];
         return rest_ensure_response($data);
    }

    public function addPost($req){
        $title = sanitize_text_field($req['title']);
        $status = sanitize_text_field($req['status']);
        $content = sanitize_post($req['content']);
        $image = $req['image'];
        $image_type = sanitize_text_field($req['image_type']);
        $categories = $req['categories'];
        $tags = $req['tags'];
        $cats = array();
        foreach ($categories as $c){
            $cats[] = $c['value'];
        }
        $my_post = [
            'post_title' => wp_strip_all_tags( $title ),
            'post_content' => $content,
            'post_status' => $status,
            'post_category' => $cats
        ];

        $post_id = wp_insert_post($my_post);

        if($image>0){
//            $this->imageUploader($image,$image_type,$post_id);
            set_post_thumbnail( $post_id, $image);
        }
        if(count($tags)>0){
            foreach ($tags as $tag){
                wp_set_object_terms($post_id, sanitize_text_field($tag['value']), 'post_tag', true);
            }
        }
        $data = [
            'status' => 'ok',
            'link' => get_permalink($post_id),
            'edit' => admin_url('post.php?post='.$post_id.'&action=edit')
        ];

        return rest_ensure_response($data);

    }





}